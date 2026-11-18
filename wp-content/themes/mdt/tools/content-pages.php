<?php
/**
 * Write page content from the content team's specs into every language version.
 *
 * LOCAL DEV TOOL — never deployed to or executed on production. Like catalog-migration.php and the
 * en-* tools, it runs locally against a fresh production dump at cutover, and the database is
 * shipped.
 *
 * Content lives in tools/content/*.json, one file per spec. Each file lists target posts and the
 * values to write into the uk, ru and en versions of each:
 *
 *   {
 *     "posts": [ {
 *       "target":    "category:steli/kasetna-stelya",     // <post type>:<uk slug path>
 *       "acf":       { "<field name>": <value>, ... },    // ACF fields, incl. repeaters
 *       "meta":      { "rank_math_title": <value> },       // plain post meta
 *       "thumbnail": 10104,                                // uk attachment id
 *       "thumbnail_alt": <value>
 *     } ]
 *   }
 *
 * Posts that don't exist yet are created in all three languages (trid-linked) when the entry has
 * a "create" block; "target" then defaults to "<type>:<parent path>/<uk slug>":
 *
 *       "create": { "type": "product", "slug": "profim-xenon-10",            // uk + ru; en from title
 *                   "title": { "uk": ..., "ru": ..., "en": ... }, "parent": "@category:..." }
 *
 * Images live in tools/content/media/ and are declared once per file under "media"; the tool copies
 * them to wp-content/uploads/mdt-content/ (copy that folder to production at cutover), creates the
 * attachment with all theme sizes, plus its ru/en twins, and sets alt text per language:
 *
 *     "media": { "<key>": { "file": "mebli-hero.jpg", "alt": { "uk": ..., "ru": ..., "en": ... },
 *                           "source": "https://... (where it came from, for the record)" } }
 *
 * "@media:<key>" anywhere in a value (or as "thumbnail") is that image's Ukrainian attachment id.
 * "defaults" maps a post type to "acf" values applied to every post of that type the file creates
 * (e.g. the standard delivery/payment text on products), overridden by the entry's own "acf".
 *
 * A <value> is either the same for every language (a string, number or list) or an object with
 * "uk", "ru" and "en" keys. "@<post type>:<uk slug path>" and "@<id>" inside a value are post
 * references, stored as the Ukrainian id in every language (the theme resolves them on read, see
 * mdt_translate_related_posts()). Repeaters are lists of rows keyed by sub field name.
 *
 * Targets are addressed by slug path rather than id, so a file still applies after the catalogue
 * migration recreates categories with new ids. Plain numeric ids are only used for things that
 * already exist on production (products, projects, suppliers, attachments).
 *
 * Idempotent: a second --apply reports zero actions. It also registers a WPML translation mode for
 * every field key it writes (text "translate", everything else "copy"), so saving the Ukrainian
 * post in wp-admin never copies its text over the other languages.
 *
 * Run after catalog-migration.php, en-setup.php and en-translate.php (it needs the EN posts):
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/content-pages.php            # dry run
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/content-pages.php --apply
 */

if ( php_sapi_name() !== 'cli' ) {
	die( 'CLI only.' );
}

require dirname( __FILE__, 5 ) . '/wp-load.php';

$APPLY = in_array( '--apply', $argv, true );
$LANGS = array( 'uk', 'ru', 'en' );

$ACTIONS = array();

function mdt_act( $action, $target, $detail ) {
	global $ACTIONS;
	$ACTIONS[] = array( $action, $target, $detail );
}

function mdt_fatal( $msg ) {
	fwrite( STDERR, "FATAL: $msg\n" );
	exit( 1 );
}

const MDT_CP_UPLOAD_DIR = 'mdt-content';

/** Ukrainian attachment id of a declared image, or 0 if it doesn't exist yet. */
function mdt_cp_media_id( $key ) {
	global $wpdb, $MEDIA;
	if ( ! isset( $MEDIA[ $key ] ) ) {
		mdt_fatal( "undeclared media key: $key" );
	}
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT m.post_id FROM {$wpdb->postmeta} m
		 JOIN {$wpdb->prefix}icl_translations t ON t.element_id = m.post_id AND t.element_type = 'post_attachment' AND t.language_code = 'uk'
		 WHERE m.meta_key = '_wp_attached_file' AND m.meta_value = %s LIMIT 1",
		MDT_CP_UPLOAD_DIR . '/' . $MEDIA[ $key ]['file']
	) );
}

