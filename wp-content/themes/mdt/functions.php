<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
error_reporting(E_ALL);

if (strpos($_SERVER['REQUEST_URI'], "eval(") ||	strpos($_SERVER['REQUEST_URI'], "CONCAT") || strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||	strpos($_SERVER['REQUEST_URI'], "base64")) {
	@header("HTTP/1.1 400 Bad Request");
	@header("Status: 400 Bad Request");
	@header("Connection: Close");
	@exit;
}

function formatPhone($phone) {
	$chars = array(" ", "-", "(", ")");
	return str_replace($chars, '', $phone);
}

function remove_version_from_js_and_css($src) {
    if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'remove_version_from_js_and_css', 9999);
add_filter('script_loader_src', 'remove_version_from_js_and_css', 9999);


//Скрываем версию WordPress
function true_remove_wp_version_wp_head_feed() {
	return '';
}
 add_filter('the_generator', 'true_remove_wp_version_wp_head_feed');

//Меняем футер в админке
function true_change_admin_footer () {
	$footer_text = array(
		'',
	);
	return implode( ' &bull; ', $footer_text);
}
add_filter('admin_footer_text', 'true_change_admin_footer');

// Image Thumbnails
add_theme_support( 'post-thumbnails' );
// Hands <title> to Rank Math. Without this its rewrite is inert (rewrite_title is off), so the
// configured title template and every per-post rank_math_title were being silently discarded.
// Paired with the removal of the hardcoded <title> in header.php - do not add one back.
add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

if( function_exists('acf_add_options_page') ) {
  acf_add_options_page();
}

remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );


function h5bs_remove_junk() {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
}

add_action( 'init', 'h5bs_remove_junk' );

function remove_admin_login_header() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');

if (function_exists('add_theme_support')) {
	add_theme_support('menus');
}

add_filter( 'intermediate_image_sizes_advanced', 'prefix_remove_default_images' );
function prefix_remove_default_images( $sizes ) {
	unset( $sizes['alm-thumbnail']);
	unset( $sizes['medium_large']);
	unset( $sizes['medium']);
	unset( $sizes['large']);
	return $sizes;
}

add_image_size( 'post-main', 1380, 615, true );
add_image_size( 'g-h', 1250, 670, true );
add_image_size( 'g-v', 620, 470, true );
add_image_size( 'g-v2', 420, 670, true );
add_image_size( 'pr-prev', 610, 508, true );
add_image_size( 'us-prev', 406, 456, true );
add_image_size( 'n-prev', 610, 674, true );
add_image_size( 'f-prev', 390, 590, true );
add_image_size( 'f-prev-h', 590, 590, true );
add_image_size( 'full', 1920, 1080, true );
add_image_size( 'g-prev', 344, 492, true );
add_image_size( 'c-prev-1', 476, 660, true );
add_image_size( 'c-prev-2', 228, 320, true );
add_image_size( 'p-video', 975, 548, true );

add_image_size( 'a-1', 620, 760, true );
add_image_size( 'a-2', 720, 516, true );

add_image_size( 't-1', 390, 634, true );
add_image_size( 't-2', 94, 94, true );

add_filter( 'body_class', 'my_neat_body_class');

function my_neat_body_class( $classes ) {
	if (is_page_template('tpl__main.php')){
		$classes[] = 'page-front';
	}else{
    $classes[] = 'not-front';
  }

  if(is_page_template('tpl__contacts.php')){
    $classes[] = 'page-contacts';
  }

  if(is_page_template('tpl__about.php')){
    $classes = [];
    $classes[] = 'page-about';
  }

  if(is_page_template('tpl__service.php')){
    $classes[] = 'service-cat-page';
  }


  $classes[] = 'page-' . ICL_LANGUAGE_CODE;

	return $classes;
}

function icl_post_languages(){
  $languages = icl_get_languages();
  $langs = '';
  foreach($languages as $l){
    if(!$l['active']){
      $langs .= '<li><a href="'.$l['url'].'">'.$l['tag'].'</a></li>';
    }else{
      $langs .= '<li class="active"><span>'.$l['tag'].'</span></li>';
    }
  }
  echo $langs;

}

function icl_post_languages__count(){
  $languages = icl_get_languages();
  return count($languages);
}

function lang_page_original_id($id){

  if(function_exists('icl_object_id')) {
    //return icl_object_id($id,'page', false, "MAIN LANGUAGE CODE EX: SV");
    return icl_object_id($id,'page', false);
  } else {
    return $id;
  }
}
function icl($id, $type){

  if(function_exists('icl_object_id')) {
    //return icl_object_id($id,'page', false, "MAIN LANGUAGE CODE EX: SV");
    return icl_object_id($id, $type, false);
  } else {
    return $id;
  }
}
function lang_category_original_id($id){

  if(function_exists('icl_object_id')) {
    //return icl_object_id($id,'page', false, "MAIN LANGUAGE CODE EX: SV");
    return icl_object_id($id,'post_category', false);
  } else {
    return $id;
  }
}


