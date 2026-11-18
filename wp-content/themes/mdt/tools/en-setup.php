<?php
/**
 * Add English as a third language: language activation + site chrome.
 *
 * LOCAL DEV TOOL — never deployed to or executed on production. The finished local database is
 * exported and imported over the production one instead (see the cutover notes in CLAUDE.md).
 *
 * This script covers everything that is *not* post content: activating `en` in WPML, the EN nav
 * menus, the EN Contact Form 7 forms, the per-language ACF options-page rows and the WPML String
 * Translation entries for the theme's own strings. Post content (pages, categories, products,
 * projects, …) is handled by en-translate.php.
 *
 * English text lives in tools/i18n-en/chrome.json, not in here, so it can be reviewed as data.
 * Parts whose data is missing are reported as skipped rather than failing, so the language can be
 * switched on and verified before any of the translation work exists.
 *
 * Idempotent: a second run reports zero actions. That matters because the cutover re-runs it
 * against a freshly pulled production dump.
 *
 * Usage (from the repo root):
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/en-setup.php
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/en-setup.php --apply
 */

if ( php_sapi_name() !== 'cli' ) {
	die( 'CLI only.' );
}

require dirname( __FILE__, 5 ) . '/wp-load.php';

$APPLY     = in_array( '--apply', $argv, true );
$CHROME    = dirname( __FILE__ ) . '/i18n-en/chrome.json';
$LANGUAGES = array( 'uk', 'ru', 'en' );

// ---------------------------------------------------------------------------
// Engine
// ---------------------------------------------------------------------------

$ACTIONS = array();

function mdt_act( $action, $target, $detail ) {
	global $ACTIONS;
	$ACTIONS[] = array( $action, $target, $detail );
}

/** Keep the report's DETAIL column readable. */
function mdt_trim( $text, $length = 58 ) {
	$text = trim( preg_replace( '/\s+/u', ' ', (string) $text ) );
	return mb_strlen( $text ) > $length ? mb_substr( $text, 0, $length - 1 ) . '…' : $text;
}

function mdt_fatal( $msg ) {
	fwrite( STDERR, "FATAL: $msg\n" );
	exit( 1 );
}

/** chrome.json, or an empty array when it hasn't been written yet. */
function mdt_chrome() {
	static $data = null;
	if ( $data !== null ) {
		return $data;
	}
	global $CHROME;
	if ( ! file_exists( $CHROME ) ) {
		return $data = array();
	}
	$decoded = json_decode( file_get_contents( $CHROME ), true );
	if ( ! is_array( $decoded ) ) {
		mdt_fatal( 'i18n-en/chrome.json is not valid JSON.' );
	}
	return $data = $decoded;
}

function mdt_chrome_section( $key ) {
	$chrome = mdt_chrome();
	return isset( $chrome[ $key ] ) && is_array( $chrome[ $key ] ) ? $chrome[ $key ] : array();
}

// ---------------------------------------------------------------------------
// 1. Activate `en`
// ---------------------------------------------------------------------------

/*
 * WPML ships all 67 languages in wp_icl_languages; `en` is already there with active = 0, tag 'en'
 * and locale en_US. Going through WPML's own installer rather than SQL is what keeps
 * icl_sitepress_settings['active_languages'], wp_icl_locale_map, languages_order and WPML's caches
 * consistent — set_active_languages() does all of that in updated_active_languages().
 *
 * It deactivates every code NOT passed in, so all three are always listed.
 */

global $sitepress;

$active = array_keys( (array) $sitepress->get_active_languages() );
sort( $active );
$want = $LANGUAGES;
sort( $want );

