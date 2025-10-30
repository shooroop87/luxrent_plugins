<?php

if( !class_exists('WDTPortfolioDependentFiles') ) {

	class WDTPortfolioDependentFiles {

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

			add_action ( 'admin_enqueue_scripts', array ( $this, 'wdt_admin_enqueue_scripts' ), 100 );
			add_action ( 'wp_enqueue_scripts', array ( $this, 'wdt_enqueue_dependent_files' ), 10 );
			add_action ( 'wp_enqueue_scripts', array ( $this, 'wdt_dequeue_files' ), 999 );

		}

		/**
		 * Admin enqueue scripts
		 */
		function wdt_admin_enqueue_scripts() {

			$current_screen = get_current_screen();

			wp_register_style ( 'fontawesome', WDT_PLUGIN_URL . 'assets/css/all.min.css' );
			wp_register_style ( 'chosen', WDT_PLUGIN_URL . 'assets/css/chosen.css' );
			wp_register_style ( 'wdt-fields', WDT_PLUGIN_URL . 'assets/css/fields.css' );
			wp_register_style ( 'wdt-backend', WDT_PLUGIN_URL . 'assets/css/backend.css' );
			wp_register_style ( 'wdt-common', WDT_PLUGIN_URL . 'assets/css/common.css' );

			wp_register_style ( 'wdt-listing', WDT_PLUGIN_URL . 'assets/css/listing.css' );

			wp_register_script ( 'wp-color-picker-alpha', WDT_PLUGIN_URL . 'assets/js/wp-color-picker-alpha.min.js', array (), false, true );
			wp_register_script ( 'chosen', WDT_PLUGIN_URL . 'assets/js/chosen.jquery.min.js', array ('jquery'), false, true );
			wp_register_script ( 'wdt-tabs', WDT_PLUGIN_URL . 'assets/js/jquery.tabs.min.js', array (), false, true );
			wp_register_script ( 'wdt-fields', WDT_PLUGIN_URL . 'assets/js/fields.js', array ('jquery'), false, true );

			wp_register_script ( 'wdt-common', WDT_PLUGIN_URL . 'assets/js/common.js', array (), false, true );
			wp_localize_script ( 'wdt-common', 'wdtcommonobject', array (
					'ajaxurl'  => admin_url('admin-ajax.php'),
					'noResult' => esc_html__('No Results Found!','wdt-portfolio')
				));

			wp_register_script ( 'wdt-backend', WDT_PLUGIN_URL . 'assets/js/backend.js', array (), false, true );
			wp_localize_script ( 'wdt-backend', 'wdtbackendobject', array (
					'ajaxurl'        => admin_url('admin-ajax.php'),
					'locationAlert1' => esc_html__('To get GPS location please fill address.','wdt-portfolio'),
					'locationAlert2' => esc_html__('Please add latitude and longitude','wdt-portfolio'),
					'confirmImport'  => esc_html__('Confirm to import listings','wdt-portfolio')
				));


			// For Taxonomies & Settings

			if(in_array($current_screen->id, array ('edit-wdt_listings_category', 'edit-wdt_listings_amenity', 'portfolio_page_wdt-settings-options'))) {

				// CSS

				wp_enqueue_style ( 'wp-color-picker' );
				wp_enqueue_style ( 'wdt-fields' );

				wp_enqueue_style ( 'wdt-backend' );

				wp_enqueue_style ( 'wdt-common' );
				wp_enqueue_style ( 'wdt-listing' );


				// JS

				wp_enqueue_script ( 'wp-color-picker' );
				wp_enqueue_script ( 'wp-color-picker-alpha' );
				wp_enqueue_script ( 'wdt-fields' );

				wp_enqueue_script ( 'wdt-common' );
				wp_enqueue_script ( 'wdt-backend' );


			}

			// For Listings

			if($current_screen->id == 'wdt_listings') {

				// CSS

				wp_enqueue_style ( 'fontawesome' );
				wp_enqueue_style ( 'chosen' );
				wp_enqueue_style ( 'wdt-fields' );

				wp_enqueue_style ( 'wdt-backend' );
				wp_enqueue_style ( 'wdt-common' );


				// JS

				wp_enqueue_script ( 'chosen' );
				wp_enqueue_script ( 'wdt-tabs' );
				wp_enqueue_script ( 'wdt-fields' );

				wp_enqueue_script ( 'wdt-common' );
				wp_enqueue_script ( 'wdt-backend' );

			}

			//wp_enqueue_media ();

		}

		/**
		 * Frontend - Register CSS Files
		 */
		function wdt_enqueue_dependent_files() {

			$this->wdt_register_css_files();
			$this->wdt_register_js_files();
			$this->wdt_enqueue_registered_files();

		}

		/**
		 * Frontend - Register CSS Files
		 */
		function wdt_register_css_files() {

			wp_register_style ( 'fontawesome', WDT_PLUGIN_URL . 'assets/css/all.min.css' );
			wp_register_style ( 'swiper', WDT_PLUGIN_URL . 'assets/css/swiper.min.css' );
			wp_register_style ( 'wdt-common', WDT_PLUGIN_URL . 'assets/css/common.css' );
			wp_register_style ( 'wdt-base', WDT_PLUGIN_URL . 'assets/css/base.css' );

			wp_register_style ( 'wdt-listing', WDT_PLUGIN_URL . 'assets/css/listing.css' );

			wp_register_style ( 'magnific-popup', WDT_PLUGIN_URL . 'assets/css/jquery.magnific-popup.css' );

			wp_register_style ( 'wdt-modules-listing', WDT_PLUGIN_URL . 'assets/css/modules-listing.css', array ( 'fontawesome', 'wdt-base', 'wdt-common' ) );
			wp_register_style ( 'wdt-modules-default', WDT_PLUGIN_URL . 'assets/css/modules-default.css', array ( 'fontawesome', 'wdt-base', 'wdt-common' ) );
			wp_register_style ( 'wdt-modules-singlepage', WDT_PLUGIN_URL . 'assets/css/modules-singlepage.css', array ( 'fontawesome', 'wdt-base', 'wdt-common' )  );
			

		}

		/**
		 * Frontend - Register JS Files
		 */
		function wdt_register_js_files() {

			wp_register_script ( 'chosen', WDT_PLUGIN_URL . 'assets/js/chosen.jquery.min.js', array ('jquery'), false, true );
			wp_register_script ( 'portfolio-jquery-swiper', WDT_PLUGIN_URL . 'assets/js/swiper.min.js', array ('jquery'), false, true );
			wp_register_script ( 'isotope', WDT_PLUGIN_URL . 'assets/js/isotope.pkgd.min.js', array ('jquery'), false, true);
			wp_register_script ( 'matchheight', WDT_PLUGIN_URL . 'assets/js/matchHeight.js', array(), false, true);
            wp_register_script ( 'wdt-sticky-sidebar', WDT_PLUGIN_URL . 'assets/js/sticky-sidebar.min.js', array ('jquery'), false, true );

			wp_register_script ( 'jquery-cookies', WDT_PLUGIN_URL . 'assets/js/jquery.cookie.min.js', array ('jquery'), false, true );
			wp_register_script ( 'jquery-magnific-popup', WDT_PLUGIN_URL . 'assets/js/jquery.magnific-popup.min.js', array ('jquery'), false, true );

			wp_register_script ( 'wdt-fields', WDT_PLUGIN_URL . 'assets/js/fields.js', array ('jquery', 'jquery-ui-sortable'), false, true );

			wp_register_script ( 'wdt-common', WDT_PLUGIN_URL . 'assets/js/common.js', array ('jquery'), false, true );
			wp_localize_script ( 'wdt-common', 'wdtcommonobject', array (
				'ajaxurl' => admin_url('admin-ajax.php'),
				'noResult' => esc_html__('No Results Found!','wdt-portfolio'),
			));

			$listing_singular_label = apply_filters( 'listing_label', 'singular' );

			$elementor_preview_mode = false;

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('elementor/elementor.php') || is_plugin_active_for_network('elementor/elementor.php')) {  // Elementor Plugin

				if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
					$elementor_preview_mode = true;
				}

			}

			wp_register_script ( 'wdt-frontend', WDT_PLUGIN_URL . 'assets/js/frontend.js', array ('jquery', 'wdt-common'), false, true );
			wp_localize_script ( 'wdt-frontend', 'wdtfrontendobject', array (
				'pluginFolderPath'                 => plugins_url().'/',
				'pluginPath'                       => WDT_PLUGIN_URL,
				'ajaxurl'                          => admin_url('admin-ajax.php'),
				'purchased'                        => '<p>'.esc_html__('Purchased','wdt-portfolio').'</p>',
				'somethingWentWrong'               => '<p>'.esc_html__('Something Went Wrong','wdt-portfolio').'</p>',
				'outputDivAlert'                   => esc_html__('Please make sure you have added output shortcode.','wdt-portfolio'),
				'printerTitle'                     => sprintf( esc_html__('%1$s Printer','wdt-portfolio'), $listing_singular_label ),
				'pleaseLogin'                      => esc_html__('Please login','wdt-portfolio'),
				'noMorePosts'                      => esc_html__('No more posts to load!','wdt-portfolio'),
				'elementorPreviewMode'             => esc_js($elementor_preview_mode),
			));

			wp_register_script ( 'wdt-modules-singlepage', WDT_PLUGIN_URL . 'assets/js/single-page.js', array ('jquery', 'wdt-frontend'), false, true );

		}

		/**
		 * Frontend - Enqueue Registered Files
		 */
		function wdt_enqueue_registered_files() {

			// CSS

				wp_enqueue_style ( 'swiper' );
				wp_enqueue_style ( 'wdt-modules-listing' );
				wp_enqueue_style ( 'wdt-modules-default' );
				if ( is_post_type_archive( 'wdt_listings' ) || is_tax( 'wdt_listings_category' ) || is_tax( 'wdt_listings_amenity' ) ) {
					wp_enqueue_style( 'wdt-listing' );
				}
			// JS

				wp_enqueue_script ( 'portfolio-jquery-swiper' );
				wp_enqueue_script ( 'isotope' );
				wp_enqueue_script ( 'matchheight' );
				wp_enqueue_script ( 'wdt-frontend' );

			// Modulewise

				if (is_singular( 'wdt_listings' )|| is_page_template( 'tpl-single-listing.php' )) {

					wp_enqueue_style ( 'wdt-modules-singlepage' );

					wp_enqueue_script ( 'wdt-sticky-sidebar' );
					wp_enqueue_script ( 'wdt-modules-singlepage' );

				}

		}

		/**
		 * Dequeue Files
		 */
		function wdt_dequeue_files() {
			if(is_singular( 'post' )) {
				global $wp_styles;
				unset($wp_styles->registered['wdt-fields']);
			}
		}

	}

}

if( !function_exists('wdt_dependent_files_instance') ) {
	function wdt_dependent_files_instance() {
		return WDTPortfolioDependentFiles::instance();
	}
}

wdt_dependent_files_instance();

?>