<?php
/**
 * Add English as a third language: post content.
 *
 * LOCAL DEV TOOL — never deployed to or executed on production. The finished local database is
 * exported and imported over the production one instead (see the cutover notes in CLAUDE.md).
 *
 * Two halves, deliberately separated so the translating and the database writing can be reviewed
 * and re-run independently:
 *
 *   --export   Reads every published Ukrainian post of the eight content types and writes the
 *              source text out as JSON "bundles" under tools/i18n-en/. Re-running it never
 *              overwrites an English value that has already been filled in, so it is safe to
 *              re-export after the Ukrainian side changes.
 *
 *   --apply    Creates the English twin of every Ukrainian post (via WPML's own duplication) and
 *              writes the English text from the bundles into it.
 *
 * The bundles are keyed by a hash of the *source string*, never by post id. That is what lets this
 * run again at cutover against a freshly pulled production dump, where the catalogue migration will
 * have allocated different ids. It also dedupes: ~26% of the site's text is repeated verbatim
 * across posts, and a shared dictionary translates each distinct string once.
 *
 * Idempotent: a second --apply reports zero actions.
 *
 * Usage (from the repo root):
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/en-translate.php --export
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/en-translate.php --stats
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/en-translate.php
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/en-translate.php --apply
 */

if ( php_sapi_name() !== 'cli' ) {
	die( 'CLI only.' );
}

require dirname( __FILE__, 5 ) . '/wp-load.php';

$APPLY   = in_array( '--apply', $argv, true );
$EXPORT  = in_array( '--export', $argv, true );
$STATS   = in_array( '--stats', $argv, true );
$VERBOSE = in_array( '--verbose', $argv, true );

/** --only=<post_type>[,<post_type>] narrows --apply, for staging a run one content type at a time. */
$ONLY = array();
foreach ( $argv as $arg ) {
	if ( strpos( $arg, '--only=' ) === 0 ) {
		$ONLY = array_filter( array_map( 'trim', explode( ',', substr( $arg, 7 ) ) ) );
	}
}

const MDT_EN_LANG        = 'en';
const MDT_EN_SRC_LANG    = 'uk';
const MDT_EN_CHUNK_CHARS = 15000;

/** Post types that carry front-end content. acf-field(-group) and nav_menu_item are not content. */
$MDT_EN_POST_TYPES = array( 'page', 'category', 'product', 'projects', 'post', 'suppliers', 'reviews', 'use' );

/** Non-ACF meta that still holds translatable prose. */
$MDT_EN_EXTRA_META = array( 'rank_math_title', 'rank_math_description' );

$MDT_EN_DIR   = dirname( __FILE__ ) . '/i18n-en';
$MDT_EN_DICT  = $MDT_EN_DIR . '/dict';
$MDT_EN_SLUGS = $MDT_EN_DIR . '/slugs.json';

// ---------------------------------------------------------------------------
// Reporting
// ---------------------------------------------------------------------------

$ACTIONS = array();
$NOTES   = array();

function mdt_act( $action, $target, $detail ) {
	global $ACTIONS;
	$ACTIONS[] = array( $action, $target, $detail );
}

function mdt_note( $msg ) {
	global $NOTES;
	$NOTES[] = $msg;
}

function mdt_fatal( $msg ) {
	fwrite( STDERR, "FATAL: $msg\n" );
	exit( 1 );
}

// ---------------------------------------------------------------------------
// What counts as translatable
// ---------------------------------------------------------------------------

/**
 * ACF field name => type, read from the acf-field posts themselves rather than hardcoded, so the
 * set stays correct if the content team adds fields. Only free-text types are ever touched:
 * images, relationships, numbers, links and selects must keep their raw values. Relationship ids
 * stay Ukrainian on purpose: mdt_translate_related_posts() in functions.php resolves them to the
 * current language on read.
 */
function mdt_en_text_fields() {
	static $names = null;
	if ( $names !== null ) {
		return $names;
	}

	global $wpdb;
	$textish = array( 'text', 'textarea', 'wysiwyg' );
	$names   = array();

	$fields = $wpdb->get_results(
		"SELECT post_excerpt AS name, post_content FROM {$wpdb->posts} WHERE post_type = 'acf-field'"
	);
	foreach ( $fields as $field ) {
		$settings = maybe_unserialize( $field->post_content );
		if ( is_array( $settings ) && isset( $settings['type'] ) && in_array( $settings['type'], $textish, true ) ) {
			$names[ $field->name ] = true;
		}
	}

	return $names;
}

/**
 * Repeater and flexible-content sub-fields are stored one meta row per index, named
 * parent_<i>_child (nested arbitrarily deep). Strip the index segments to get back to the field
 * name ACF registered: bl__list_3_bl__item-cl-title -> bl__item-cl-title.
 */
function mdt_en_field_name( $meta_key ) {
	while ( preg_match( '/^.*?_\d+_(.+)$/', $meta_key, $m ) ) {
		$meta_key = $m[1];
	}
	return $meta_key;
}