if ( $active !== $want ) {
	mdt_act( 'language', 'en', 'activate (' . implode( ', ', $active ) . ' -> ' . implode( ', ', $want ) . ')' );

	if ( $APPLY ) {
		if ( ! function_exists( 'wpml_get_setup_instance' ) ) {
			mdt_fatal( 'wpml_get_setup_instance() is unavailable — is WPML active?' );
		}
		if ( ! wpml_get_setup_instance()->set_active_languages( $LANGUAGES ) ) {
			mdt_fatal( 'set_active_languages() refused the language list.' );
		}

		$now = array_keys( (array) $sitepress->get_active_languages( true ) );
		sort( $now );
		if ( $now !== $want ) {
			mdt_fatal( 'language activation did not stick: got ' . implode( ', ', $now ) );
		}
	}
}

// ---------------------------------------------------------------------------
// 1b. Never drop a language from the switcher
// ---------------------------------------------------------------------------

/*
 * icl_post_languages() (functions.php) renders whatever icl_get_languages() returns, and with
 * icl_lso_link_empty = 0 WPML omits any language that has no translation of the current page. On a
 * site that is mid-translation that means EN silently disappears from the navbar on most pages,
 * which reads as a bug rather than as missing content. Flipping it to 1 makes the switcher always
 * offer all three languages, linking to the language home when a page has no translation.
 */

if ( (int) $sitepress->get_setting( 'icl_lso_link_empty' ) !== 1 ) {
	mdt_act( 'setting', 'icl_lso_link_empty', '0 -> 1 (always show every language)' );
	if ( $APPLY ) {
		$sitepress->set_setting( 'icl_lso_link_empty', 1, true );
	}
}

// ---------------------------------------------------------------------------
// 2. Theme strings (WPML String Translation)
// ---------------------------------------------------------------------------

/*
 * The theme has no languages/ directory and never calls load_theme_textdomain(), so every
 * _e(..., 'mdt') call is translated through WPML String Translation instead. Auto-registration is
 * switched off site-wide (st.icl_st_auto_reg = 'disable'), so a string that is not registered here
 * can never be translated and will render in Ukrainian on /en/.
 *
 * The source language is passed explicitly: the site's String Translation default is set to 'en',
 * and a string registered under that default would have its Ukrainian text treated as the English
 * one — /en/ would then show Ukrainian with no way to correct it.
 */

global $wpdb;

foreach ( mdt_chrome_section( 'strings' ) as $uk => $en ) {
	$name = md5( $uk );
	$row  = $wpdb->get_row( $wpdb->prepare(
		"SELECT id, language FROM {$wpdb->prefix}icl_strings WHERE context = 'mdt' AND name = %s", $name
	) );

	if ( ! $row ) {
		mdt_act( 'string', mdt_trim( $uk ), 'register (source uk)' );
		if ( $APPLY ) {
			icl_register_string( 'mdt', $name, $uk, false, 'uk' );
			$row = $wpdb->get_row( $wpdb->prepare(
				"SELECT id, language FROM {$wpdb->prefix}icl_strings WHERE context = 'mdt' AND name = %s", $name
			) );
		}
	}

	if ( ! $row ) {
		continue; // dry run
	}

	if ( $row->language !== 'uk' ) {
		mdt_act( 'string', mdt_trim( $uk ), "source language {$row->language} -> uk" );
		if ( $APPLY ) {
			$wpdb->update( "{$wpdb->prefix}icl_strings", array( 'language' => 'uk' ), array( 'id' => $row->id ) );
		}
	}

	$current = $wpdb->get_var( $wpdb->prepare(
		"SELECT value FROM {$wpdb->prefix}icl_string_translations
		 WHERE string_id = %d AND language = 'en' AND status = %d", $row->id, ICL_TM_COMPLETE
	) );

	if ( $current !== $en ) {
		mdt_act( 'string', mdt_trim( $uk ), 'en: ' . mdt_trim( $en ) );
		if ( $APPLY ) {
			icl_add_string_translation( $row->id, 'en', $en, ICL_TM_COMPLETE );
		}
	}
}

// ---------------------------------------------------------------------------
// 3. ACF options page
// ---------------------------------------------------------------------------

