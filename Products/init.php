<?php
/*
* Plugin Name:       Add Products
* Version:           1.0
* Author:            Ram Gupta
* Author URI:        https://jmbliss.com
* License:           Private
* License URI:       https://jmbliss.com
*/

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

define( 'PRODUCTS_PLUGIN_FILE', __FILE__ );
// Directory Paths
define( 'PRODUCTS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRODUCTS_DIR_PATH', plugin_dir_url( __FILE__ ) );


// include the main plugin class file
require_once( PRODUCTS_PLUGIN_PATH . 'classes/products-main.php' );


if ( class_exists( 'AddproductPlugin' ) ){
	$addproductplugin = new AddproductPlugin();
}
//activation
register_activation_hook( __FILE__, array( $addproductplugin, 'activate') );
//deactivation
register_deactivation_hook( __FILE__, array( $addproductplugin, 'deactivate') );