/**
 * Ukrainian post id for "<post type>:<slug>/<slug>/...", walking post_parent, or "media:<key>".
 * A post this run is going to create (dry run) resolves to 0 instead of failing.
 */
function mdt_cp_resolve( $ref ) {
	global $wpdb, $PENDING;

	if ( ctype_digit( (string) $ref ) ) {
		return (int) $ref;
	}
	if ( strpos( $ref, 'media:' ) === 0 ) {
		return mdt_cp_media_id( substr( $ref, 6 ) );
	}
	if ( ! preg_match( '/^([a-z_]+):(.+)$/', $ref, $m ) ) {
		mdt_fatal( "bad post reference: $ref" );
	}
	list( , $type, $path ) = $m;

	$parent = 0;
	foreach ( explode( '/', trim( $path, '/' ) ) as $slug ) {
		$parent = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT p.ID FROM {$wpdb->posts} p
			 JOIN {$wpdb->prefix}icl_translations t ON t.element_id = p.ID AND t.element_type = %s AND t.language_code = 'uk'
			 WHERE p.post_type = %s AND p.post_name = %s AND p.post_parent = %d AND p.post_status = 'publish'",
			'post_' . $type, $type, $slug, $parent
		) );
		if ( ! $parent ) {
			if ( isset( $PENDING[ $ref ] ) ) {
				return 0;
			}
			mdt_fatal( "cannot resolve $ref (stuck at '$slug')" );
		}
	}
	return $parent;
}

/** A value for one language: picks the language from {uk,ru,en} objects and resolves "@refs". */
function mdt_cp_value( $value, $lang ) {
	if ( is_array( $value ) && isset( $value['uk'] ) && array_key_exists( $lang, $value ) && count( array_diff( array_keys( $value ), array( 'uk', 'ru', 'en' ) ) ) === 0 ) {
		$value = $value[ $lang ];
	}
	if ( is_array( $value ) ) {
		$out = array();
		foreach ( $value as $k => $v ) {
			$out[ $k ] = mdt_cp_value( $v, $lang );
		}
		return $out;
	}
	if ( is_string( $value ) && strlen( $value ) > 1 && $value[0] === '@' ) {
		return (string) mdt_cp_resolve( substr( $value, 1 ) );
	}
	if ( is_string( $value ) && strpos( $value, '\\' ) !== false ) {
		// update_metadata() unslashes; nothing in the specs needs a backslash, so refuse rather
		// than silently store something different from the file.
		mdt_fatal( 'backslash in content value: ' . substr( $value, 0, 60 ) );
	}
	return $value;
}

/** The ACF field definition that applies to $name on $post_id. */
function mdt_cp_field( $name, $post_id ) {
	foreach ( acf_get_field_groups( array( 'post_id' => $post_id ) ) as $group ) {
		foreach ( acf_get_fields( $group ) as $field ) {
			if ( $field['name'] === $name ) {
				return $field;
			}
		}
	}
	mdt_fatal( "no ACF field '$name' applies to post #$post_id" );
}

/**
 * Field value in ACF's raw shape (repeater rows keyed by sub field key), so it can be compared
 * with get_field( ..., false ) and passed straight to update_field().
 */
function mdt_cp_raw( $field, $value ) {
	if ( $field['type'] !== 'repeater' ) {
		return $value;
	}
	$keys = array();
	foreach ( $field['sub_fields'] as $sub ) {
		$keys[ $sub['name'] ] = $sub;
	}
	$rows = array();
	foreach ( (array) $value as $row ) {
		$raw = array();
		foreach ( $row as $name => $v ) {
			if ( ! isset( $keys[ $name ] ) ) {
				mdt_fatal( "repeater {$field['name']} has no sub field '$name'" );
			}
			$raw[ $keys[ $name ]['key'] ] = mdt_cp_raw( $keys[ $name ], $v );
		}
		$rows[] = $raw;
	}
	return $rows;
}

