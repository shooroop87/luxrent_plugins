<?php

if( !class_exists('WDTPortfolioListingsPostType') ) {

	class WDTPortfolioListingsPostType {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {

			add_action ( 'init', array ( $this, 'wdt_init' ) );
			add_action ( 'admin_notices', array ( $this, 'wdt_save_post_admin_notices') );

			add_action ( 'admin_init', array ( $this, 'wdt_admin_init' ) );
			add_filter ( 'template_include', array ( $this, 'wdt_template_include'  ) );

		}

		function wdt_init() {

			$this->createPostType();
			add_action ( 'save_post', array ( $this, 'wdt_save_post_meta' ) );

			/* Taxomony custom fields */
			require_once WDT_PLUGIN_PATH . 'custom-post-types/taxonomy-custom-fields.php';

		}

		function wdt_save_post_admin_notices() {

			if(get_option('wdt_savepost_adminnotices')) {

				$class = 'notice notice-error';
				$message = get_option('wdt_savepost_adminnotices');

				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

				delete_option('wdt_savepost_adminnotices');

			}

		}

		function createPostType() {

			$listing_slug = trim(wdt_option('permalink', 'listing-slug'));
			$listing_category_slug = trim(wdt_option('permalink','listing-category-slug'));
			$listing_contracttype_slug = trim(wdt_option('permalink','listing-contracttype-slug'));
			$listing_amenity_slug = trim(wdt_option('permalink','listing-amenity-slug'));

			$listing_singular_label = apply_filters( 'listing_label', 'singular' );
			$listing_plural_label = apply_filters( 'listing_label', 'plural' );

			$amenity_singular_label = apply_filters( 'amenity_label', 'singular' );
			$amenity_plural_label = apply_filters( 'amenity_label', 'plural' );

			$labels = array (
				'name'               => sprintf( esc_html__('%1$s','wdt-portfolio'), $listing_plural_label ),
				'all_items'          => sprintf( esc_html__('All %1$s','wdt-portfolio'), $listing_plural_label ),
				'singular_name'      => sprintf( esc_html__('%1$s','wdt-portfolio'), $listing_singular_label ),
				'add_new'            => esc_html__( 'Add New','wdt-portfolio'),
				'add_new_item'       => sprintf( esc_html__('Add New %1$s','wdt-portfolio'), $listing_singular_label ),
				'edit_item'          => sprintf( esc_html__('Edit %1$s','wdt-portfolio'), $listing_singular_label ),
				'new_item'           => sprintf( esc_html__('New %1$s','wdt-portfolio'), $listing_singular_label ),
				'view_item'          => sprintf( esc_html__('View %1$s','wdt-portfolio'), $listing_singular_label ),
				'search_items'       => sprintf( esc_html__('Search %1$s','wdt-portfolio'), $listing_plural_label ),
				'not_found'          => sprintf( esc_html__('No %1$s found','wdt-portfolio'), $listing_plural_label ),
				'not_found_in_trash' => sprintf( esc_html__('No %1$s found in Trash','wdt-portfolio'), $listing_plural_label ),
				'parent_item_colon'  => sprintf( esc_html__('Parent %1$s:','wdt-portfolio'), $listing_singular_label ),
				'menu_name'          => sprintf( esc_html__('%1$s','wdt-portfolio'), $listing_plural_label ),
			);

			$args = array (
				'labels'       => $labels,
				'hierarchical' => false,
				'description'  => sprintf( esc_html__('This is custom post type %1$s','wdt-portfolio'), strtolower($listing_plural_label) ),
				'supports'     => array (
					'title',
					'editor',
					'excerpt',
					'author',
					'comments',
					'page-attributes',
					'thumbnail',
					'revisions'
				),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'edit.php?post_type=wdt_listings',
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => true,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array (
					'slug'         => $listing_slug,
					'hierarchical' => true,
					'with_front'   => false
				),
				'capability_type' => 'post',
				'map_meta_cap'    => true,
				'capabilities'    => array (
					// meta caps (don't assign these to roles)
					'edit_post'   => 'edit_wdt_listing',
					'read_post'   => 'read_wdt_listing',
					'delete_post' => 'delete_wdt_listing',

					// primitive/meta caps
					'create_posts' => 'create_wdt_listings',

					// primitive caps used outside of map_meta_cap()
					'edit_posts'         => 'edit_wdt_listings',
					'edit_others_posts'  => 'edit_others_wdt_listings',
					'publish_post'       => 'publish_wdt_listings',
					'read_private_posts' => 'read_private_wdt_listings',

					// primitive caps used inside of map_meta_cap()
					'read'                   => 'read',
					'delete_posts'           => 'delete_wdt_listings',
					'delete_private_posts'   => 'delete_private_wdt_listings',
					'delete_published_posts' => 'delete_published_wdt_listings',
					'delete_others_posts'    => 'delete_others_wdt_listings',
					'edit_private_posts'     => 'edit_private_wdt_listings',
					'edit_published_posts'   => 'edit_published_wdt_listings'
				)
			);

			register_post_type ( 'wdt_listings', $args );

			register_taxonomy ( 'wdt_listings_category', array ( 'wdt_listings' ), array (
				'hierarchical' => true,
				'labels'       => array(
					'name' 					=> sprintf( esc_html__('%1$s Categories','wdt-portfolio'), $listing_singular_label ),
					'singular_name' 		=> sprintf( esc_html__('%1$s Category','wdt-portfolio'), $listing_singular_label ),
					'search_items'			=> sprintf( esc_html__('Search %1$s Categories','wdt-portfolio'), $listing_singular_label ),
					'popular_items'			=> sprintf( esc_html__('Popular %1$s Categories','wdt-portfolio'), $listing_singular_label ),
					'all_items'				=> sprintf( esc_html__('All %1$s Categories','wdt-portfolio'), $listing_singular_label ),
					'parent_item'			=> sprintf( esc_html__('Parent %1$s Category','wdt-portfolio'), $listing_singular_label ),
					'parent_item_colon'		=> sprintf( esc_html__('Parent %1$s Category','wdt-portfolio'), $listing_singular_label ),
					'edit_item'				=> sprintf( esc_html__('Edit %1$s Category','wdt-portfolio'), $listing_singular_label ),
					'update_item'			=> sprintf( esc_html__('Update %1$s Category','wdt-portfolio'), $listing_singular_label ),
					'add_new_item'			=> sprintf( esc_html__('Add New %1$s Category','wdt-portfolio'), $listing_singular_label ),
					'new_item_name'			=> sprintf( esc_html__('New %1$s Category','wdt-portfolio'), $listing_singular_label ),
					'add_or_remove_items'	=> sprintf( esc_html__('Add or remove','wdt-portfolio'), $listing_singular_label ),
					'choose_from_most_used'	=> sprintf( esc_html__('Choose from most used','wdt-portfolio'), $listing_singular_label ),
					'menu_name'				=> sprintf( esc_html__('%1$s Categories','wdt-portfolio'), $listing_singular_label ),
				),
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'         => $listing_category_slug,
					'hierarchical' => true,
					'with_front'   => false
				),
				'query_var'         => true
			) );

			register_taxonomy ( 'wdt_listings_amenity', array ( 'wdt_listings' ), array (
				'hierarchical' => false,
				'labels'       => array(
					'name' 					=> sprintf( esc_html__('%1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_plural_label ),
					'singular_name' 		=> sprintf( esc_html__('%1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'search_items'			=> sprintf( esc_html__('Search %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_plural_label ),
					'popular_items'			=> sprintf( esc_html__('Popular %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_plural_label ),
					'all_items'				=> sprintf( esc_html__('All %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_plural_label ),
					'parent_item'			=> sprintf( esc_html__('Parent %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'parent_item_colon'		=> sprintf( esc_html__('Parent %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'edit_item'				=> sprintf( esc_html__('Edit %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'update_item'			=> sprintf( esc_html__('Update %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'add_new_item'			=> sprintf( esc_html__('Add New %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'new_item_name'			=> sprintf( esc_html__('New %1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'add_or_remove_items'	=> sprintf( esc_html__('Add or remove','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'choose_from_most_used'	=> sprintf( esc_html__('Choose from most used','wdt-portfolio'), $listing_singular_label, $amenity_singular_label ),
					'menu_name'				=> sprintf( esc_html__('%1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_plural_label ),
				),
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'         => $listing_amenity_slug,
					'hierarchical' => true,
					'with_front'   => false
				),
				'query_var'         => true
			) );

		}

		function wdt_save_post_meta($post_id) {

			if( key_exists ( '_inline_edit', $_POST )) :
				if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) return;
			endif;

			if( key_exists( 'wdt_listings_meta_nonce',$_POST ) ) :
				if ( ! wp_verify_nonce( $_POST['wdt_listings_meta_nonce'], 'wdt_listings_nonce' ) ) return;
			endif;

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

			if (!current_user_can('edit_post', $post_id)) :
				return;
			endif;

			if ( (key_exists('post_type', $_POST)) && ('wdt_listings' == $_POST['post_type']) ) :

				$author_id = get_post_field( 'post_author', $post_id );
				$user_id   = get_current_user_id();

				// General
				if( isset( $_POST ['wdt_page_template'] ) && $_POST ['wdt_page_template'] != '') {
					update_post_meta ( $post_id, 'wdt_page_template', wdt_sanitize_fields ( $_POST ['wdt_page_template'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_page_template' );
				}

				if((int)$author_id == (int)$user_id) {

					if( isset( $_POST ['wdt_featured_item'] ) && $_POST ['wdt_featured_item'] != '') {
						update_post_meta ( $post_id, 'wdt_featured_item', sanitize_key ( $_POST ['wdt_featured_item'] ) );
					} else {
						delete_post_meta ( $post_id, 'wdt_featured_item' );
					}

				}

				if( isset( $_POST ['wdt_excerpt_title'] ) && $_POST ['wdt_excerpt_title'] != '') {
					update_post_meta ( $post_id, 'wdt_excerpt_title', wdt_sanitize_fields ( $_POST ['wdt_excerpt_title'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_excerpt_title' );
				}

				// Features

				if( isset( $_POST ['wdt_features_title'] ) && $_POST ['wdt_features_title'] != '') {
					update_post_meta ( $post_id, 'wdt_features_title', wdt_sanitize_fields( $_POST ['wdt_features_title'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_features_title' );
				}
				if( isset( $_POST ['wdt_features_description'] ) && $_POST ['wdt_features_description'] != '') {
					update_post_meta ( $post_id, 'wdt_features_description', wdt_sanitize_fields( $_POST ['wdt_features_description'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_features_description' );
				}

				if( isset( $_POST ['wdt_features_image'] ) && $_POST ['wdt_features_image'] != '') {
					update_post_meta ( $post_id, 'wdt_features_image', wdt_sanitize_fields( $_POST ['wdt_features_image'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_features_image' );
				}


				// Contact Information

				if( isset( $_POST ['wdt_email'] ) && $_POST ['wdt_email'] != '') {
					update_post_meta ( $post_id, 'wdt_email', sanitize_email( $_POST ['wdt_email'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_email' );
				}

				if( isset( $_POST ['wdt_phone'] ) && $_POST ['wdt_phone'] != '') {
					update_post_meta ( $post_id, 'wdt_phone', wdt_sanitize_fields( $_POST ['wdt_phone'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_phone' );
				}

				if( isset( $_POST ['wdt_mobile'] ) && $_POST ['wdt_mobile'] != '') {
					update_post_meta ( $post_id, 'wdt_mobile', wdt_sanitize_fields( $_POST ['wdt_mobile'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_mobile' );
				}

				if( isset( $_POST ['wdt_website'] ) && $_POST ['wdt_website'] != '') {
					update_post_meta ( $post_id, 'wdt_website', wdt_sanitize_fields( $_POST ['wdt_website'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_website' );
				}
				if( isset( $_POST ['wdt_location'] ) && $_POST ['wdt_location'] != '') {
					update_post_meta ( $post_id, 'wdt_location', wdt_sanitize_fields( $_POST ['wdt_location'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_location' );
				}

				if( isset( $_POST ['wdt_social_items'] ) && $_POST ['wdt_social_items'] != '') {
					update_post_meta ( $post_id, 'wdt_social_items', wdt_sanitize_fields( $_POST ['wdt_social_items'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_social_items' );
				}

				if( isset( $_POST ['wdt_social_items_value'] ) && $_POST ['wdt_social_items_value'] != '') {
					update_post_meta ( $post_id, 'wdt_social_items_value', wdt_sanitize_fields( $_POST ['wdt_social_items_value'] ) );
				} else {
					delete_post_meta ( $post_id, 'wdt_social_items_value' );
				}

				// Add or Update listing from modules
				do_action('wdt_addorupdate_listing_module', $_POST, $post_id);

			endif;

		}

		function wdt_admin_init() {

			add_action ( 'add_meta_boxes', array ( $this, 'wdt_add_listing_default_metabox' ) );
			add_filter ( 'manage_wdt_listings_posts_columns', array ( $this, 'set_custom_edit_wdt_listings_columns' ) );
			add_action ( 'manage_wdt_listings_posts_custom_column', array ( $this, 'custom_wdt_listings_column' ), 10, 2 );

		}

		function wdt_add_listing_default_metabox() {
			$listing_singular_label = apply_filters( 'listing_label', 'singular' );
			add_meta_box ( 'wdt-listing-default-metabox', sprintf( esc_html__( '%1$s Options','wdt-portfolio'), $listing_singular_label ), array ( $this, 'wdt_listing_default_metabox' ), 'wdt_listings', 'normal', 'default' );
		}

		function wdt_listing_default_metabox() {
			include_once WDT_PLUGIN_PATH . 'custom-post-types/metaboxes/listing-default-metabox.php';
		}

		function set_custom_edit_wdt_listings_columns($columns) {

			$newcolumns = array (
				'cb'                  => '<input type="checkbox" />',
				'wdt_listings_thumb' => esc_html__('Image','wdt-portfolio'),
				'title'               => esc_html__('Title','wdt-portfolio'),
				'author'              => esc_html__('Author','wdt-portfolio')
			);

			$columns = array_merge ( $newcolumns, $columns );

			return $columns;

		}

		function custom_wdt_listings_column($columns, $id) {

			global $post;

			switch ($columns) {

				case 'wdt_listings_thumb':
					$image = wp_get_attachment_image(get_post_thumbnail_id($id), array(75,75));
					if(!empty($image)) {
						echo $image;
					} else {
						echo '<img src="'.esc_url( WDT_PLUGIN_URL . 'assets/images/thumb.png' ).'" alt="'.esc_attr( $id ).'"/>';
					}
				break;

			}

		}

		function wdt_template_include($template) {

			if (is_singular( 'wdt_listings' )) {
				$template = WDT_PLUGIN_PATH . 'custom-post-types/templates/single-wdt_listings.php';
			} elseif (is_tax ( 'wdt_listings_category' )) {
				$template = WDT_PLUGIN_PATH . 'custom-post-types/templates/taxonomy-wdt_listings_category.php';
			} elseif (is_tax ( 'wdt_listings_amenity' )) {
				$template = WDT_PLUGIN_PATH . 'custom-post-types/templates/taxonomy-wdt_listings_amenity.php';
			} elseif (is_post_type_archive('wdt_listings')) {
				$template = WDT_PLUGIN_PATH . 'custom-post-types/templates/archive-wdt_listings.php';
			}

			return $template;

		}

	}

	WDTPortfolioListingsPostType::instance();
}