function mdt_en_is_translatable_meta( $meta_key ) {
	global $MDT_EN_EXTRA_META;

	if ( $meta_key === '' || $meta_key[0] === '_' ) {
		return false;
	}
	if ( in_array( $meta_key, $MDT_EN_EXTRA_META, true ) ) {
		return true;
	}

	$fields = mdt_en_text_fields();
	return isset( $fields[ mdt_en_field_name( $meta_key ) ] );
}

/** A value worth translating: has at least one letter and isn't a bare number. */
function mdt_en_is_translatable_value( $value ) {
	$value = trim( (string) $value );

	if ( $value === '' || is_numeric( $value ) ) {
		return false;
	}

	return (bool) preg_match( '/\p{L}/u', $value );
}

/**
 * Compare two pieces of stored text.
 *
 * wp_update_post() runs the title and body through kses, which turns a bare "&" into "&amp;", so a
 * title like "Milliken & Company" never equals the dictionary value it was written from and the
 * post gets rewritten on every run. Entities are decoded on both sides for the comparison only;
 * what actually gets written is always the dictionary's own text.
 */
function mdt_en_same_text( $a, $b ) {
	$normalise = function ( $value ) {
		return trim( html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' ) );
	};
	return $normalise( $a ) === $normalise( $b );
}

function mdt_en_hash( $value ) {
	return md5( trim( (string) $value ) );
}

/** Latin-only strings (brand and collection names, "Forbo Eternal Colour") need no translating. */
function mdt_en_needs_translating( $value ) {
	return (bool) preg_match( '/[\x{0400}-\x{04FF}]/u', $value );
}

// ---------------------------------------------------------------------------
// Reading the Ukrainian side
// ---------------------------------------------------------------------------

/**
 * Published uk posts of the content types, hierarchical ones shallowest first.
 *
 * Queried through wp_icl_translations directly rather than through WP_Query: WPML scopes normal
 * queries to the current language, and the depth ordering is load-bearing — WPML's make_duplicate()
 * resolves the master's post_parent into the target language at duplication time, so a category
 * duplicated before its parent would silently land at the root of the tree.
 */
function mdt_en_source_posts() {
	global $wpdb, $MDT_EN_POST_TYPES;

	$in   = "'" . implode( "','", array_map( 'esc_sql', $MDT_EN_POST_TYPES ) ) . "'";
	$rows = $wpdb->get_results(
		"SELECT p.ID, p.post_type, p.post_parent
		 FROM {$wpdb->posts} p
		 JOIN {$wpdb->prefix}icl_translations t
		   ON t.element_id = p.ID AND t.element_type = CONCAT('post_', p.post_type)
		 WHERE p.post_status = 'publish'
		   AND p.post_type IN ($in)
		   AND t.language_code = '" . MDT_EN_SRC_LANG . "'
		 ORDER BY p.post_type, p.ID"
	);

	$posts = array();
	foreach ( $rows as $row ) {
		$posts[] = array(
			'id'    => (int) $row->ID,
			'type'  => $row->post_type,
			'depth' => count( get_post_ancestors( (int) $row->ID ) ),
		);
	}

	usort( $posts, function ( $a, $b ) {
		if ( $a['depth'] !== $b['depth'] ) {
			return $a['depth'] - $b['depth'];
		}
		if ( $a['type'] !== $b['type'] ) {
			return strcmp( $a['type'], $b['type'] );
		}
		return $a['id'] - $b['id'];
	} );

	return $posts;
}

/** meta_key => meta_value for the translatable meta of one post. */
function mdt_en_source_meta( $post_id ) {
	global $wpdb;

	$out  = array();
	$rows = $wpdb->get_results( $wpdb->prepare(
		"SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $post_id
	) );

	foreach ( $rows as $row ) {
		if ( ! mdt_en_is_translatable_meta( $row->meta_key ) ) {
			continue;
		}
		if ( ! mdt_en_is_translatable_value( $row->meta_value ) ) {
			continue;
		}
		$out[ $row->meta_key ] = $row->meta_value;
	}

	return $out;
}

/** Every translatable source string on one post: the three post columns plus its meta. */
function mdt_en_source_strings( $post_id ) {
	$post = get_post( $post_id );
	$out  = array();

	foreach ( array( $post->post_title, $post->post_content, $post->post_excerpt ) as $value ) {
		if ( mdt_en_is_translatable_value( $value ) ) {
			$out[] = trim( $value );
		}
	}
	foreach ( mdt_en_source_meta( $post_id ) as $value ) {
		$out[] = trim( $value );
	}

	return $out;
}

/** Full uk slug path, so the slug map can be keyed by something stable across database dumps. */
function mdt_en_slug_path( $post_id ) {
	$slugs = array( get_post_field( 'post_name', $post_id ) );
	foreach ( get_post_ancestors( $post_id ) as $ancestor ) {
		$slugs[] = get_post_field( 'post_name', $ancestor );
	}
	return implode( '/', array_reverse( $slugs ) );
}

// ---------------------------------------------------------------------------
// Bundles
// ---------------------------------------------------------------------------

function mdt_en_read_json( $path ) {
	if ( ! file_exists( $path ) ) {
		return array();
	}
	$data = json_decode( file_get_contents( $path ), true );
	if ( ! is_array( $data ) ) {
		mdt_fatal( "$path is not valid JSON." );
	}
	return $data;
}

function mdt_en_write_json( $path, $data ) {
	$dir = dirname( $path );
	if ( ! is_dir( $dir ) && ! mkdir( $dir, 0755, true ) ) {
		mdt_fatal( "cannot create $dir" );
	}
	$json = json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( $json === false ) {
		mdt_fatal( "cannot encode $path: " . json_last_error_msg() );
	}
	file_put_contents( $path, $json . "\n" );
}

function mdt_en_dict_files() {
	global $MDT_EN_DICT;
	$files = glob( $MDT_EN_DICT . '/*.json' );
	return $files ? $files : array();
}

/**
 * The whole dictionary, plus which file each entry lives in so --export can leave existing
 * entries where they are instead of reshuffling every chunk on each run.
 *
 * @return array{entries: array<string,array>, file_of: array<string,string>}
 */
function mdt_en_load_dict() {
	$entries = array();
	$file_of = array();

	foreach ( mdt_en_dict_files() as $file ) {
		foreach ( mdt_en_read_json( $file ) as $hash => $entry ) {
			$entries[ $hash ] = $entry;
			$file_of[ $hash ] = basename( $file );
		}
	}

	return array( 'entries' => $entries, 'file_of' => $file_of );
}

/** hash => English string, for entries that actually have one. */
function mdt_en_translations() {
	$out = array();
	foreach ( mdt_en_load_dict()['entries'] as $hash => $entry ) {
		if ( isset( $entry['en'] ) && trim( (string) $entry['en'] ) !== '' ) {
			$out[ $hash ] = $entry['en'];
		}
	}
	return $out;
}

// ---------------------------------------------------------------------------
// --export
// ---------------------------------------------------------------------------

function mdt_en_export() {
	global $MDT_EN_DICT, $MDT_EN_SLUGS;

	$dict    = mdt_en_load_dict();
	$entries = $dict['entries'];
	$file_of = $dict['file_of'];

	$sources = array();   // hash => source string
	$slugs   = mdt_en_read_json( $MDT_EN_SLUGS );
	$seen    = array();

	foreach ( mdt_en_source_posts() as $post ) {
		foreach ( mdt_en_source_strings( $post['id'] ) as $value ) {
			$sources[ mdt_en_hash( $value ) ] = $value;
		}

		$key = $post['type'] . ':' . mdt_en_slug_path( $post['id'] );
		$seen[ $key ] = true;
		if ( ! array_key_exists( $key, $slugs ) ) {
			$slugs[ $key ] = '';
		}
	}

	// Grow the dictionary; never touch an English value that is already filled in.
	$added = 0;
	$auto  = 0;
	foreach ( $sources as $hash => $value ) {
		if ( isset( $entries[ $hash ] ) ) {
			$entries[ $hash ]['uk'] = $value;
			continue;
		}

		$entry = array( 'uk' => $value, 'en' => '' );
		if ( ! mdt_en_needs_translating( $value ) ) {
			// No Cyrillic at all: a brand or collection name that is already English.
			$entry['en'] = $value;
			$auto++;
		}
		$entries[ $hash ] = $entry;
		$added++;
	}

	// Drop entries whose source string no longer exists anywhere (renamed or deleted content).
	$stale = 0;
	foreach ( array_keys( $entries ) as $hash ) {
		if ( ! isset( $sources[ $hash ] ) ) {
			unset( $entries[ $hash ], $file_of[ $hash ] );
			$stale++;
		}
	}

	// Place new entries into chunks: keep existing assignments, and group the newcomers by length
	// so a chunk is either lots of short strings or a few long ones rather than a mix.
	$buckets = array();
	foreach ( $entries as $hash => $entry ) {
		if ( isset( $file_of[ $hash ] ) ) {
			$buckets[ $file_of[ $hash ] ][ $hash ] = $entry;
		}
	}

	$newcomers = array();
	foreach ( $entries as $hash => $entry ) {
		if ( ! isset( $file_of[ $hash ] ) ) {
			$newcomers[ $hash ] = $entry;
		}
	}
	uasort( $newcomers, function ( $a, $b ) {
		return mb_strlen( $a['uk'] ) - mb_strlen( $b['uk'] );
	} );

	$next  = count( $buckets ) + 1;
	$chunk = array();
	$chars = 0;
	foreach ( $newcomers as $hash => $entry ) {
		$chunk[ $hash ] = $entry;
		$chars         += mb_strlen( $entry['uk'] );
		if ( $chars >= MDT_EN_CHUNK_CHARS ) {
			$buckets[ sprintf( '%03d.json', $next++ ) ] = $chunk;
			$chunk = array();
			$chars = 0;
		}
	}
	if ( $chunk ) {
		$buckets[ sprintf( '%03d.json', $next++ ) ] = $chunk;
	}

	ksort( $buckets );
	foreach ( $buckets as $name => $bucket ) {
		mdt_en_write_json( $MDT_EN_DICT . '/' . $name, $bucket );
	}

	ksort( $slugs );
	mdt_en_write_json( $MDT_EN_SLUGS, $slugs );

	mdt_act( 'export', 'dict', sprintf(
		'%d string(s) in %d file(s); %d new (%d auto-filled Latin), %d stale removed',
		count( $entries ), count( $buckets ), $added, $auto, $stale
	) );
	mdt_act( 'export', 'slugs.json', sprintf( '%d entr(ies)', count( $slugs ) ) );
}

// ---------------------------------------------------------------------------
// --stats
// ---------------------------------------------------------------------------

function mdt_en_stats() {
	$entries = mdt_en_load_dict()['entries'];

	$done = 0;
	$todo = 0;
	$todo_chars = 0;
	$per_file = array();

	foreach ( mdt_en_dict_files() as $file ) {
		$name  = basename( $file );
		$open  = 0;
		$chars = 0;
		foreach ( mdt_en_read_json( $file ) as $entry ) {
			if ( isset( $entry['en'] ) && trim( (string) $entry['en'] ) !== '' ) {
				continue;
			}
			$open++;
			$chars += mb_strlen( $entry['uk'] );
		}
		if ( $open ) {
			$per_file[ $name ] = array( $open, $chars );
		}
	}

	foreach ( $entries as $entry ) {
		if ( isset( $entry['en'] ) && trim( (string) $entry['en'] ) !== '' ) {
			$done++;
		} else {
			$todo++;
			$todo_chars += mb_strlen( $entry['uk'] );
		}
	}

	printf( "\ndictionary: %d string(s) — %d translated, %d outstanding (%d chars)\n",
		count( $entries ), $done, $todo, $todo_chars );

	if ( $per_file ) {
		echo "\nfiles with outstanding strings:\n";
		foreach ( $per_file as $name => $info ) {
			printf( "  %-12s %4d string(s), %6d chars\n", $name, $info[0], $info[1] );
		}
	}
	echo "\n";
}

// ---------------------------------------------------------------------------
// --apply
// ---------------------------------------------------------------------------

/**
 * Media has to be duplicated per language, even though every language shows the same files.
 *
 * It looks like pure bloat — roughly 5,200 extra attachment posts, and an attachment id already
 * resolves to the same file whatever the post's language. It isn't: ACF's image, file and gallery
 * fields resolve their ids through acf_get_posts(), which is a WP_Query, and WPML scopes WP_Query
 * by language because `attachment` is a translatable type here. An English post pointing at
 * Ukrainian attachment ids therefore gets an empty result and ACF's format_value() returns false —
 * the homepage hero slider renders zero slides. This is also exactly why the Russian side has
 * 5,322 attachment translations of its own, with Russian ids stored in its gallery fields.
 *
 * Featured images are the exception and would have been fine either way: get_the_post_thumbnail()
 * does not go through WP_Query.
 *
 * So WPML Media Translation is left switched on for this run. The alternative — marking
 * `attachment` non-translatable so WPML stops language-filtering it — would change how media
 * behaves in every language, including the Russian pages that already depend on the current
 * arrangement.
 */
function mdt_en_media_fields() {
	static $names = null;
	if ( $names !== null ) {
		return $names;
	}

	global $wpdb;
	$mediaish = array( 'image', 'file', 'gallery' );
	$names    = array();

	$fields = $wpdb->get_results(
		"SELECT post_excerpt AS name, post_content FROM {$wpdb->posts} WHERE post_type = 'acf-field'"
	);
	foreach ( $fields as $field ) {
		$settings = maybe_unserialize( $field->post_content );
		if ( is_array( $settings ) && isset( $settings['type'] ) && in_array( $settings['type'], $mediaish, true ) ) {
			$names[ $field->name ] = true;
		}
	}

	return $names;
}

/** Attachment ids one post points at: its featured image plus every ACF image/file/gallery value. */
function mdt_en_referenced_attachments( $post_id ) {
	global $wpdb;

	$fields = mdt_en_media_fields();
	$ids    = array();

	$rows = $wpdb->get_results( $wpdb->prepare(
		"SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $post_id
	) );

	foreach ( $rows as $row ) {
		if ( $row->meta_key === '_thumbnail_id' ) {
			if ( (int) $row->meta_value ) {
				$ids[ (int) $row->meta_value ] = true;
			}
			continue;
		}
		if ( $row->meta_key === '' || $row->meta_key[0] === '_' ) {
			continue;
		}
		if ( ! isset( $fields[ mdt_en_field_name( $row->meta_key ) ] ) ) {
			continue;
		}

		// image/file hold a single id, gallery a serialized array of them.
		foreach ( (array) maybe_unserialize( $row->meta_value ) as $value ) {
			if ( is_numeric( $value ) && (int) $value ) {
				$ids[ (int) $value ] = true;
			}
		}
	}

	return array_keys( $ids );
}

/**
 * Give one attachment an English twin.
 *
 * Why attachments need translating at all, when every language shows the same file: ACF's image,
 * file and gallery fields resolve their ids through acf_get_posts(), which is a WP_Query, and WPML
 * scopes WP_Query by language because `attachment` is a translatable type here. An English post
 * pointing at Ukrainian attachment ids gets an empty result set, ACF's format_value() returns
 * false, and the block renders as if it had no images — the homepage hero slider comes out with
 * zero slides. The Russian side has 5,322 attachment translations for exactly this reason. The
 * stored ids are left as the Ukrainian ones; ACFML converts them on read.
 *
 * Built by hand rather than with $sitepress->make_duplicate(), which is wrong for attachments in
 * two ways: it copies custom fields through WPML's per-field settings, which do not include
 * _wp_attached_file, so the copy has no file at all; and the wp_insert_post() inside it trips WPML
 * Media's own save_post duplication, which then duplicates the new English attachment back into
 * Ukrainian and Russian — three new posts per attachment instead of one, and ~12,000 junk rows
 * across the library.
 *
 * @return bool whether a twin was (or would be) created.
 */
function mdt_en_translate_attachment( $attachment_id, $apply ) {
	global $wpdb, $sitepress;

	if ( icl_object_id( $attachment_id, 'attachment', false, MDT_EN_LANG ) ) {
		return false;
	}

	$master = get_post( $attachment_id );
	if ( ! $master || $master->post_type !== 'attachment' ) {
		return false;
	}

	if ( ! $apply ) {
		return true;
	}

	$trid = $sitepress->get_element_trid( $attachment_id, 'post_attachment' );

	// WPML Media hooks save_post to fan new attachments out across every language; that is what
	// this function exists to avoid, so it is silenced for the insert.
	remove_all_actions( 'save_post_attachment' );
	remove_all_actions( 'add_attachment' );
	remove_all_actions( 'edit_attachment' );

	$en_id = wp_insert_post( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'post_title'     => $master->post_title,
		'post_content'   => $master->post_content,
		'post_excerpt'   => $master->post_excerpt,
		'post_mime_type' => $master->post_mime_type,
		'post_author'    => $master->post_author,
		'post_date'      => $master->post_date,
		'post_date_gmt'  => $master->post_date_gmt,
		'post_parent'    => 0,
		'guid'           => $master->guid,
	), true );

	if ( is_wp_error( $en_id ) ) {
		mdt_note( "attachment #$attachment_id: " . $en_id->get_error_message() );
		return false;
	}

	// The file itself, its generated sizes and its alt text. Nothing is copied to disk — both
	// languages point at the same _wp_attached_file.
	foreach ( array( '_wp_attached_file', '_wp_attachment_metadata', '_wp_attachment_image_alt' ) as $meta_key ) {
		$value = get_post_meta( $attachment_id, $meta_key, true );
		if ( $value !== '' && $value !== false ) {
			update_post_meta( $en_id, $meta_key, wp_slash( $value ) );
		}
	}

	do_action( 'wpml_set_element_language_details', array(
		'element_id'           => $en_id,
		'element_type'         => 'post_attachment',
		'trid'                 => $trid,
		'language_code'        => MDT_EN_LANG,
		'source_language_code' => MDT_EN_SRC_LANG,
	) );

	if ( get_post_meta( $en_id, '_wp_attached_file', true ) === '' ) {
		mdt_fatal( "attachment #$en_id came out with no file (master #$attachment_id)" );
	}

	return true;
}