/*
 * ACF's options page is stored per language as options_<lang>_<field> (plus the _options_… row
 * holding the field key), which is how /ru/ gets its own mega-menu blurb and modal copy. It is not
 * WPML String Translation, so nothing else in this script covers it. Fields with no English text —
 * the two image fields — are copied across verbatim so get_field(..., 'option') resolves on /en/
 * at all.
 */

$mdt_en_option_text = mdt_chrome_section( 'options' );
$mdt_uk_options     = $wpdb->get_col(
	"SELECT option_name FROM {$wpdb->options}
	 WHERE option_name LIKE 'options\_%' AND option_name NOT LIKE 'options\_ru\_%'
	   AND option_name NOT LIKE 'options\_en\_%' AND option_name NOT LIKE '\_%'"
);

foreach ( $mdt_uk_options as $uk_option ) {
	$field  = substr( $uk_option, strlen( 'options_' ) );
	$wanted = isset( $mdt_en_option_text[ $field ] ) ? $mdt_en_option_text[ $field ] : get_option( $uk_option );

	if ( get_option( 'options_en_' . $field ) !== $wanted ) {
		mdt_act( 'option', 'options_en_' . $field, mdt_trim( $wanted ) );
		if ( $APPLY ) {
			update_option( 'options_en_' . $field, $wanted );
		}
	}

	// ACF resolves a field through its _<name> companion row; without it get_field() returns null.
	$reference = get_option( '_' . $uk_option );
	if ( $reference && get_option( '_options_en_' . $field ) !== $reference ) {
		mdt_act( 'option', '_options_en_' . $field, $reference );
		if ( $APPLY ) {
			update_option( '_options_en_' . $field, $reference );
		}
	}
}

// ---------------------------------------------------------------------------
// 4. Navigation menus
// ---------------------------------------------------------------------------

/*
 * header.php fetches the primary nav by a hardcoded id, wp_get_nav_menu_items(32), and the footer
 * and info navs come through nav_menu widgets. All of them are still language-correct, because
 * wp_get_nav_menu_object() goes through get_term(), which WPML filters to swap in the current
 * language's menu (sitepress.class.php -> get_term_adjust_id). That only works if an English menu
 * exists as a translation of the Ukrainian one — otherwise /en/ quietly renders the Ukrainian menu,
 * and the catalogue mega-menu does not open at all, because the item it looks for is identified by
 * what it points at and the Ukrainian item points at the Ukrainian catalogue root.
 */

$mdt_en_menu_names = mdt_chrome_section( 'menus' );
$mdt_en_item_names = mdt_chrome_section( 'menu_items' );

$mdt_uk_menus = $wpdb->get_col( $wpdb->prepare(
	"SELECT element_id FROM {$wpdb->prefix}icl_translations
	 WHERE element_type = 'tax_nav_menu' AND language_code = %s", 'uk'
) );

