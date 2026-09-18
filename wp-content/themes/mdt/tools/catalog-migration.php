<?php
/**
 * Catalogue restructure: build the six-branch category tree.
 *
 * LOCAL DEV TOOL — never deployed to or executed on production. The finished local database is
 * exported and imported over the production one instead (see the cutover notes in CLAUDE.md).
 *
 * "Categories" here are a hierarchical CPT named `category` (CPT UI), not a taxonomy, so the tree
 * is post_parent-based. This script is idempotent: it is meant to be run once now against the
 * current dump, and again at cutover against a freshly pulled production dump.
 *
 * Usage (from the repo root):
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/catalog-migration.php
 *   docker compose exec wordpress php wp-content/themes/mdt/tools/catalog-migration.php --apply
 */

if ( php_sapi_name() !== 'cli' ) {
	die( 'CLI only.' );
}

require dirname( __FILE__, 5 ) . '/wp-load.php';

$APPLY = in_array( '--apply', $argv, true );

// ---------------------------------------------------------------------------
// Spec
// ---------------------------------------------------------------------------

/** Retitle in place. Slugs are never touched, so URLs and rankings survive. uk_id => [uk, ru]. */
$RENAMES = array(
	522  => array( 'Підлога', 'Пол' ),
	968  => array( 'Ковролін рулонний', 'Ковролин рулонный' ),
	1749 => array( 'Килимова плитка', 'Ковровая плитка' ),
	2207 => array( 'Флоковане покриття Flotex', 'Флокированное покрытие Flotex' ),
	1420 => array( 'ПВХ плитка LVT', 'ПВХ плитка LVT' ),
	1582 => array( 'Вінілові покриття', 'Виниловые покрытия' ),
	525  => array( 'Натуральний лінолеум Marmoleum', 'Натуральный линолеум Marmoleum' ),
	1690 => array( 'Брудозахист', 'Грязезащита' ),
	1741 => array( 'Індивідуальний дизайн підлоги', 'Индивидуальный дизайн пола' ),
	2671 => array( 'Стеля', 'Потолок' ),
);

/** uk_id => new uk parent id. The ru twin is moved under the ru twin of the parent. */
$REPARENTS = array(
	2608  => 522, // Фальшпідлога: top-level -> Підлога
	13209 => 522, // Матеріали для підлоги: hidden child, URL kept alive by a 301
);

/** uk_id => menu_order. Drives both the nav accordion and the homepage grid. */
$MENU_ORDER = array(
	// top level
	522 => 10, /* Меблі via ref */ 2527 => 30, 2671 => 40, 735 => 50, 13498 => 60,
	13209 => 999, // hidden
	// Підлога children
	968 => 10, 1749 => 20, 2207 => 30, 1420 => 40, 1582 => 50,
	525 => 60, 1690 => 70, 1741 => 80, 2608 => 90,
);

/** Existing uk categories that have no ru translation yet. uk_id => ru title. */
$RU_TWINS = array(
	13498 => 'Гибкий камень',
);

/**
 * New categories. parent is an int uk id, or 'ref:<ref>' resolved after its parent is created.
 * ref => [parent, slug, uk title, ru title, menu_order, uk subtitle, ru subtitle]
 */