function mdt_en_meta_denylist() {
	return array(
		'_edit_last', '_edit_lock', '_last_translation_edit_mode', 'ao_post_optimize',
		'rank_math_internal_links_processed', 'rank_math_seo_score',
		'rank_math_advanced_robots', 'rank_math_permalink',

		// catalog-migration.php resolves the categories it seeded by this meta with a LIMIT 1.
		// Copied onto the English twin it becomes a second row with the same value, and the
		// cutover re-run could then reparent or rename the English post instead of the Ukrainian
		// one. The EN posts are found through WPML's trid, so they never need it.
		'_mdt_migration_ref',
	);
}

function mdt_en_meta_is_copyable( $meta_key ) {
	if ( strpos( $meta_key, '_icl' ) === 0 || strpos( $meta_key, '_wpml' ) === 0 ) {
		return false;
	}
	return ! in_array( $meta_key, mdt_en_meta_denylist(), true );
}

/**
 * Copy across the custom fields WPML's duplication leaves behind.
 *
 * WPML only copies fields configured "Copy" (or "Copy once"). Fields set to "Translate" are meant
 * to be filled in by a human in the translation editor, and fields set to "Don't translate" are
 * skipped outright — and on this site that second group includes `_thumbnail_id`, so every English
 * post would come out with no featured image. The Russian pages have these values because a
 * translator typed them in; the point of this script is not to need one, so the Ukrainian values
 * are copied in and then translated in place.
 *
 * Only keys that are entirely absent on the English post are touched, so a re-run never overwrites
 * a translated value or an edit made in wp-admin.
 *
 * @return string[] the keys that were (or, on a dry run, would be) copied.
 */