foreach ( $mdt_uk_menus as $mdt_uk_menu_id ) {
	$mdt_uk_menu = get_term( (int) $mdt_uk_menu_id, 'nav_menu' );
	if ( ! $mdt_uk_menu || is_wp_error( $mdt_uk_menu ) ) {
		continue;
	}

	$mdt_en_menu_id = icl_object_id( (int) $mdt_uk_menu_id, 'nav_menu', false, 'en' );
	$mdt_en_name    = isset( $mdt_en_menu_names[ $mdt_uk_menu->name ] )
		? $mdt_en_menu_names[ $mdt_uk_menu->name ]
		: $mdt_uk_menu->name . ' (EN)';

	if ( ! $mdt_en_menu_id ) {
		mdt_act( 'menu', $mdt_uk_menu->name, 'create "' . $mdt_en_name . '"' );
		if ( $APPLY ) {
			$created = wp_insert_term( $mdt_en_name, 'nav_menu' );
			if ( is_wp_error( $created ) ) {
				mdt_fatal( 'menu insert failed: ' . $created->get_error_message() );
			}
			$mdt_en_menu_id = (int) $created['term_id'];

			do_action( 'wpml_set_element_language_details', array(
				'element_id'           => $mdt_en_menu_id,
				'element_type'         => 'tax_nav_menu',
				'trid'                 => apply_filters( 'wpml_element_trid', null, (int) $mdt_uk_menu_id, 'tax_nav_menu' ),
				'language_code'        => 'en',
				'source_language_code' => 'uk',
			) );
		}
	}

	if ( ! $mdt_en_menu_id ) {
		continue; // dry run
	}

	// Both reads have to happen in their own language. wp_get_nav_menu_items() resolves the menu
	// through get_term(), which WPML rewrites to the current language's menu — asking for the
	// English menu while the process is still in Ukrainian hands back the Ukrainian one, so the
	// "does this item already exist" lookup never matches and every run appends the items again.
	$mdt_uk_menu_items = (array) wp_get_nav_menu_items( $mdt_uk_menu_id, array( 'output_key' => 'menu_order' ) );

	do_action( 'wpml_switch_language', 'en' );
	$mdt_en_items = array();
	foreach ( (array) wp_get_nav_menu_items( $mdt_en_menu_id, array( 'output_key' => 'menu_order' ) ) as $item ) {
		$mdt_en_items[ $item->type . ':' . $item->object . ':' . $item->object_id ] = $item;
	}
	do_action( 'wpml_switch_language', 'uk' );

	foreach ( $mdt_uk_menu_items as $mdt_uk_item ) {
		$mdt_target = $mdt_uk_item->object_id;
		if ( $mdt_uk_item->type === 'post_type' ) {
			$translated = icl_object_id( (int) $mdt_uk_item->object_id, $mdt_uk_item->object, false, 'en' );
			if ( ! $translated ) {
				mdt_act( 'skip', 'menu item "' . $mdt_uk_item->title . '"', 'no en translation of ' . $mdt_uk_item->object . ' #' . $mdt_uk_item->object_id );
				continue;
			}
			$mdt_target = (int) $translated;
		}

		$mdt_title = isset( $mdt_en_item_names[ $mdt_uk_item->title ] )
			? $mdt_en_item_names[ $mdt_uk_item->title ]
			: get_the_title( $mdt_target );

		$mdt_key      = $mdt_uk_item->type . ':' . $mdt_uk_item->object . ':' . $mdt_target;
		$mdt_existing = isset( $mdt_en_items[ $mdt_key ] ) ? $mdt_en_items[ $mdt_key ] : null;

		if ( $mdt_existing
			&& $mdt_existing->title === $mdt_title
			&& (int) $mdt_existing->menu_order === (int) $mdt_uk_item->menu_order ) {
			continue;
		}

		mdt_act( 'menu item', $mdt_en_name, ( $mdt_existing ? 'update' : 'add' ) . ' "' . $mdt_title . '"' );
		if ( ! $APPLY ) {
			continue;
		}

		do_action( 'wpml_switch_language', 'en' );
		$mdt_item_id = wp_update_nav_menu_item( $mdt_en_menu_id, $mdt_existing ? $mdt_existing->ID : 0, array(
			'menu-item-object-id' => $mdt_target,
			'menu-item-object'    => $mdt_uk_item->object,
			'menu-item-type'      => $mdt_uk_item->type,
			'menu-item-url'       => $mdt_uk_item->type === 'custom' ? $mdt_uk_item->url : '',
			'menu-item-title'     => $mdt_title,
			'menu-item-status'    => 'publish',
			'menu-item-position'  => (int) $mdt_uk_item->menu_order,
		) );
		do_action( 'wpml_switch_language', 'uk' );

		if ( is_wp_error( $mdt_item_id ) ) {
			mdt_fatal( 'menu item failed: ' . $mdt_item_id->get_error_message() );
		}

		do_action( 'wpml_set_element_language_details', array(
			'element_id'           => $mdt_item_id,
			'element_type'         => 'post_nav_menu_item',
			'trid'                 => apply_filters( 'wpml_element_trid', null, $mdt_uk_item->ID, 'post_nav_menu_item' ),
			'language_code'        => 'en',
			'source_language_code' => 'uk',
		) );
	}
}