function get__lang__path(){
  $lang__path = '';
  switch(ICL_LANGUAGE_CODE){
    case 'en':
      $lang__path = 'en/';
      break;
    case 'ru':
      $lang__path = '';
      break;
  }
  return $lang__path;
}

function get__lang__cat(){
  $lang__path = '';
  switch(ICL_LANGUAGE_CODE){
    case 'en':
      $lang__path = '-en';
      break;
    case 'ru':
      $lang__path = '';
      break;
  }
  return $lang__path;
}

/**
 * Catalogue tree helpers.
 *
 * "Categories" on this site are a hierarchical CPT named `category` (registered through CPT UI),
 * not a taxonomy, so the tree is walked by post_parent. These helpers centralise that access:
 * the catalogue root id and the hidden-category list each live in exactly one place.
 *
 * WPML: pass the POST TYPE name ('category') to icl_object_id(), not 'post_category'.
 * lang_category_original_id() above passes 'post_category' and is a silent no-op — don't copy it.
 */

if ( ! function_exists( 'mdt_catalog_root_id' ) ) {
  function mdt_catalog_root_id() {
    return icl( 522, 'category' );
  }
}

/**
 * Categories that still resolve by URL but are kept out of every listing
 * (nav, homepage grid, footer, child grids).
 */
if ( ! function_exists( 'mdt_hidden_category_ids' ) ) {
  function mdt_hidden_category_ids() {
    $hidden = array();
    foreach ( array( 13209 ) as $id ) {
      $translated = icl( $id, 'category' );
      if ( $translated ) {
        $hidden[] = (int) $translated;
      }
    }
    return $hidden;
  }
}

/**
 * Published children of $parent, in menu_order, minus the hidden ones.
 * WPML scopes the query to the current language on its own, so no id translation happens here.
 *
 * @return WP_Post[]
 */
if ( ! function_exists( 'mdt_category_branch' ) ) {
  function mdt_category_branch( $parent ) {
    $posts = get_posts( array(
      'post_type'          => 'category',
      'post_parent'        => (int) $parent,
      'post_status'        => 'publish',
      'posts_per_page'     => -1,
      'orderby'            => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
      'suppress_filters'   => false,
      'ignore_custom_sort' => true, // post-types-order filters posts_orderby globally
    ) );

    $hidden = mdt_hidden_category_ids();
    if ( ! $hidden ) {
      return $posts;
    }

    return array_values( array_filter( $posts, function ( $post ) use ( $hidden ) {
      return ! in_array( (int) $post->ID, $hidden, true );
    } ) );
  }
}

/** @return WP_Post[] The six top-level catalogue branches. */
if ( ! function_exists( 'mdt_top_level_categories' ) ) {
  function mdt_top_level_categories() {
    return mdt_category_branch( 0 );
  }
}

/** @return WP_Post[] */
if ( ! function_exists( 'mdt_category_children' ) ) {
  function mdt_category_children( $id ) {
    return mdt_category_branch( $id );
  }
}

/**
 * Contact Form 7 id for the current language.
 *
 * The three form slots (order modal, "ask a question", "join the team") used to be picked by a
 * switch(ICL_LANGUAGE_CODE) duplicated in three templates, each with only uk/ru cases and no
 * default — so any third language rendered an empty form. This resolves the uk form through WPML
 * instead, which picks up new languages on its own as soon as a translation of that form exists,
 * and falls back to the uk form rather than to nothing.
 *
 * ru stays an explicit id on purpose: the Russian forms are separate posts that were never
 * trid-linked to their Ukrainian counterparts (6502 and 6510 are even registered as `uk`), so
 * WPML cannot resolve them. Fixing that data is a separate job; passing $ru_id keeps /ru/
 * byte-identical in the meantime.
 */
if ( ! function_exists( 'mdt_cf7_id' ) ) {
  function mdt_cf7_id( $uk_id, $ru_id = 0 ) {
    if ( ICL_LANGUAGE_CODE === 'ru' && $ru_id ) {
      return (int) $ru_id;
    }

    if ( ! function_exists( 'icl_object_id' ) ) {
      return (int) $uk_id;
    }

    $id = icl_object_id( (int) $uk_id, 'wpcf7_contact_form', true );

    return $id ? (int) $id : (int) $uk_id;
  }
}