function mdt_en_backfill_meta( $uk_id, $en_id, $apply ) {
	global $wpdb;

	$uk_keys = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE post_id = %d", $uk_id
	) );
	$en_keys = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE post_id = %d", $en_id
	) );
	$en_keys = array_flip( $en_keys );

	$copied = array();
	foreach ( $uk_keys as $meta_key ) {
		if ( isset( $en_keys[ $meta_key ] ) || ! mdt_en_meta_is_copyable( $meta_key ) ) {
			continue;
		}

		$copied[] = $meta_key;
		if ( ! $apply ) {
			continue;
		}

		// get_post_meta() returns every row for the key and unserializes each; add_post_meta()
		// re-serializes, so arrays round-trip intact. wp_slash() because add_post_meta() unslashes.
		foreach ( get_post_meta( $uk_id, $meta_key ) as $value ) {
			add_post_meta( $en_id, $meta_key, wp_slash( $value ) );
		}
	}

	return $copied;
}

function mdt_en_twin( $uk_id ) {
	$type = get_post_field( 'post_type', $uk_id );
	$id   = icl_object_id( (int) $uk_id, $type, false, MDT_EN_LANG );
	return $id ? (int) $id : 0;
}

/** Force a slug past wp_unique_post_slug() and cyr3lat, which both rewrite it behind your back. */
function mdt_en_force_slug( $id, $slug ) {
	global $wpdb;

	if ( get_post_field( 'post_name', $id ) === $slug ) {
		return;
	}
	$wpdb->update( $wpdb->posts, array( 'post_name' => $slug ), array( 'ID' => $id ) );
	clean_post_cache( $id );
}

