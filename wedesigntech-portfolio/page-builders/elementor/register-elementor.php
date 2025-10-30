<?php

namespace DTElementor\widgets;

if (! class_exists ( 'WDTPortfolioElementor' )) {

	class WDTPortfolioElementor {

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

		/**
		 * Constructor
		 */
		function __construct() {

			add_action( 'elementor/elements/categories_registered', array( $this, 'wdt_register_category' ) );

			add_action( 'elementor/widgets/register', array( $this, 'wdt_register_widgets' ) );

			add_action( 'elementor/frontend/after_register_styles', array( $this, 'wdt_register_widget_styles' ) );
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'wdt_register_widget_scripts' ) );

			add_action( 'elementor/preview/enqueue_styles', array( $this, 'wdt_preview_styles') );

		}

		/**
		 * Register category
		 * Add plugin category in elementor
		 */
		public function wdt_register_category( $elements_manager ) {

			$elements_manager->add_category(
				'wdt-default-widgets',array(
					'title' => WDT_PB_MODULE_DEFAULT_TITLE,
					'icon'  => 'font'
				)
			);

			// $wdt_modules = wdtportfolio_instance()->active_modules;
			// if(is_array($wdt_modules) && !empty($wdt_modules)) {
			// 	if(in_array('search', $wdt_modules)) {
			// 		$elements_manager->add_category(
			// 			'wdt-searchform-widgets',array(
			// 				'title' => WDT_PB_MODULE_SEARCHFORM_TITLE,
			// 				'icon'  => 'font'
			// 			)
			// 		);
			// 	}
			// }

		}

		/**
		 * Parse Attributes
		 * Parse shortcode attributes
		 */
		public function wdt_parse_shortcode_attrs( $attrs ) {

			$keys_to_filter = array ( 'animation_duration', 'hide_desktop', 'hide_tablet', 'hide_mobile' );

			$attrs_str = '';
			if(is_array($attrs) && !empty($attrs)) {
				foreach($attrs as $attr_key => $attr) {
                    if(!is_array($attr)) {
                        $first_character = substr($attr_key, 0, 1);
                        if($first_character != '_' && !in_array($attr_key, $keys_to_filter)) {
                            $attrs_str .= $attr_key.'="'.$attr.'" ';
                        }
                    }
				}
			}

			return $attrs_str;

		}

		/**
		 * Register widgets
		 */
		public function wdt_register_widgets( $widgets_manager ) {

			$elementor_modules_path = WDT_PLUGIN_PATH . 'page-builders/elementor/widgets/';

			# Default Modules

				require $elementor_modules_path . 'default/class-listings-listing.php';
				$widgets_manager->register( new WDTPortfolioDfListingsListing() );

			# Listing Single Page Modules

				require $elementor_modules_path . 'single-page/featured-image.php';
				$widgets_manager->register( new WDTPortfolioSpFeaturedImage() );

				require $elementor_modules_path . 'single-page/features.php';
				$widgets_manager->register( new WDTPortfolioSpFeatures() );

				require $elementor_modules_path . 'single-page/contact-details.php';
				$widgets_manager->register( new WDTPortfolioSpContactDetails() );

				require $elementor_modules_path . 'single-page/social-links.php';
				$widgets_manager->register( new WDTPortfolioSpSocialLinks() );

				require $elementor_modules_path . 'single-page/utils.php';
				$widgets_manager->register( new WDTPortfolioSpUtils() );

				require $elementor_modules_path . 'single-page/taxonomy.php';
				$widgets_manager->register( new WDTPortfolioSpTaxonomy() );

				require $elementor_modules_path . 'single-page/post-date.php';
				$widgets_manager->register( new WDTPortfolioSpPostDate() );


			# Load Modules Elementor widgets

				$wdt_modules = wdtportfolio_instance()->active_modules;
				if(is_array($wdt_modules) && !empty($wdt_modules)) {
					$search_module_exists = false;
					if(in_array('search', $wdt_modules)) {
						$search_module_exists = true;
					}
					foreach($wdt_modules as $wdt_module) {

						$module_epb_path = WDT_PLUGIN_MODULE_PATH . '/'.$wdt_module.'/page-builders/elementor/';
						$pb_files = glob($module_epb_path.'*.php');

						if(is_array($pb_files) && !empty($pb_files)) {
							foreach($pb_files as $pb_file) {

								$file_base_name = basename($pb_file, '.php');
								$file_base_name = explode('-', $file_base_name);

								if(($file_base_name[0] == 'sf' && $search_module_exists) || ($file_base_name[0] != 'sf')) {

									require $pb_file;

									$class_name = implode('', array_map("ucfirst", $file_base_name));
									$class_name =  'DTElementor\Widgets\WDTPortfolio'.$class_name;

									$widgets_manager->register( new $class_name() );

								}

							}
						}

					}
				}


		}

		/**
		 * Register widgets styles
		 */
		public function wdt_register_widget_styles() {

			wdt_dependent_files_instance()->wdt_register_css_files();

		}


		/**
		 * Register widgets scripts
		 */
		public function wdt_register_widget_scripts() {

			wdt_dependent_files_instance()->wdt_register_js_files();

			# Load Modules Dependent Scripts

				$wdt_modules = wdtportfolio_instance()->active_modules;
				if(is_array($wdt_modules) && !empty($wdt_modules)) {
					foreach($wdt_modules as $wdt_module) {
						$wdt_module = explode('-', $wdt_module);
						$wdt_module = implode('', array_map("ucfirst", $wdt_module));
						$moduleInstance = 'wdt'.$wdt_module.'Module';
						if (class_exists($moduleInstance) && method_exists($moduleInstance, 'wdt_register_dependent_files')) {
							$moduleInstance::wdt_register_dependent_files();
						}
					}
				}

		}


		/**
		 * Editor Preview Style
		 */
		public function wdt_preview_styles() {
		}


	}

}


if( !function_exists('wdtportfolio_elementor_instance') ) {
	function wdtportfolio_elementor_instance() {
		return WDTPortfolioElementor::instance();
	}
}

wdtportfolio_elementor_instance();
?>