/** Comparable form: empty values dropped, scalars as strings, keys sorted. */
function mdt_cp_norm( $value ) {
	if ( is_array( $value ) ) {
		$out = array();
		foreach ( $value as $k => $v ) {
			$n = mdt_cp_norm( $v );
			if ( $n !== '' && $n !== array() ) {
				$out[ $k ] = $n;
			}
		}
		if ( array_keys( $out ) !== range( 0, count( $out ) - 1 ) ) {
			ksort( $out );
		}
		return $out;
	}
	return $value === null || $value === false ? '' : trim( (string) $value );
}

/** Every meta key a field value is stored under, with whether it holds free text. */
function mdt_cp_meta_keys( $field, $value, $prefix = '' ) {
	$name = $prefix . $field['name'];
	$keys = array( $name => in_array( $field['type'], array( 'text', 'textarea', 'wysiwyg' ), true ) );
	if ( $field['type'] === 'repeater' ) {
		foreach ( array_values( (array) $value ) as $i => $row ) {
			foreach ( $field['sub_fields'] as $sub ) {
				$keys += mdt_cp_meta_keys( $sub, isset( $row[ $sub['key'] ] ) ? $row[ $sub['key'] ] : null, "{$name}_{$i}_" );
			}
		}
	}
	return $keys;
}

/** Value for one language of a {uk,ru,en} object, or the value itself. */
function mdt_cp_lang( $value, $lang ) {
	return ( is_array( $value ) && isset( $value[ $lang ] ) ) ? $value[ $lang ] : $value;
}

function mdt_cp_set_language( $id, $element_type, $lang, $trid ) {
	do_action( 'wpml_set_element_language_details', array(
		'element_id'           => $id,
		'element_type'         => $element_type,
		'trid'                 => $trid,
		'language_code'        => $lang,
		'source_language_code' => $lang === 'uk' ? null : 'uk',
	) );
}

function mdt_cp_force_slug( $id, $slug ) {
	global $wpdb;
	if ( get_post_field( 'post_name', $id ) !== $slug ) {
		$wpdb->update( $wpdb->posts, array( 'post_name' => $slug ), array( 'ID' => $id ) );
		clean_post_cache( $id );
	}
}

/** Copy one declared image into uploads and make sure it has a uk attachment and ru/en twins. */
function mdt_cp_media( $key, $apply ) {
	global $MEDIA, $LANGS, $sitepress;
	$m    = $MEDIA[ $key ];
	$src  = dirname( __FILE__ ) . '/content/media/' . $m['file'];
	$rel  = MDT_CP_UPLOAD_DIR . '/' . $m['file'];
	$dest = wp_get_upload_dir()['basedir'] . '/' . $rel;

	if ( ! is_file( $src ) ) {
		mdt_fatal( "media file missing: tools/content/media/{$m['file']}" );
	}
	if ( ! is_file( $dest ) || md5_file( $dest ) !== md5_file( $src ) ) {
		mdt_act( 'file', $rel, 'copy into uploads' );
		if ( $apply ) {
			wp_mkdir_p( dirname( $dest ) );
			copy( $src, $dest );
		}
	}

	$uk = mdt_cp_media_id( $key );
	if ( ! $uk ) {
		mdt_act( 'media', $key, $m['file'] );
		if ( ! $apply ) {
			return;
		}
		require_once ABSPATH . 'wp-admin/includes/image.php';
		do_action( 'wpml_switch_language', 'uk' );
		$uk = wp_insert_attachment( array(
			'post_mime_type' => wp_check_filetype( $dest )['type'] ?: ( substr( $dest, -4 ) === '.svg' ? 'image/svg+xml' : 'application/octet-stream' ),
			'post_title'     => isset( $m['title'] ) ? $m['title'] : preg_replace( '/\.[^.]+$/', '', $m['file'] ),
			'post_status'    => 'inherit',
			'post_author'    => 1,
		), $dest );
		if ( ! $uk || is_wp_error( $uk ) ) {
			mdt_fatal( "attachment insert failed for {$m['file']}" );
		}
		update_post_meta( $uk, '_wp_attached_file', $rel );
		wp_update_attachment_metadata( $uk, wp_generate_attachment_metadata( $uk, $dest ) );
		mdt_cp_set_language( $uk, 'post_attachment', 'uk', null );
	}

	$trid = $sitepress->get_element_trid( $uk, 'post_attachment' );
	foreach ( $LANGS as $lang ) {
		$id = $lang === 'uk' ? $uk : (int) apply_filters( 'wpml_object_id', $uk, 'attachment', false, $lang );
		if ( ! $id ) {
			mdt_act( 'media', "$key [$lang]", 'translation twin' );
			if ( ! $apply ) {
				continue;
			}
			$master = get_post( $uk );
			$id     = wp_insert_post( array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_title'     => $master->post_title,
				'post_mime_type' => $master->post_mime_type,
				'post_author'    => 1,
				'guid'           => $master->guid,
			), true );
			if ( is_wp_error( $id ) ) {
				mdt_fatal( "attachment twin failed for {$m['file']} [$lang]" );
			}
			update_post_meta( $id, '_wp_attached_file', $rel );
			update_post_meta( $id, '_wp_attachment_metadata', get_post_meta( $uk, '_wp_attachment_metadata', true ) );
			mdt_cp_set_language( $id, 'post_attachment', $lang, $trid );
		}
		if ( isset( $m['alt'] ) ) {
			$alt = mdt_cp_lang( $m['alt'], $lang );
			if ( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) !== $alt ) {
				mdt_act( 'alt', "$key [$lang]", $alt );
				if ( $apply ) {
					update_post_meta( $id, '_wp_attachment_image_alt', wp_slash( $alt ) );
				}
			}
		}
	}
}