/** An EN slug not already taken by a different EN sibling of the same type. */
function mdt_en_unique_slug( $base, $post_id, $post_type, $post_parent ) {
	global $wpdb;

	$slug = $base;
	for ( $i = 2; $i <= 20; $i++ ) {
		$taken = $wpdb->get_var( $wpdb->prepare(
			"SELECT p.ID FROM {$wpdb->posts} p
			 JOIN {$wpdb->prefix}icl_translations t
			   ON t.element_id = p.ID AND t.element_type = CONCAT('post_', p.post_type)
			 WHERE p.post_name = %s AND p.post_type = %s AND p.post_parent = %d
			   AND p.ID <> %d AND p.post_status <> 'trash' AND t.language_code = %s
			 LIMIT 1",
			$slug, $post_type, (int) $post_parent, (int) $post_id, MDT_EN_LANG
		) );

		if ( ! $taken ) {
			return $slug;
		}
		$slug = $base . '-' . $i;
	}

	return $slug;
}

function mdt_en_apply( $apply, $verbose ) {
	global $sitepress, $MDT_EN_SLUGS, $wpdb, $ONLY, $MDT_EN_POST_TYPES;

	if ( $ONLY ) {
		$unknown = array_diff( $ONLY, $MDT_EN_POST_TYPES );
		if ( $unknown ) {
			mdt_fatal( '--only: unknown post type(s) ' . implode( ', ', $unknown ) );
		}
		mdt_note( 'limited to: ' . implode( ', ', $ONLY ) );
	}

	$dict  = mdt_en_translations();
	$slugs = mdt_en_read_json( $MDT_EN_SLUGS );

	if ( ! $dict ) {
		mdt_note( 'The dictionary has no translations yet — run --export, fill in tools/i18n-en/dict/, then come back.' );
	}


	$counts = array(
		'duplicate' => 0, 'attachment' => 0, 'meta-backfill' => 0, 'text' => 0,
		'slug' => 0, 'independent' => 0, 'slug-deferred' => 0, 'meta-mismatch' => 0,
	);

	foreach ( mdt_en_source_posts() as $post ) {
		$uk_id  = $post['id'];
		$type   = $post['type'];

		if ( $ONLY && ! in_array( $type, $ONLY, true ) ) {
			continue;
		}
		$en_id  = mdt_en_twin( $uk_id );
		$label  = $type . ' #' . $uk_id;

		// 1. The EN post itself. WPML's duplication is what copies the ACF meta, the featured
		//    image, the page template and the menu_order, and links the trid.
		if ( ! $en_id ) {
			$counts['duplicate']++;
			if ( $verbose ) {
				mdt_act( 'duplicate', $label, get_post_field( 'post_title', $uk_id ) );
			}
			if ( $apply ) {
				$en_id = $sitepress->make_duplicate( $uk_id, MDT_EN_LANG );
				if ( ! $en_id || is_wp_error( $en_id ) ) {
					mdt_fatal( "duplication failed for $label" );
				}
			}
		}

		if ( ! $en_id ) {
			continue; // dry run, nothing downstream to inspect yet
		}

		// 2. The attachments this post points at need English twins of their own.
		foreach ( mdt_en_referenced_attachments( $uk_id ) as $attachment_id ) {
			if ( mdt_en_translate_attachment( $attachment_id, $apply ) ) {
				$counts['attachment']++;
			}

			// Gallery captions print the attachment title (single-product.php, p__g), so a
			// title with a dictionary entry gets its English text. Most are Latin file names
			// and have none. Written directly for the same reason as the post text below.
			$en_attachment = icl_object_id( $attachment_id, 'attachment', false, MDT_EN_LANG );
			$uk_title      = trim( (string) get_post_field( 'post_title', $attachment_id ) );
			$hash          = mdt_en_hash( $uk_title );
			if ( $en_attachment && $uk_title !== '' && isset( $dict[ $hash ] )
				&& get_post_field( 'post_title', $en_attachment ) !== $dict[ $hash ] ) {
				$counts['text']++;
				if ( $apply ) {
					$wpdb->update( $wpdb->posts, array( 'post_title' => $dict[ $hash ] ), array( 'ID' => $en_attachment ) );
					clean_post_cache( $en_attachment );
				}
			}
		}

		// 3. Fields WPML's duplication skipped (see mdt_en_backfill_meta).
		$copied = mdt_en_backfill_meta( $uk_id, $en_id, $apply );
		if ( $copied ) {
			$counts['meta-backfill']++;
			if ( $verbose ) {
				mdt_act( 'meta', $label . ' -> #' . $en_id, sprintf(
					'%d field(s): %s', count( $copied ), implode( ', ', array_slice( $copied, 0, 5 ) )
				) );
			}
		}

		// 4. English text over the copied Ukrainian values.
		$uk_post = get_post( $uk_id );
		$changes = array();

		foreach ( array( 'post_title', 'post_content', 'post_excerpt' ) as $column ) {
			$source = trim( (string) $uk_post->$column );
			if ( $source === '' ) {
				continue;
			}
			$hash = mdt_en_hash( $source );
			if ( ! isset( $dict[ $hash ] ) ) {
				continue;
			}
			if ( ! mdt_en_same_text( get_post_field( $column, $en_id ), $dict[ $hash ] ) ) {
				$changes[ $column ] = $dict[ $hash ];
			}
		}

		$meta_changes = array();
		foreach ( mdt_en_source_meta( $uk_id ) as $meta_key => $source ) {
			$hash = mdt_en_hash( $source );
			if ( ! isset( $dict[ $hash ] ) ) {
				continue;
			}
			if ( ! mdt_en_same_text( get_post_meta( $en_id, $meta_key, true ), $dict[ $hash ] ) ) {
				$meta_changes[ $meta_key ] = $dict[ $hash ];
			}
		}

		if ( $changes || $meta_changes ) {
			$counts['text']++;
			if ( $verbose ) {
				mdt_act( 'text', $label . ' -> #' . $en_id, sprintf(
					'%d column(s), %d meta field(s)', count( $changes ), count( $meta_changes )
				) );
			}
			if ( $apply ) {
				if ( $changes ) {
					$slug = get_post_field( 'post_name', $en_id );
					// wp_update_post() for its hooks and cache handling, then the dictionary text
					// written verbatim: WPML Media Translation's save_post hook rewrites <img> URLs
					// to this install's home URL, which locally is http://localhost:8080 and would
					// ship in the exported database.
					wp_update_post( wp_slash( $changes + array( 'ID' => $en_id ) ) );
					$wpdb->update( $wpdb->posts, $changes, array( 'ID' => $en_id ) );
					clean_post_cache( $en_id );
					mdt_en_force_slug( $en_id, $slug );
				}
				foreach ( $meta_changes as $meta_key => $value ) {
					// update_post_meta() unslashes, which would turn a literal "\n" into "n".
					update_post_meta( $en_id, $meta_key, wp_slash( $value ) );
				}
			}
		}

		// 5. English slug. Deferred while the title is still Ukrainian, so a re-run can set it
		//    once the translation lands rather than baking a transliterated slug into the URL.
		$path     = $type . ':' . mdt_en_slug_path( $uk_id );
		$wanted   = isset( $slugs[ $path ] ) ? trim( (string) $slugs[ $path ] ) : '';
		$en_title = get_post_field( 'post_title', $en_id );

		if ( $wanted === '' && $en_title !== '' && ! mdt_en_needs_translating( $en_title ) ) {
			$wanted = sanitize_title( $en_title );
		}

		if ( $wanted === '' ) {
			$counts['slug-deferred']++;
		} else {
			$wanted  = mdt_en_unique_slug( $wanted, $en_id, $type, get_post_field( 'post_parent', $en_id ) );
			$current = get_post_field( 'post_name', $en_id );
			if ( $current !== $wanted ) {
				$counts['slug']++;
				if ( $verbose ) {
					mdt_act( 'slug', $label . ' -> #' . $en_id, "$current -> $wanted" );
				}
				if ( $apply ) {
					mdt_en_force_slug( $en_id, $wanted );
					if ( get_post_field( 'post_name', $en_id ) !== $wanted ) {
						mdt_fatal( "slug did not stick on #$en_id (wanted $wanted)" );
					}
				}
			}
		}

		// 6. Cut the duplicate link, last — while it is set, WPML re-syncs the EN post from the
		//    Ukrainian one on every uk save, which would silently revert hand edits in wp-admin.
		//    Only once the post actually holds English text: a duplicate that is still Ukrainian
		//    is better left tracking its master.
		$is_duplicate  = get_post_meta( $en_id, '_icl_lang_duplicate_of', true );
		$title_is_en   = $en_title !== '' && ! mdt_en_needs_translating( $en_title );

		if ( $is_duplicate && $title_is_en ) {
			$counts['independent']++;
			if ( $verbose ) {
				mdt_act( 'independent', $label . ' -> #' . $en_id, 'drop _icl_lang_duplicate_of' );
			}
			if ( $apply ) {
				delete_post_meta( $en_id, '_icl_lang_duplicate_of' );
			}
		}

		// 7. Sanity check, against the *Russian* twin rather than the Ukrainian original.
		//
		//    WPML will not copy a field that is configured "Don't translate" — 65 of this site's
		//    782 configured custom fields are, plus each plugin's own per-post caches (Rank Math
		//    scores, Autoptimize) — so the EN post legitimately has fewer fields than the uk one.
		//    The Russian page is the reference for what a *working translation* carries here: if EN
		//    has everything ru has, it is structurally sound. Falls back to the uk comparison for
		//    the handful of posts with no ru twin.
		if ( $en_id ) {
			$reference = icl_object_id( $uk_id, $type, false, 'ru' );
			$reference = $reference ? (int) $reference : $uk_id;

			$missing = $wpdb->get_col( $wpdb->prepare(
				"SELECT DISTINCT r.meta_key FROM {$wpdb->postmeta} r
				 WHERE r.post_id = %d
				   AND r.meta_key NOT LIKE '\_icl%%' AND r.meta_key NOT LIKE '\_edit%%'
				   AND r.meta_key NOT LIKE '\_wpml%%' AND r.meta_key NOT LIKE 'ao\_post%%'
				   AND r.meta_key NOT IN (
				       'rank_math_internal_links_processed', 'rank_math_seo_score',
				       'rank_math_advanced_robots', 'rank_math_permalink',
				       '_last_translation_edit_mode', '_top_nav_excluded', '_cms_nav_minihome'
				   )
				   AND r.meta_key NOT IN (
				       SELECT e.meta_key FROM {$wpdb->postmeta} e WHERE e.post_id = %d
				   )",
				$reference, $en_id
			) );

			if ( $missing ) {
				$counts['meta-mismatch']++;
				mdt_note( sprintf(
					'%s -> #%d is missing %d field(s) that #%d has: %s',
					$label, $en_id, count( $missing ), $reference,
					implode( ', ', array_slice( $missing, 0, 6 ) )
				) );
			}
		}
	}

	$informational = array( 'slug-deferred', 'meta-mismatch' );

	foreach ( $counts as $what => $n ) {
		if ( ! $n ) {
			continue;
		}
		if ( in_array( $what, $informational, true ) ) {
			mdt_note( "$what: $n post(s)" );
		} else {
			mdt_act( 'summary', $what, $n . ' post(s)' );
		}
	}
}

// ---------------------------------------------------------------------------
// Run
// ---------------------------------------------------------------------------

if ( $STATS ) {
	mdt_en_stats();
	exit( 0 );
}

if ( $EXPORT ) {
	mdt_en_export();
} else {
	mdt_en_apply( $APPLY, $VERBOSE );
}

// ---------------------------------------------------------------------------
// Report
// ---------------------------------------------------------------------------

echo "\n";
printf( "%-13s | %-34s | %s\n", 'ACTION', 'TARGET', 'DETAIL' );
echo str_repeat( '-', 110 ) . "\n";
foreach ( $ACTIONS as $a ) {
	printf( "%-13s | %-34s | %s\n", $a[0], $a[1], $a[2] );
}
echo str_repeat( '-', 110 ) . "\n";
printf( "%d action(s).", count( $ACTIONS ) );

if ( $EXPORT ) {
	echo " Bundles written.\n";
} else {
	echo $APPLY ? " APPLIED.\n" : " Dry run — nothing written. Re-run with --apply.\n";
}

foreach ( $NOTES as $note ) {
	echo "  note: $note\n";
}
echo "\n";