$NEW = array(
	'mebli' => array( 0, 'mebli', 'Меблі', 'Мебель', 20,
		'Офісні та комерційні меблі для робочих, переговорних і громадських просторів.',
		'Офисная и коммерческая мебель для рабочих, переговорных и общественных пространств.' ),

	'mebli-robochi-krisla' => array( 'ref:mebli', 'robochi-krisla', 'Робочі крісла', 'Рабочие кресла', 10,
		'Ергономічні крісла для щоденної роботи за столом.',
		'Эргономичные кресла для ежедневной работы за столом.' ),
	'mebli-peregovorni-krisla' => array( 'ref:mebli', 'peregovorni-krisla', 'Переговорні крісла', 'Переговорные кресла', 20,
		'Крісла для переговорних кімнат і кабінетів керівників.',
		'Кресла для переговорных комнат и кабинетов руководителей.' ),
	'mebli-konferents-krisla' => array( 'ref:mebli', 'konferents-krisla', 'Конференц крісла', 'Конференц кресла', 30,
		'Штабельовані крісла для конференц-залів та аудиторій.',
		'Штабелируемые кресла для конференц-залов и аудиторий.' ),
	'mebli-barni-stiltsi' => array( 'ref:mebli', 'barni-stiltsi', 'Барні стільці', 'Барные стулья', 40,
		'Барні стільці для кафе, лаунж-зон та зон коворкінгу.',
		'Барные стулья для кафе, лаунж-зон и зон коворкинга.' ),
	'mebli-stoly' => array( 'ref:mebli', 'robochi-ta-peregovorni-stoly', 'Робочі та переговорні столи', 'Рабочие и переговорные столы', 50,
		'Робочі місця та переговорні столи в різних конфігураціях.',
		'Рабочие места и переговорные столы в различных конфигурациях.' ),
	'mebli-myaki' => array( 'ref:mebli', 'myaki-mebli', 'М’які меблі', 'Мягкая мебель', 60,
		'Дивани, пуфи та м’які зони для зон очікування й відпочинку.',
		'Диваны, пуфы и мягкие зоны для зон ожидания и отдыха.' ),
	'mebli-terasa' => array( 'ref:mebli', 'mebli-dlya-terasy', 'Меблі для тераси', 'Мебель для террасы', 70,
		'Вологостійкі меблі для терас, літніх майданчиків і зон відпочинку.',
		'Влагостойкая мебель для террас, летних площадок и зон отдыха.' ),

	'ak-kabiny' => array( 2527, 'akustychni-kabiny', 'Акустичні кабіни', 'Акустические кабины', 10,
		'Ізольовані кабіни для дзвінків, зустрічей і зосередженої роботи.',
		'Изолированные кабины для звонков, встреч и сосредоточенной работы.' ),
	'ak-stinovi' => array( 2527, 'stinovi-paneli', 'Стінові панелі', 'Стеновые панели', 20,
		'Звукопоглинальні панелі для стін офісів та громадських приміщень.',
		'Звукопоглощающие панели для стен офисов и общественных помещений.' ),
	'ak-stelovi' => array( 2527, 'stelovi-paneli', 'Стельові панелі', 'Потолочные панели', 30,
		'Акустичні стельові панелі для контролю шуму у відкритих просторах.',
		'Акустические потолочные панели для контроля шума в открытых пространствах.' ),

	'st-ostrovy' => array( 2671, 'akustychni-ostrovy-ta-bafly', 'Акустичні острови та бафли', 'Акустические острова и бафлы', 10,
		'Підвісні акустичні острови та бафли для великих відкритих приміщень.',
		'Подвесные акустические острова и бафлы для больших открытых помещений.' ),
	'st-knauf' => array( 2671, 'pidvisna-stelya-knauf', 'Підвесна стеля Knauf', 'Подвесной потолок Knauf', 20,
		'Системи підвісних стель Knauf для комерційних об’єктів.',
		'Системы подвесных потолков Knauf для коммерческих объектов.' ),
);

/**
 * Theme strings introduced by this change. The theme has no languages/ dir and never calls
 * load_theme_textdomain() — every _e(..., 'mdt') string is translated through WPML String
 * Translation instead, so new ones must be registered there or they render in Ukrainian on /ru/.
 * uk source => ru translation.
 */
$STRINGS = array(
	'Асортимент'   => 'Ассортимент',
	'КАТЕГОРІЇ'    => 'КАТЕГОРИИ',
	'Категорії'    => 'Категории',
	'Інші товари'  => 'Другие товары',
);

/** ACF groups whose `post != 522` location rule blocks the new Підлога page from having content. */
$ACF_GROUPS = array( 'group_5fd71029cf71e', 'group_6005673a2d597' );

const MDT_SUBTITLE_KEY = 'field_60059e1647d2e';
const MDT_REF_META     = '_mdt_migration_ref';

// ---------------------------------------------------------------------------
// Engine
// ---------------------------------------------------------------------------

$ACTIONS = array();

function mdt_act( $action, $target, $detail ) {
	global $ACTIONS;
	$ACTIONS[] = array( $action, $target, $detail );
}

/** ru counterpart of a uk category id, or 0. */
function mdt_ru_id( $uk_id ) {
	$id = icl_object_id( (int) $uk_id, 'category', false, 'ru' );
	return $id ? (int) $id : 0;
}

/** Find a previously-seeded post by its migration ref, bypassing WPML query filtering. */
function mdt_find_ref( $ref, $lang ) {
	global $wpdb;
	$id = $wpdb->get_var( $wpdb->prepare(
		"SELECT p.ID FROM {$wpdb->posts} p
		 JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = %s AND m.meta_value = %s
		 WHERE p.post_type = 'category' AND p.post_status != 'trash' LIMIT 1",
		MDT_REF_META, $ref . '/' . $lang
	) );
	return $id ? (int) $id : 0;
}

/** Force a slug past wp_unique_post_slug()'s sibling check (WPML allows the same slug per language). */
function mdt_force_slug( $id, $slug ) {
	global $wpdb;
	if ( get_post_field( 'post_name', $id ) === $slug ) {
		return;
	}
	$wpdb->update( $wpdb->posts, array( 'post_name' => $slug ), array( 'ID' => $id ) );
	clean_post_cache( $id );
}

