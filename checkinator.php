<?php
/**
 * Plugin Name: Checkinator
 * Version: 0.1-alpha
 * Description: Allows an office to use WordPress as a visitor log and check-in form
 * Author: Rob Ward
 * Author URI: https://github.com/rwrobe
 * Text Domain: checkinator
 * Domain Path: /languages
 * @package Checkinator
 */


namespace notne;


/** Set plugin constants */
if ( ! defined( 'CTR_BASE_FILE' ) )
	define( 'CTR_BASE_FILE', __FILE__ );
if ( ! defined( 'CTR_BASE_DIR' ) )
	define( 'CTR_BASE_DIR',  WP_PLUGIN_URL . '/' . dirname( plugin_basename( CTR_BASE_FILE ) ) );
if ( ! defined( 'CTR_PLUGIN_URL' ) )
	define( 'CTR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'CTR_PLUGIN_PATH' ) )
	define( 'CTR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/** Require the plugin classes */
require_once( 'src/class/Visitor.php' ); // Create the visitor CPT
require_once( 'src/class/JSON_Grabinator.php' ); // Decode the JSON and save it in wp_options
require_once( 'src/class/Page_Makinator.php' ); // Create the /visit/ page on activation including template/JS
require_once( 'checkinator-admin.php' ); // The admin log
require_once( 'src/utility.php' ); // Form processing and cron job

/**
 * Remaining tasks:
 * @todo: Style the form better
 * @todo: Style the backend log
 * @todo: Make form submit via AJAX, and have messages appear using JS vs. PHP
 * @todo: Implement a PDR redirect (instead of currently reloading the page) @see https://en.wikipedia.org/wiki/Post/Redirect/Get
 * @todo: Create tests
 * @todo: Add plugin settings to adjust number of visitors per day
 */