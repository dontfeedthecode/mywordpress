<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

$environment_config = array();

// Environment check
switch ( $_SERVER['SERVER_NAME'] )
{
	// Local.
	case 'localhost':
	case 'LOCAL SERVER NAME: website.dev':
		$environment_config = require 'wp-config-local.php';
		break;
	
	// Staging.
	case 'STAGING SITE SERVER NAME: website.dev.maikeldaloo.com':
		$environment_config = require 'wp-config-staging.php';
		break;
	
	// Production.
	case 'PRODUCTION SITE SERVER NAME: website.com':
	case 'PRODUCTION SITE SERVER NAME: www.website.com':
		$environment_config = require 'wp-config-production.php';
		break;
	
	default:
		die( 'Undefined environment: Please check the main config file.' );
}

/**
 * Define all the defaults for all the constants that are about
 * to get defined and allow the environment configuration items
 * to overwrite these values.
 * 
 * Do not modify these values from here. Use the environment-specific
 * config files - unless it's a global change for all environments.
 */
$sparky_config_defaults = array(
	// Define the HOME and SITEURL constants to be dynamic.
	'WP_HOME'                => 'http://' . $_SERVER['HTTP_HOST'],
	'WP_SITEURL'             => 'http://' . $_SERVER['HTTP_HOST'],
	
	// Do not allow theme editing from WP admin.
	'DISALLOW_FILE_EDIT'     => true,
	
	// Do not allow plugins/themes to be updated/installed.
	'DISALLOW_FILE_MODS'     => true,
	
	// Completely disable the auto-update feature in WordPress 3.7 and newer.
	'AUTOMATIC_UPDATER_DISABLED' => true,
	
	// Database settings.
	'DB_NAME'                => '',
	'DB_USER'                => '',
	'DB_PASSWORD'            => '',
	'DB_HOST'                => '',
	'DB_CHARSET'             => 'utf8',
	'DB_COLLATE'             => '',
	
	// SMTP Details for email sending. These settings are applied when
	// at least the SMTP host is provided.
	'SMTP_HOST' => '',
	'SMTP_USER' => '',
	'SMTP_PASS' => '',
	'SMTP_PORT' => 587,
	'SMTP_AUTH' => true,
	
	// WordPress Debug mode.
	'WP_DEBUG'               => false,
	
	// Limit the post/page revisions to reduce DB bloat.
	'WP_POST_REVISIONS'      => 5
);

// Merge the environment config items with the defaults.
$sparky_config = array_merge( $sparky_config_defaults , $environment_config );

// Define all the configuration items.
foreach ( $sparky_config as $config_setting => $config_value )
{
	define( $config_setting , $config_value );
}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

if ( !AUTH_KEY || AUTH_KEY == 'put your unique phrase here' )
{
	die( 'Please update your secret keys.<br><a href="https://api.wordpress.org/secret-key/1.1/salt/" target="_blank">Click here</a>' );
}

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'md_';

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