function mdt_set_language( $id, $lang, $trid, $source ) {
	do_action( 'wpml_set_element_language_details', array(
		'element_id'           => $id,
		'element_type'         => 'post_category',
		'trid'                 => $trid,
		'language_code'        => $lang,
		'source_language_code' => $source,
	) );
}

/** Create (or return the existing) seeded category for one language. */
function mdt_upsert( $ref, $lang, $parent, $slug, $title, $menu_order, $subtitle, $trid, $source, $apply, $parent_label = null ) {
	if ( $parent_label === null ) {
		$parent_label = $parent;
	}
	$existing = mdt_find_ref( $ref, $lang );

	if ( $existing ) {
		$changes = array();
		$post    = get_post( $existing );
		if ( $post->post_title !== $title )            { $changes['post_title']  = $title; }
		if ( (int) $post->post_parent !== (int) $parent ) { $changes['post_parent'] = (int) $parent; }
		if ( (int) $post->menu_order !== (int) $menu_order ) { $changes['menu_order'] = (int) $menu_order; }
		if ( $changes ) {
			mdt_act( 'update', "$ref/$lang #$existing", implode( ', ', array_keys( $changes ) ) );
			if ( $apply ) {
				$changes['ID'] = $existing;
				wp_update_post( $changes );
				mdt_force_slug( $existing, $slug );
			}
		}
		return $existing;
	}

	mdt_act( 'create', "$ref/$lang", "$title  ($slug, parent $parent_label, order $menu_order)" );
	if ( ! $apply ) {
		return 0;
	}

	do_action( 'wpml_switch_language', $lang );

	$id = wp_insert_post( array(
		'post_type'   => 'category',
		'post_status' => 'publish',
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_parent' => (int) $parent,
		'menu_order'  => (int) $menu_order,
		'post_author' => 1,
	), true );

	if ( is_wp_error( $id ) ) {
		fwrite( STDERR, "FATAL: insert failed for $ref/$lang: " . $id->get_error_message() . "\n" );
		exit( 1 );
	}

	mdt_set_language( $id, $lang, $trid, $source );
	mdt_force_slug( $id, $slug );
	update_post_meta( $id, MDT_REF_META, $ref . '/' . $lang );
	if ( $subtitle !== '' ) {
		update_field( MDT_SUBTITLE_KEY, $subtitle, $id );
	}

	do_action( 'wpml_switch_language', 'uk' );
	return (int) $id;
}

// ---------------------------------------------------------------------------
// 1. Renames
// ---------------------------------------------------------------------------

foreach ( $RENAMES as $uk_id => $titles ) {
	foreach ( array( 'uk' => $uk_id, 'ru' => mdt_ru_id( $uk_id ) ) as $lang => $id ) {
		$title = ( $lang === 'uk' ) ? $titles[0] : $titles[1];
		if ( ! $id ) {
			mdt_act( 'skip', "rename $lang of #$uk_id", 'no translation exists' );
			continue;
		}
		$current = get_post_field( 'post_title', $id );
		if ( $current === $title ) {
			continue;
		}
		mdt_act( 'rename', "#$id ($lang)", "$current  ->  $title" );
		if ( $APPLY ) {
			wp_update_post( array( 'ID' => $id, 'post_title' => $title ) );
		}
	}
}

// ---------------------------------------------------------------------------
// 2. Reparents
// ---------------------------------------------------------------------------

foreach ( $REPARENTS as $uk_id => $uk_parent ) {
	$pairs = array( 'uk' => array( $uk_id, $uk_parent ) );
	$ru_id = mdt_ru_id( $uk_id );
	if ( $ru_id ) {
		$pairs['ru'] = array( $ru_id, mdt_ru_id( $uk_parent ) );
	}

	foreach ( $pairs as $lang => $pair ) {
		list( $id, $parent ) = $pair;
		if ( ! $id || ! $parent ) {
			continue;
		}
		if ( (int) get_post_field( 'post_parent', $id ) === (int) $parent ) {
			continue;
		}
		$slug = get_post_field( 'post_name', $id );
		mdt_act( 'reparent', "#$id ($lang)", "parent " . get_post_field( 'post_parent', $id ) . " -> $parent" );
		if ( $APPLY ) {
			wp_update_post( array( 'ID' => $id, 'post_parent' => (int) $parent ) );
			mdt_force_slug( $id, $slug );
		}
	}
}

// ---------------------------------------------------------------------------
// 3. ru twins for existing uk-only categories
// ---------------------------------------------------------------------------

foreach ( $RU_TWINS as $uk_id => $ru_title ) {
	if ( mdt_ru_id( $uk_id ) ) {
		continue;
	}
	$uk        = get_post( $uk_id );
	$trid      = apply_filters( 'wpml_element_trid', null, $uk_id, 'post_category' );
	$ru_parent = $uk->post_parent ? mdt_ru_id( $uk->post_parent ) : 0;

	mdt_upsert( 'twin-' . $uk_id, 'ru', $ru_parent, $uk->post_name, $ru_title,
		$uk->menu_order, '', $trid, 'uk', $APPLY );
}