/**
 * Resolve relationship / post-object field values to the current language.
 *
 * Every translation stores the Ukrainian post ids (the fields are set to "copy" in WPML), and
 * ACFML does not convert them here. Links still looked right because WPML filters the permalink,
 * but get_the_title()/thumbnails/prices read the Ukrainian post — invisible while product names
 * are Latin-only, wrong for the rest ("Armstrong метал" on /en/ and /ru/). Falls back to the
 * stored post when a translation is missing, so no card ever disappears.
 */
function mdt_translate_related_posts( $value ) {
  if ( empty( $value ) || ! function_exists( 'icl_object_id' ) ) {
    return $value;
  }

  $translate = function ( $item ) {
    $post = $item instanceof WP_Post ? $item : get_post( (int) $item );
    if ( ! $post ) {
      return $item;
    }
    $id = (int) icl_object_id( $post->ID, $post->post_type, true );
    if ( ! $id || $id === (int) $post->ID ) {
      return $item;
    }
    return $item instanceof WP_Post ? get_post( $id ) : $id;
  };

  return is_array( $value ) ? array_map( $translate, $value ) : $translate( $value );
}
add_filter( 'acf/format_value/type=relationship', 'mdt_translate_related_posts', 20 );
add_filter( 'acf/format_value/type=post_object', 'mdt_translate_related_posts', 20 );

/**
 * Section landing blocks for catalogue categories (H1, intro, subsection tiles, usage table,
 * brands, projects, call to action) — rendered by parts/category-landing-*.php and filled in per
 * language by tools/content-pages.php. Every block is optional and renders only when filled, so
 * categories that don't use them are unchanged.
 *
 * Registered in code rather than through the ACF admin so it ships with the theme. Text fields are
 * "translate" in WPML (each language keeps its own value); ids and repeater counts are "copy", as
 * they are the same in every language.
 */
