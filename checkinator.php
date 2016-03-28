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
require_once( 'src/class/Visitor.php' );
require_once( 'src/class/JSON_Grabinator.php' );
require_once( 'src/class/Page_Makinator.php' );
require_once( 'checkinator-admin.php' );
require_once( 'src/utility.php' );