// ---------------------------------------------------------------------------
// 4. New categories (uk + ru pairs)
// ---------------------------------------------------------------------------

$resolved = array();

foreach ( $NEW as $ref => $row ) {
	list( $parent, $slug, $uk_title, $ru_title, $order, $uk_sub, $ru_sub ) = $row;

	$parent_label = $parent;
	if ( is_string( $parent ) && strpos( $parent, 'ref:' ) === 0 ) {
		$parent_ref = substr( $parent, 4 );
		$uk_parent  = isset( $resolved[ $parent_ref ] ) ? $resolved[ $parent_ref ] : mdt_find_ref( $parent_ref, 'uk' );
	} else {
		$uk_parent    = (int) $parent;
		$parent_label = (int) $parent;
	}

	$uk_id = mdt_upsert( $ref, 'uk', $uk_parent, $slug, $uk_title, $order, $uk_sub, null, null, $APPLY, $parent_label );
	$resolved[ $ref ] = $uk_id;

	// Reported (and created) even on a dry run, so the review artifact shows both languages.
	$trid         = $uk_id ? apply_filters( 'wpml_element_trid', null, $uk_id, 'post_category' ) : null;
	$ru_parent    = $uk_parent ? mdt_ru_id( $uk_parent ) : 0;
	$ru_par_label = $ru_parent ? $ru_parent : ( is_string( $parent_label ) ? $parent_label : $ru_parent );

	mdt_upsert( $ref, 'ru', $ru_parent, $slug, $ru_title, $order, $ru_sub, $trid, 'uk', $APPLY, $ru_par_label );
}

// ---------------------------------------------------------------------------
// 5. menu_order on the pre-existing categories
// ---------------------------------------------------------------------------

foreach ( $MENU_ORDER as $uk_id => $order ) {
	foreach ( array( 'uk' => $uk_id, 'ru' => mdt_ru_id( $uk_id ) ) as $lang => $id ) {
		if ( ! $id || (int) get_post_field( 'menu_order', $id ) === (int) $order ) {
			continue;
		}
		mdt_act( 'menu_order', "#$id ($lang)", get_post_field( 'menu_order', $id ) . " -> $order" );
		if ( $APPLY ) {
			$slug = get_post_field( 'post_name', $id );
			wp_update_post( array( 'ID' => $id, 'menu_order' => (int) $order ) );
			mdt_force_slug( $id, $slug );
		}
	}
}

// ---------------------------------------------------------------------------
// 6. Unblock ACF on 522
// ---------------------------------------------------------------------------

foreach ( $ACF_GROUPS as $group_key ) {
	$group = function_exists( 'acf_get_field_group' ) ? acf_get_field_group( $group_key ) : null;
	if ( ! $group ) {
		mdt_act( 'skip', "acf $group_key", 'group not found' );
		continue;
	}

	$changed = false;
	foreach ( $group['location'] as $gi => $rules ) {
		foreach ( $rules as $ri => $rule ) {
			if ( $rule['param'] === 'post' && $rule['operator'] === '!=' && (string) $rule['value'] === '522' ) {
				unset( $group['location'][ $gi ][ $ri ] );
				$changed = true;
			}
		}
		$group['location'][ $gi ] = array_values( $group['location'][ $gi ] );
	}

	if ( ! $changed ) {
		continue;
	}

	mdt_act( 'acf', $group_key, 'drop "post != 522" location rule' );
	if ( $APPLY ) {
		$group['location'] = array_values( array_filter( $group['location'] ) );
		acf_update_field_group( $group );
	}
}

// ---------------------------------------------------------------------------
// 7. WPML string translations for the new theme strings
// ---------------------------------------------------------------------------

global $wpdb;
foreach ( $STRINGS as $uk => $ru ) {
	$name      = md5( $uk );
	$string_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM {$wpdb->prefix}icl_strings WHERE context = 'mdt' AND name = %s", $name
	) );

	if ( $string_id ) {
		$has = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}icl_string_translations
			 WHERE string_id = %d AND language = 'ru' AND status = %d", $string_id, ICL_TM_COMPLETE
		) );
		if ( $has ) {
			continue;
		}
	}

	mdt_act( 'string', $uk, "ru: $ru" );
	if ( ! $APPLY ) {
		continue;
	}

	icl_register_string( 'mdt', $name, $uk, false, 'uk' );
	$string_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM {$wpdb->prefix}icl_strings WHERE context = 'mdt' AND name = %s", $name
	) );
	if ( $string_id ) {
		icl_add_string_translation( $string_id, 'ru', $ru, ICL_TM_COMPLETE );
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