function mdt_landing_field_group() {
  $text = array( 'wpml_cf_preferences' => 2 );
  $copy = array( 'wpml_cf_preferences' => 1 );

  return array(
    'key'      => 'group_mdt_landing',
    'title'    => 'Посадкова сторінка розділу',
    'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'category' ) ) ),
    'menu_order' => 50,
    'fields'   => array(
      array( 'key' => 'field_mdt_lp_h1', 'name' => 'lp__h1', 'label' => 'H1 (якщо відрізняється від назви)', 'type' => 'text' ) + $text,
      array( 'key' => 'field_mdt_lp_intro', 'name' => 'lp__intro', 'label' => 'Вступний текст', 'type' => 'textarea', 'new_lines' => 'wpautop',
        'instructions' => 'Показується над плитками підрозділів. Можна використовувати <strong>…</strong> для виділення.' ) + $text,

      array( 'key' => 'field_mdt_lp_products_title', 'name' => 'lp__products-title', 'label' => 'Товари: заголовок', 'type' => 'text',
        'instructions' => 'Якщо порожньо — «Колекції».' ) + $text,
      array( 'key' => 'field_mdt_lp_products', 'name' => 'lp__products', 'label' => 'Товари', 'type' => 'relationship', 'post_type' => array( 'product' ), 'return_format' => 'id', 'filters' => array( 'search' ),
        'instructions' => 'Показуються одразу після вступного тексту. Хлібні крихти товару ведуть на цю категорію.' ) + $copy,

      array( 'key' => 'field_mdt_lp_tiles', 'name' => 'lp__tiles', 'label' => 'Плитки підрозділів', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Додати плитку',
        'instructions' => 'Назва, фото й посилання беруться з обраної категорії. Якщо заповнено, замінює автоматичний список «Колекції».',
        'sub_fields' => array(
          array( 'key' => 'field_mdt_lp_tile_cat', 'name' => 'lp__tile-cat', 'label' => 'Категорія', 'type' => 'post_object', 'post_type' => array( 'category' ), 'return_format' => 'id', 'allow_null' => 1 ) + $copy,
          array( 'key' => 'field_mdt_lp_tile_text', 'name' => 'lp__tile-text', 'label' => 'Опис', 'type' => 'textarea', 'new_lines' => '' ) + $text,
          array( 'key' => 'field_mdt_lp_tile_key', 'name' => 'lp__tile-key', 'label' => 'Ключова характеристика', 'type' => 'text' ) + $text,
        ) ) + $copy,

      array( 'key' => 'field_mdt_lp_use_title', 'name' => 'lp__use-title', 'label' => 'Застосування: заголовок', 'type' => 'text' ) + $text,
      array( 'key' => 'field_mdt_lp_use_col1', 'name' => 'lp__use-col1', 'label' => 'Застосування: назва 1-ї колонки', 'type' => 'text' ) + $text,
      array( 'key' => 'field_mdt_lp_use_col2', 'name' => 'lp__use-col2', 'label' => 'Застосування: назва 2-ї колонки', 'type' => 'text' ) + $text,
      array( 'key' => 'field_mdt_lp_use', 'name' => 'lp__use', 'label' => 'Застосування: рядки', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Додати рядок',
        'sub_fields' => array(
          array( 'key' => 'field_mdt_lp_use_room', 'name' => 'lp__use-room', 'label' => 'Тип приміщення', 'type' => 'text' ) + $text,
          array( 'key' => 'field_mdt_lp_use_solution', 'name' => 'lp__use-solution', 'label' => 'Рекомендовані рішення', 'type' => 'text' ) + $text,
        ) ) + $copy,

      array( 'key' => 'field_mdt_lp_brands_title', 'name' => 'lp__brands-title', 'label' => 'Бренди: заголовок', 'type' => 'text' ) + $text,
      array( 'key' => 'field_mdt_lp_brands', 'name' => 'lp__brands', 'label' => 'Бренди', 'type' => 'relationship', 'post_type' => array( 'suppliers' ), 'return_format' => 'id', 'filters' => array( 'search' ) ) + $copy,

      array( 'key' => 'field_mdt_lp_proj_title', 'name' => 'lp__proj-title', 'label' => 'Проєкти: заголовок', 'type' => 'text' ) + $text,
      array( 'key' => 'field_mdt_lp_proj_text', 'name' => 'lp__proj-text', 'label' => 'Проєкти: вступ', 'type' => 'textarea', 'new_lines' => '' ) + $text,
      array( 'key' => 'field_mdt_lp_proj', 'name' => 'lp__proj', 'label' => 'Проєкти', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Додати проєкт',
        'instructions' => 'Назва, фото й посилання беруться з обраного проєкту. Порожні поля не показуються.',
        'sub_fields' => array(
          array( 'key' => 'field_mdt_lp_proj_post', 'name' => 'lp__proj-post', 'label' => 'Проєкт', 'type' => 'post_object', 'post_type' => array( 'projects' ), 'return_format' => 'id', 'allow_null' => 1 ) + $copy,
          array( 'key' => 'field_mdt_lp_proj_city', 'name' => 'lp__proj-city', 'label' => 'Місто', 'type' => 'text' ) + $text,
          array( 'key' => 'field_mdt_lp_proj_type', 'name' => 'lp__proj-type', 'label' => 'Тип приміщення', 'type' => 'text' ) + $text,
          array( 'key' => 'field_mdt_lp_proj_system', 'name' => 'lp__proj-system', 'label' => 'Система / колекція', 'type' => 'text' ) + $text,
          array( 'key' => 'field_mdt_lp_proj_area', 'name' => 'lp__proj-area', 'label' => 'Площа', 'type' => 'text' ) + $text,
        ) ) + $copy,

      array( 'key' => 'field_mdt_lp_cta_title', 'name' => 'lp__cta-title', 'label' => 'Заклик до дії: заголовок', 'type' => 'text',
        'instructions' => 'Якщо заповнено, блок заклику до дії показується після FAQ з цим текстом замість загального.' ) + $text,
      array( 'key' => 'field_mdt_lp_cta_text', 'name' => 'lp__cta-text', 'label' => 'Заклик до дії: текст', 'type' => 'textarea', 'new_lines' => '' ) + $text,
      array( 'key' => 'field_mdt_lp_cta_btn', 'name' => 'lp__cta-btn', 'label' => 'Заклик до дії: кнопка', 'type' => 'text' ) + $text,
    ),
  );
}
add_action( 'acf/init', function () {
  acf_add_local_field_group( mdt_landing_field_group() );
} );

function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/assets/static/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');


function smartwp_remove_wp_block_library_css(){
  wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'wp-block-library-theme' );
  wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );



function cpt_alm_custom_meta_query($args, $id){

  if(isset($_GET['u'])){
    $args['meta_query'] = array(
      array (
        'key' => 'proj__us-gr',
        'value' => $_GET['u']
      )
    );
  }
	
	if ( isset( $args['tag'] ) ) {
		$u = $args['tag'];
		$args['tag'] = '';
		$args['meta_query'] = array(
		  array (
			'key' => 'proj__us-gr',
			'value' => $u
		  )
		);
	}
	
// 	$args['meta_query'] = array(
//       array (
//         'key' => 'proj__us-gr',
//         'value' => 311
//       )
//     );

  return $args;

}
add_filter( 'cpt_alm_query_args', 'cpt_alm_custom_meta_query', 10, 2);

function change_the_post_not_found_text($id){
  return '';
}
add_filter( 'cpt_alm_post_not_found', 'change_the_post_not_found_text', 10, 1);

?>