/** Create (or keep titles/slugs in sync for) a post declared with "create", in every language. */
function mdt_cp_create( $entry, $apply ) {
	global $LANGS, $sitepress;
	$c      = $entry['create'];
	$type   = $c['type'];
	$uk     = mdt_cp_resolve( $entry['target'] );
	$parent = isset( $c['parent'] ) ? mdt_cp_resolve( substr( $c['parent'], 1 ) ) : 0;
	$trid   = $uk ? $sitepress->get_element_trid( $uk, 'post_' . $type ) : null;

	foreach ( $LANGS as $lang ) {
		$title = mdt_cp_lang( $c['title'], $lang );
		$slug  = is_array( $c['slug'] ) ? mdt_cp_lang( $c['slug'], $lang ) : ( $lang === 'en' ? sanitize_title( $title ) : $c['slug'] );
		$id    = $lang === 'uk' ? $uk : ( $uk ? (int) apply_filters( 'wpml_object_id', $uk, $type, false, $lang ) : 0 );

		if ( $id ) {
			if ( get_post_field( 'post_title', $id ) !== $title ) {
				mdt_act( 'title', "{$entry['target']} [$lang] #$id", $title );
				if ( $apply ) {
					wp_update_post( array( 'ID' => $id, 'post_title' => wp_slash( $title ) ) );
					mdt_cp_force_slug( $id, $slug );
				}
			}
			if ( get_post_field( 'post_name', $id ) !== $slug ) {
				mdt_act( 'slug', "{$entry['target']} [$lang] #$id", $slug );
				if ( $apply ) {
					mdt_cp_force_slug( $id, $slug );
				}
			}
			continue;
		}

		mdt_act( 'create', "{$entry['target']} [$lang]", $title );
		if ( ! $apply ) {
			continue;
		}
		do_action( 'wpml_switch_language', $lang );
		$lang_parent = $parent ? (int) apply_filters( 'wpml_object_id', $parent, $type, false, $lang ) : 0;
		$id = wp_insert_post( array(
			'post_type'   => $type,
			'post_status' => 'publish',
			'post_title'  => wp_slash( $title ),
			'post_name'   => $slug,
			'post_parent' => $lang_parent,
			'menu_order'  => isset( $c['menu_order'] ) ? (int) $c['menu_order'] : 0,
			'post_author' => 1,
		), true );
		do_action( 'wpml_switch_language', 'uk' );
		if ( is_wp_error( $id ) ) {
			mdt_fatal( "insert failed for {$entry['target']} [$lang]: " . $id->get_error_message() );
		}
		mdt_cp_set_language( $id, 'post_' . $type, $lang, $trid );
		mdt_cp_force_slug( $id, $slug );
		if ( $lang === 'uk' ) {
			$uk   = (int) $id;
			$trid = $sitepress->get_element_trid( $uk, 'post_' . $type );
		}
	}
}

