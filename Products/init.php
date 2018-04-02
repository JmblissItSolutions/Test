<?php
/*
* Plugin Name:       Add Products
* Version:           1.0
* Author:            Ram Gupta
* Author URI:        https://jmbliss.com
* License:           Private
* License URI:       https://jmbliss.com
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here' );

class AddproductPlugin
{
	public function __construct() {
		add_action( 'init', array( $this , 'Products_post_type' ) );
		add_action( 'init', array( $this , 'taxonomies_products_post_type' ) );
	}
	public function activate() {
		//generated a CPT
		$this->Products_post_type();
	
		//flush rewrite rules
		flush_rewrite_rules();
	}
	public function deactivate() {
		//flush rewrite rules
		flush_rewrite_rules();
	}

	/*
	Register products post type.
	*/
	public function Products_post_type() {
		$labels = array(
				'name'               => _x( 'Products', 'post type general name' ),
				'singular_name'      => _x( 'Product', 'post type singular name' ),
				'add_new'            => _x( 'Add New', 'Product' ),
				'add_new_item'       => __( 'Add New Product' ),
				'edit_item'          => __( 'Edit Product' ),
				'new_item'           => __( 'New Product' ),
				'all_items'          => __( 'All Products' ),
				'view_item'          => __( 'View Product' ),
				'search_items'       => __( 'Search Products' ),
				'not_found'          => __( 'No products found' ),
				'not_found_in_trash' => __( 'No products found in the Trash' ),
				'parent_item_colon'  => '',
				'menu_name'          => 'Products'
			);
			$args = array(
				'labels'        		=> $labels,
    			'public'        		=> true,
    			'menu_position' 		=> 4,
    			'supports'      		=> array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
    			'has_archive'   		=> true,
				'hierarchical'          => false,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-products',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
			);
			register_post_type( 'products', $args );
	}

	/*
	register taxonomies for products custom post.
	*/
	public function taxonomies_products_post_type() {
			//labels array
 
			$labels = array(
    			'name'              => _x( 'Product Types', 'taxonomy general name' ),
    			'singular_name'     => _x( 'Product  Type', 'taxonomy singular name' ),
    			'search_items'      => __( 'Search Product Types' ),
    			'all_items'         => __( 'All Product Types' ),
    			'parent_item'       => __( 'Parent Product Type' ),
    			'parent_item_colon' => __( 'Parent Product Type:' ),
    			'edit_item'         => __( 'Edit Product Type' ),
    			'update_item'       => __( 'Update Product Type' ),
    			'add_new_item'      => __( 'Add New Product Type' ),
    			'new_item_name'     => __( 'New Product Type' ),
    			'menu_name'         => __( ' Product Types' ),
  				);
 
   			//args array
 
			$args = array(
    			'labels' => $labels,
    			'hierarchical' => true,
 				 );
 
  			register_taxonomy( 'product_type', 'products', $args );

	}
}
if ( class_exists( 'AddproductPlugin' ) ){
	$addproductplugin = new AddproductPlugin();
}
//activation
register_activation_hook( __FILE__, array( $addproductplugin, 'activate') );
//deactivation
register_deactivation_hook( __FILE__, array( $addproductplugin, 'deactivate') );