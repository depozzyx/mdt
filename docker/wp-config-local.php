<?php
/**
 * Local Docker override for wp-config.php.
 * Mounted over the real wp-config.php inside the wordpress container only —
 * the tracked wp-config.php (production credentials) is never modified.
 */

define( 'DB_NAME', 'mtgroup_new' );
define( 'DB_USER', 'wordpress' );
define( 'DB_PASSWORD', 'wordpress' );
define( 'DB_HOST', 'db' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',         'local-dev-auth-key' );
define( 'SECURE_AUTH_KEY',  'local-dev-secure-auth-key' );
define( 'LOGGED_IN_KEY',    'local-dev-logged-in-key' );
define( 'NONCE_KEY',        'local-dev-nonce-key' );
define( 'AUTH_SALT',        'local-dev-auth-salt' );
define( 'SECURE_AUTH_SALT', 'local-dev-secure-auth-salt' );
define( 'LOGGED_IN_SALT',   'local-dev-logged-in-salt' );
define( 'NONCE_SALT',       'local-dev-nonce-salt' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );
ini_set( 'display_errors', '0' );
define( 'WPCF7_AUTOP', false );

// Override the site URL baked into the imported production DB so local testing works.
define( 'WP_HOME', 'http://localhost:8080' );
define( 'WP_SITEURL', 'http://localhost:8080' );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

require_once( ABSPATH . 'wp-settings.php' );