// ---------------------------------------------------------------------------
// 4b. Rank Math title and description templates
// ---------------------------------------------------------------------------

/*
 * Rank Math's global templates are site-wide options, translated through WPML's admin-texts
 * strings rather than String Translation's theme domain — so they need their own pass, keyed by
 * WPML string name instead of by source text. They are written in Russian, so without this every
 * /en/ page ships a Russian <meta name="description">.
 *
 * Only the templates that actually contain prose are listed in chrome.json; the rest are nothing
 * but placeholders (%title% %sep% %sitename%) and read the same in any language.
 */

/*
 * First the source language itself. The templates are Russian text stored as the Ukrainian (default
 * language) original with no ru translation, so uk and ru pages both printed Russian. Write the uk
 * text into the option and its WPML string, and keep the old Russian text as the ru translation.
 * Runs before the English pass below because changing a source string can flag its translations as
 * needing an update; that pass then re-marks the English ones complete.
 */
$mdt_src        = mdt_chrome_section( 'seo_source' );
$mdt_src_option = isset( $mdt_src['option'] ) ? $mdt_src['option'] : '';
$mdt_src_ctx    = isset( $mdt_src['context'] ) ? $mdt_src['context'] : '';

foreach ( isset( $mdt_src['strings'] ) ? $mdt_src['strings'] : array() as $mdt_src_name => $mdt_src_text ) {
	$mdt_src_key = preg_replace( '/^\[[^\]]+\]/', '', $mdt_src_name );
	$mdt_src_row = $wpdb->get_row( $wpdb->prepare(
		"SELECT id, value FROM {$wpdb->prefix}icl_strings WHERE context = %s AND name = %s",
		$mdt_src_ctx, $mdt_src_name
	) );
	if ( ! $mdt_src_row ) {
		mdt_act( 'skip', mdt_trim( $mdt_src_name, 28 ), 'string not registered' );
		continue;
	}

	// The option is read raw (no WPML filter) so this compares what is actually stored.
	$mdt_src_opts = $wpdb->get_var( $wpdb->prepare(
		"SELECT option_value FROM {$wpdb->options} WHERE option_name = %s", $mdt_src_option
	) );
	$mdt_src_opts = is_string( $mdt_src_opts ) ? maybe_unserialize( $mdt_src_opts ) : array();

	if ( ! isset( $mdt_src_opts[ $mdt_src_key ] ) || $mdt_src_opts[ $mdt_src_key ] !== $mdt_src_text['uk']
		|| $mdt_src_row->value !== $mdt_src_text['uk'] ) {
		mdt_act( 'seo-uk', mdt_trim( $mdt_src_name, 28 ), mdt_trim( $mdt_src_text['uk'] ) );
		if ( $APPLY ) {
			$mdt_src_opts[ $mdt_src_key ] = $mdt_src_text['uk'];
			$wpdb->update( $wpdb->options, array( 'option_value' => maybe_serialize( $mdt_src_opts ) ), array( 'option_name' => $mdt_src_option ) );
			wp_cache_delete( $mdt_src_option, 'options' );
			wp_cache_delete( 'alloptions', 'options' );
			$wpdb->update( "{$wpdb->prefix}icl_strings", array( 'value' => $mdt_src_text['uk'] ), array( 'id' => $mdt_src_row->id ) );
		}
	}

	$mdt_src_ru = $wpdb->get_var( $wpdb->prepare(
		"SELECT value FROM {$wpdb->prefix}icl_string_translations
		 WHERE string_id = %d AND language = 'ru' AND status = %d", $mdt_src_row->id, ICL_TM_COMPLETE
	) );
	if ( $mdt_src_ru !== $mdt_src_text['ru'] ) {
		mdt_act( 'seo-ru', mdt_trim( $mdt_src_name, 28 ), mdt_trim( $mdt_src_text['ru'] ) );
		if ( $APPLY ) {
			icl_add_string_translation( $mdt_src_row->id, 'ru', $mdt_src_text['ru'], ICL_TM_COMPLETE );
		}
	}
}

