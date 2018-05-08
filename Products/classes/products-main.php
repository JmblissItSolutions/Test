<?php
/*
Products Main Class
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AddproductPlugin' ) ){

	class AddproductPlugin
{   
	//magic function
	public function __construct() {
		add_action( 'init', array( $this , 'Products_post_type' ) );
		add_action( 'init', array( $this , 'taxonomies_products_post_type' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'main_css' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_products_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_products_meta') );
		add_shortcode( 'Products' , array( $this, 'products_show' ) );
	}


	// enqueues scripts and styled on the front end		
	public function main_css() {
		wp_enqueue_style( 'public_styles', PRODUCTS_DIR_PATH . 'css/public_styles.css' );
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

	

	//Register products post type.
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
    			'supports'      		=> array( 'title', 'thumbnail'),
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

	
	//register taxonomies for products custom post.
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


			 //Add Meta Box
			public function add_products_meta_boxes( $post_type ) {
				global $wp_meta_boxes;
				$post_type = 'products';
				add_meta_box(
					'products_link',	// $id
					'PRODUCTS URL',	// $title
					array( $this, 'product_meta_link' ),	// $callback
					$post_type,	// $post
					'normal',	// $context
					'low'	// $priority
				);
				
				add_meta_box(
					'products_bg',	// $id
					'PRODUCTS BACKGROUND COLOR',	// $title
					array( $this, 'product_meta_bgcolr' ),	// $callback
					$post_type,	// $post
					'normal',	// $context
					'low'	// $priority
				);
			}


			/* 
			* Display META Admin
			*/
			public function product_meta_link() {
				global $post;
				wp_nonce_field( basename( __FILE__ ), 'products_nonce' );
				
				$prod_url = get_post_meta( $post->ID, 'prod_url', true );
				
				echo '<input type="url" size="100" name="prod_url" value="'. wp_strip_all_tags( $prod_url ). '">';
				
			}


			public function product_meta_bgcolr() {
				global $post;
				wp_nonce_field( basename( __FILE__ ), 'products_nonce' );
		
				$prod_bgcolr = get_post_meta( $post->ID, 'prod_bgcolr', true );
				
				echo '<input type="color" name="prod_bgcolr" value="'. wp_strip_all_tags( $prod_bgcolr ). '">';
				
			}


			/*
			* Save Meta fields
			*/
			public function save_products_meta( $post_id ) {

				if ( ! isset( $_POST['prod_url'] ) || ! isset( $_POST['prod_bgcolr'] ) || ! wp_verify_nonce( $_POST['products_nonce'], basename(__FILE__) ) ) {
					return $post_id;
				}
				// This sanitizes the field 
				$prod_url = wp_strip_all_tags( $_POST['prod_url'] );
				$prod_bgcolr = wp_strip_all_tags( $_POST['prod_bgcolr'] );
				// Update Post Meta 
				update_post_meta( $post_id, 'prod_url', $prod_url );
				update_post_meta( $post_id, 'prod_bgcolr', $prod_bgcolr );
			}

			/*
			* Display Products Shortcode
			*/
			public function products_show(){

				$args_post = array(
				'post_type' => 'products',
				'posts_per_page' => -1,
				'order' => 'ASC'
				);
			
				$post_query  = new WP_Query( $args_post );

				if ( $post_query->have_posts() ) {

					$list .= '<div class="product_wrapper">';
					while ( $post_query->have_posts() ) : 
					$post_query->the_post(); 

					$list .= '<div class="product_item" > 
								<a href="'.get_post_meta( get_the_ID(), 'prod_url', true).
							'">
					             <div style="background: '.get_post_meta( get_the_ID(), 'prod_bgcolr', true).';" class="bg-colr-box">
					                <div class="prod_img">
					                  '.get_the_post_thumbnail( $post_id, 'full' ).'
					                  </div>
					                  </div>
					                <div class="prod_title">
					                <h5>'.get_the_title().'</h5>
					                </div></a></div>';
					endwhile;


					$list .= '</div>';
				}
				else{
					$list .= '<h2>No posts found</h2>';
				}
				wp_reset_postdata();
				return $list;
			}


} // Class End

	}