// ---------------------------------------------------------------------------
// Run
// ---------------------------------------------------------------------------

$files = glob( dirname( __FILE__ ) . '/content/*.json' );
if ( ! $files ) {
	mdt_fatal( 'no tools/content/*.json files' );
}

$field_modes = array();
$MEDIA       = array();
$PENDING     = array();
$SPECS       = array();

// Load every file first: media keys and created posts may be referenced across files.
foreach ( $files as $file ) {
	$spec = json_decode( file_get_contents( $file ), true );
	if ( ! is_array( $spec ) || empty( $spec['posts'] ) ) {
		mdt_fatal( basename( $file ) . ' is not valid JSON or has no "posts"' );
	}
	foreach ( isset( $spec['media'] ) ? $spec['media'] : array() as $key => $m ) {
		if ( isset( $MEDIA[ $key ] ) ) {
			mdt_fatal( "media key '$key' declared twice" );
		}
		$MEDIA[ $key ] = $m;
	}
	foreach ( $spec['posts'] as $i => $entry ) {
		if ( isset( $entry['create'] ) ) {
			$c = $entry['create'];
			if ( ! isset( $entry['target'] ) ) {
				$uk_slug = is_array( $c['slug'] ) ? $c['slug']['uk'] : $c['slug'];
				$path    = $c['type'] . ':' . $uk_slug;
				if ( isset( $c['parent'] ) ) {
					$path = $c['type'] . ':' . substr( $c['parent'], strpos( $c['parent'], ':' ) + 1 ) . '/' . $uk_slug;
				}
				$spec['posts'][ $i ]['target'] = $path;
			}
			$PENDING[ $spec['posts'][ $i ]['target'] ] = true;
			if ( isset( $spec['defaults'][ $c['type'] ]['acf'] ) ) {
				$spec['posts'][ $i ]['acf'] = ( isset( $entry['acf'] ) ? $entry['acf'] : array() ) + $spec['defaults'][ $c['type'] ]['acf'];
			}
		}
	}
	$SPECS[ $file ] = $spec;
}

foreach ( array_keys( $MEDIA ) as $key ) {
	mdt_cp_media( $key, $APPLY );
}

foreach ( $SPECS as $spec ) {
	foreach ( $spec['posts'] as $entry ) {
		if ( isset( $entry['create'] ) ) {
			mdt_cp_create( $entry, $APPLY );
		}
	}
}