$mdt_seo         = mdt_chrome_section( 'seo' );
$mdt_seo_context = isset( $mdt_seo['context'] ) ? $mdt_seo['context'] : '';
$mdt_seo_strings = isset( $mdt_seo['strings'] ) ? $mdt_seo['strings'] : array();

foreach ( $mdt_seo_strings as $mdt_seo_name => $mdt_seo_en ) {
	$mdt_seo_row = $wpdb->get_row( $wpdb->prepare(
		"SELECT id, value FROM {$wpdb->prefix}icl_strings WHERE context = %s AND name = %s",
		$mdt_seo_context, $mdt_seo_name
	) );

	if ( ! $mdt_seo_row ) {
		mdt_act( 'skip', mdt_trim( $mdt_seo_name, 28 ), 'string not registered' );
		continue;
	}

	$mdt_seo_current = $wpdb->get_var( $wpdb->prepare(
		"SELECT value FROM {$wpdb->prefix}icl_string_translations
		 WHERE string_id = %d AND language = 'en' AND status = %d", $mdt_seo_row->id, ICL_TM_COMPLETE
	) );

	if ( $mdt_seo_current === $mdt_seo_en ) {
		continue;
	}

	mdt_act( 'seo', mdt_trim( $mdt_seo_name, 28 ), mdt_trim( $mdt_seo_en ) );
	if ( $APPLY ) {
		icl_add_string_translation( $mdt_seo_row->id, 'en', $mdt_seo_en, ICL_TM_COMPLETE );
	}
}

// ---------------------------------------------------------------------------
// 5. Contact Form 7
// ---------------------------------------------------------------------------

/*
 * The three forms the theme embeds get English twins, linked to their Ukrainian originals by trid.
 * mdt_cf7_id() in functions.php then resolves them through icl_object_id(), so no form ids need
 * hardcoding for English — or for any language added later.
 *
 * Only the human-readable text is swapped, by replacing the phrases listed in chrome.json's
 * forms.text. The shortcodes, field names, CSS classes and mail headers are copied byte for byte:
 * the field names are what the mail template refers to, and a well-meant translation of one would
 * silently break the emails.
 *
 * The Russian forms are deliberately left alone. 6502 and 6510 are titled "RU" but registered as
 * `uk` and share no trid with their Ukrainian counterparts, which is why mdt_cf7_id() still takes
 * an explicit Russian id. Repairing that data is its own job.
 */

$mdt_forms      = mdt_chrome_section( 'forms' );
$mdt_form_text  = isset( $mdt_forms['text'] ) ? $mdt_forms['text'] : array();
$mdt_form_names = isset( $mdt_forms['titles'] ) ? $mdt_forms['titles'] : array();
$mdt_form_msgs  = isset( $mdt_forms['messages'] ) ? $mdt_forms['messages'] : array();

// Longest phrase first, so "Посилання на резюме" is not half-replaced by "Резюме".
uksort( $mdt_form_text, function ( $a, $b ) {
	return mb_strlen( $b ) - mb_strlen( $a );
} );

/** Swap the Ukrainian phrases for English ones, leaving everything else untouched. */
function mdt_en_form_text( $value ) {
	global $mdt_form_text;

	if ( is_array( $value ) ) {
		return array_map( 'mdt_en_form_text', $value );
	}
	if ( ! is_string( $value ) || $value === '' ) {
		return $value;
	}

	return str_replace( array_keys( $mdt_form_text ), array_values( $mdt_form_text ), $value );
}

