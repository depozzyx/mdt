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