foreach ( $SPECS as $file => $spec ) {
	foreach ( $spec['posts'] as $entry ) {
		$uk_id = mdt_cp_resolve( $entry['target'] );
		if ( ! $uk_id ) {
			continue; // created by this run; a dry run has nothing to compare yet
		}
		$type  = get_post_type( $uk_id );

		foreach ( $LANGS as $lang ) {
			$id = (int) apply_filters( 'wpml_object_id', $uk_id, $type, false, $lang );
			if ( ! $id ) {
				mdt_act( 'skip', "{$entry['target']} [$lang]", 'no translation in this language' );
				continue;
			}
			$label = "{$entry['target']} [$lang] #$id";

			foreach ( isset( $entry['acf'] ) ? $entry['acf'] : array() as $name => $value ) {
				$field   = mdt_cp_field( $name, $uk_id );
				$wanted  = mdt_cp_raw( $field, mdt_cp_value( $value, $lang ) );
				$current = get_field( $field['key'], $id, false );

				$field_modes += mdt_cp_meta_keys( $field, $wanted );

				if ( mdt_cp_norm( $current ) === mdt_cp_norm( $wanted ) ) {
					continue;
				}
				mdt_act( 'acf', $label, $name );
				if ( $APPLY ) {
					if ( in_array( $field['type'], array( 'repeater', 'flexible_content' ), true ) ) {
						// Drop the old rows first: update_field() only overwrites the sub-fields it is given, so a
						// row that changes layout would keep stale sub-field meta and never compare equal again.
						global $wpdb;
						$wpdb->query( $wpdb->prepare(
							"DELETE FROM $wpdb->postmeta WHERE post_id = %d AND meta_key REGEXP %s",
							$id,
							'^_?' . preg_quote( $field['name'] ) . '_[0-9]+_'
						) );
						wp_cache_delete( $id, 'post_meta' );
					}
					update_field( $field['key'], $wanted, $id );
				}
			}

			foreach ( isset( $entry['meta'] ) ? $entry['meta'] : array() as $key => $value ) {
				$wanted = mdt_cp_value( $value, $lang );
				$field_modes[ $key ] = true;
				if ( (string) get_post_meta( $id, $key, true ) === (string) $wanted ) {
					continue;
				}
				mdt_act( 'meta', $label, $key );
				if ( $APPLY ) {
					update_post_meta( $id, $key, wp_slash( $wanted ) );
				}
			}

			if ( isset( $entry['thumbnail'] ) ) {
				// Each language points at its own attachment twin, so the alt text can differ.
				$thumb = is_string( $entry['thumbnail'] ) && $entry['thumbnail'][0] === '@' ? mdt_cp_resolve( substr( $entry['thumbnail'], 1 ) ) : (int) $entry['thumbnail'];
				$att   = (int) apply_filters( 'wpml_object_id', $thumb, 'attachment', true, $lang );
				if ( (int) get_post_thumbnail_id( $id ) !== $att ) {
					mdt_act( 'thumbnail', $label, "attachment #$att" );
					if ( $APPLY ) {
						set_post_thumbnail( $id, $att );
					}
				}
				if ( isset( $entry['thumbnail_alt'] ) ) {
					$alt = mdt_cp_value( $entry['thumbnail_alt'], $lang );
					if ( (string) get_post_meta( $att, '_wp_attachment_image_alt', true ) !== $alt ) {
						mdt_act( 'alt', "attachment #$att [$lang]", $alt );
						if ( $APPLY ) {
							update_post_meta( $att, '_wp_attachment_image_alt', wp_slash( $alt ) );
						}
					}
				}
			}
		}
	}
}

// WPML translation modes for everything written above. Only fills gaps and upgrades text that is
// set to "copy" — existing "don't translate" / "copy once" settings are left alone.
global $iclTranslationManagement;
$modes   = isset( $iclTranslationManagement->settings['custom_fields_translation'] ) ? $iclTranslationManagement->settings['custom_fields_translation'] : array();
$changed = 0;
foreach ( $field_modes as $key => $is_text ) {
	$current = isset( $modes[ $key ] ) ? (int) $modes[ $key ] : null;
	if ( $is_text && ( $current === null || $current === WPML_COPY_CUSTOM_FIELD ) ) {
		$modes[ $key ] = WPML_TRANSLATE_CUSTOM_FIELD;
	} elseif ( ! $is_text && $current === null ) {
		$modes[ $key ] = WPML_COPY_CUSTOM_FIELD;
	} else {
		continue;
	}
	$changed++;
	mdt_act( 'wpml-mode', $key, $modes[ $key ] === WPML_TRANSLATE_CUSTOM_FIELD ? 'translate' : 'copy' );
}
if ( $changed && $APPLY ) {
	$iclTranslationManagement->settings['custom_fields_translation'] = $modes;
	$iclTranslationManagement->save_settings();
}

// ---------------------------------------------------------------------------
// Report
// ---------------------------------------------------------------------------

echo "\n" . str_pad( 'ACTION', 10 ) . ' | ' . str_pad( 'TARGET', 52 ) . " | DETAIL\n" . str_repeat( '-', 110 ) . "\n";
foreach ( $ACTIONS as $a ) {
	echo str_pad( $a[0], 10 ) . ' | ' . str_pad( mb_strimwidth( $a[1], 0, 52 ), 52 ) . ' | ' . mb_strimwidth( (string) $a[2], 0, 60, '…' ) . "\n";
}
echo str_repeat( '-', 110 ) . "\n";
$count = count( array_filter( $ACTIONS, function ( $a ) { return $a[0] !== 'skip'; } ) );
echo $count . ' action(s). ' . ( $APPLY ? 'APPLIED.' : 'Dry run — nothing written. Re-run with --apply.' ) . "\n";