foreach ( array( 6498, 133, 6511 ) as $mdt_uk_form_id ) {
	$mdt_uk_form = get_post( $mdt_uk_form_id );
	if ( ! $mdt_uk_form || $mdt_uk_form->post_type !== 'wpcf7_contact_form' ) {
		mdt_act( 'skip', "cf7 #$mdt_uk_form_id", 'form not found' );
		continue;
	}

	$mdt_en_title = isset( $mdt_form_names[ $mdt_uk_form->post_title ] )
		? $mdt_form_names[ $mdt_uk_form->post_title ]
		: $mdt_uk_form->post_title . ' EN';

	$mdt_en_form_id = icl_object_id( $mdt_uk_form_id, 'wpcf7_contact_form', false, 'en' );

	if ( ! $mdt_en_form_id ) {
		mdt_act( 'cf7', "#$mdt_uk_form_id " . mdt_trim( $mdt_uk_form->post_title, 24 ), 'create "' . $mdt_en_title . '"' );
		if ( $APPLY ) {
			$mdt_en_form_id = wp_insert_post( array(
				'post_type'   => 'wpcf7_contact_form',
				'post_status' => 'publish',
				'post_title'  => $mdt_en_title,
				'post_name'   => sanitize_title( $mdt_en_title ),
				'post_author' => $mdt_uk_form->post_author,
			), true );

			if ( is_wp_error( $mdt_en_form_id ) ) {
				mdt_fatal( 'cf7 insert failed: ' . $mdt_en_form_id->get_error_message() );
			}

			do_action( 'wpml_set_element_language_details', array(
				'element_id'           => $mdt_en_form_id,
				'element_type'         => 'post_wpcf7_contact_form',
				'trid'                 => apply_filters( 'wpml_element_trid', null, $mdt_uk_form_id, 'post_wpcf7_contact_form' ),
				'language_code'        => 'en',
				'source_language_code' => 'uk',
			) );
		}
	}

	if ( ! $mdt_en_form_id ) {
		continue; // dry run
	}

	$mdt_wanted = array(
		'_form'                => mdt_en_form_text( get_post_meta( $mdt_uk_form_id, '_form', true ) ),
		'_mail'                => mdt_en_form_text( get_post_meta( $mdt_uk_form_id, '_mail', true ) ),
		'_mail_2'              => mdt_en_form_text( get_post_meta( $mdt_uk_form_id, '_mail_2', true ) ),
		'_additional_settings' => get_post_meta( $mdt_uk_form_id, '_additional_settings', true ),
		'_locale'              => 'en_US',
		'_messages'            => $mdt_form_msgs
			? array_merge( (array) get_post_meta( $mdt_uk_form_id, '_messages', true ), $mdt_form_msgs )
			: get_post_meta( $mdt_uk_form_id, '_messages', true ),
	);

	foreach ( $mdt_wanted as $mdt_key => $mdt_value ) {
		if ( $mdt_value === '' || $mdt_value === false || $mdt_value === null ) {
			continue;
		}
		if ( get_post_meta( $mdt_en_form_id, $mdt_key, true ) === $mdt_value ) {
			continue;
		}
		mdt_act( 'cf7', "#$mdt_en_form_id", $mdt_key );
		if ( $APPLY ) {
			update_post_meta( $mdt_en_form_id, $mdt_key, wp_slash( $mdt_value ) );
		}
	}

	if ( get_post_field( 'post_title', $mdt_en_form_id ) !== $mdt_en_title ) {
		mdt_act( 'cf7', "#$mdt_en_form_id", 'title -> ' . $mdt_en_title );
		if ( $APPLY ) {
			wp_update_post( array( 'ID' => $mdt_en_form_id, 'post_title' => $mdt_en_title ) );
		}
	}
}

// ---------------------------------------------------------------------------
// Report
// ---------------------------------------------------------------------------

echo "\n";
printf( "%-11s | %-28s | %s\n", 'ACTION', 'TARGET', 'DETAIL' );
echo str_repeat( '-', 100 ) . "\n";
foreach ( $ACTIONS as $a ) {
	printf( "%-11s | %-28s | %s\n", $a[0], $a[1], $a[2] );
}
echo str_repeat( '-', 100 ) . "\n";
printf( "%d action(s). %s\n\n", count( $ACTIONS ), $APPLY ? 'APPLIED.' : 'Dry run — nothing written. Re-run with --apply.' );
