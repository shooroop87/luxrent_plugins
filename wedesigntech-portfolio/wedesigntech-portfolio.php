<?php
/**
 * Plugin Name: WeDesignTech Portfolio
 * Description: A simple wordpress plugin designed to implements <strong>Portfolio addon features of WeDesignTech</strong>
 * Version: 1.0.0
 * Author: the WeDesignTech team
 * Author URI: https://wedesignthemes.com/
 * Text Domain: wdt-portfolio
 */

if (! class_exists ( 'WDTPortfolioAddon' )) {

	class WDTPortfolioAddon {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Active Modules
		 */
		public $active_modules = array ();

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

			// Set up non-translatable constants first
			$this->wdt_setup_non_translatable_constants();
			$this->wdt_action_hooks();
			$this->wdt_includes();
			add_action('init', array($this, 'wdt_setup_translatable_constants'), 11);
			add_action('init', array($this, 'wdt_load_modules'), 12);

		}

		/**
		 * Define constant if not already set.
		 */
		public function wdt_define_constants( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Configure Non-Translatable Constants
		 */
		public function wdt_setup_non_translatable_constants()
		{
			$this->wdt_define_constants('WDT_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
			$this->wdt_define_constants('WDT_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
			$this->wdt_define_constants('WDT_PLUGIN_MODULE_PATH', trailingslashit(plugin_dir_path(__FILE__)) . 'modules');
		}

		/**
		 * Configure Translatable Constants
		 */
		public function wdt_setup_translatable_constants()
		{
			$this->wdt_define_constants('WDT_PLUGIN_NAME', esc_html__('WeDesignTech Portfolio Addon', 'wdt-portfolio'));
			$this->wdt_define_constants('WDT_PB_MODULE_DEFAULT_TITLE', sprintf(esc_html__('%1$s', 'wdt-portfolio'), WDT_PLUGIN_NAME));
		}
		/**
		 * Action Hooks
		 */
		public function wdt_action_hooks() {

			add_action ( 'init', array ( $this, 'wdt_init' ) );
			add_action ( 'plugins_loaded', array( $this, 'wdt_plugins_loaded' ) );
			add_filter ( 'theme_page_templates', array ( $this, 'wdt_add_new_page_template' ) );
			add_filter ( 'template_include', array ( $this, 'wdt_view_project_template' ) );

			add_action ( 'admin_menu', array ( $this, 'wdt_configure_admin_menu_first_set' ), 10 );
			add_action ( 'admin_menu', array ( $this, 'wdt_configure_admin_menu_second_set' ), 30 );
			add_action ( 'parent_file', array ( $this, 'wdt_change_active_menu' ) );
		}

		/**
		 * On Init
		 */
		function wdt_init() {

			load_plugin_textdomain ( 'wdt-portfolio', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );

			// Register Dependent Styles & Scripts
				require_once WDT_PLUGIN_PATH . 'script-and-styles.php';

		}

		/**
		 * Plugins Load
		 */
		function wdt_plugins_loaded() {

			// Page Builders
				if( did_action( 'elementor/loaded' ) ) {

					// Scan and Include all available page builders
					if(is_dir(WDT_PLUGIN_PATH . 'page-builders')) {

						$wdt_page_builders = scandir(WDT_PLUGIN_PATH . 'page-builders');
						$wdt_page_builders = array_diff($wdt_page_builders, array('..', '.'));

						if ( did_action( 'elementor/loaded' ) && in_array( 'elementor', $wdt_page_builders ) ) {
							require_once WDT_PLUGIN_PATH . 'page-builders/elementor/register-elementor.php';
						}

					}

				} else {
					add_action ('admin_notices', array( $this, 'wdt_pb_plugin_notice' ) );
					return;
				}

		}

		function wdt_pb_plugin_notice() {

			echo '<div class="updated notice is-dismissible">';
				echo '<p>';
					echo sprintf(esc_html__('%1$s requires %2$s or %3$s plugin to be installed and activated on your site','wdt-portfolio'), '<strong>'.esc_html( WDT_PLUGIN_NAME ).'</strong>', '<strong><a href="https://wordpress.org/plugins/elementor/" target="_blank">'.esc_html__('Elementor Page Builder','wdt-portfolio').'</a></strong>' );
				echo '</p>';
				echo '<button type="button" class="notice-dismiss">';
					echo '<span class="screen-reader-text">'.esc_html__('Dismiss this notice.','wdt-portfolio').'</span>';
				echo '</button>';
			echo '</div>';

		}


		/**
		 * Add Custom Templates to page template array
		 */
		function wdt_add_new_page_template( $templates ) {

			$templates = array_merge (
				$templates,
				array (
					'tpl-single-listing.php'  => esc_html__('Portfolio Listings Single Page Template','wdt-portfolio'),
				)
			);

			return $templates;

		}

		/**
		 * Include Custom Templates page from plugin
		 */
		function wdt_view_project_template( $template ) {

			if( is_singular('page') ) {

				global $post;
				$id = $post->ID;
				$file = get_post_meta( $post->ID, '_wp_page_template', true );

				if( 'tpl-single-listing.php' == $file ) {
					if( ! file_exists( get_stylesheet_directory() . '/tpl-single-listing.php' ) ) {
						$template = WDT_PLUGIN_PATH . 'templates/tpl-single-listing.php';
					}
				}

			}

			return $template;

		}

		/**
		 * Configure admin menu - First Set
		 */
		function wdt_configure_admin_menu_first_set() {

			$listing_singular_label = apply_filters( 'listing_label', 'singular' );
			$listing_plural_label   = apply_filters( 'listing_label', 'plural' );
			$category_title         = sprintf( esc_html__('%1$s Category','wdt-portfolio'), $listing_singular_label );

			add_menu_page( sprintf( esc_html__('Portfolio %1$s','wdt-portfolio'), $listing_plural_label ), esc_html__('Portfolio','wdt-portfolio'), 'edit_posts', 'edit.php?post_type=wdt_listings', '', 'dashicons-index-card', 6 );
			add_submenu_page( 'edit.php?post_type=wdt_listings', $category_title, $category_title, 'edit_posts', 'edit-tags.php?taxonomy=wdt_listings_category&post_type=wdt_listings' );
		}

		/**
		 * Configure admin menu - Second Set
		 */
		function wdt_configure_admin_menu_second_set() {

			$listing_singular_label = apply_filters( 'listing_label', 'singular' );
			$amenity_singular_label = apply_filters( 'amenity_label', 'singular' );

			$category_title = sprintf( esc_html__('%1$s Category','wdt-portfolio'), $listing_singular_label );
			$amenity_title = sprintf( esc_html__('%1$s %2$s','wdt-portfolio'), $listing_singular_label, $amenity_singular_label );

			add_submenu_page( 'edit.php?post_type=wdt_listings', $amenity_title, $amenity_title, 'edit_posts', 'edit-tags.php?taxonomy=wdt_listings_amenity&post_type=wdt_listings' );
			add_submenu_page( 'edit.php?post_type=wdt_listings', 'Settings', 'Settings', 'edit_posts', 'wdt-settings-options', 'wdt_settings_options' );
		}

		/**
		 * Update admin menu
		 */
		function wdt_change_active_menu($parent_file) {

			global $submenu_file, $current_screen;
			$taxonomy = $current_screen->taxonomy;
			if ($taxonomy == 'wdt_listings_category') {
				$submenu_file = 'edit-tags.php?taxonomy=wdt_listings_category&post_type=wdt_listings';
				$parent_file = 'edit.php?post_type=wdt_listings';
			} else if ($taxonomy == 'wdt_listings_amenity') {
				$submenu_file = 'edit-tags.php?taxonomy=wdt_listings_amenity&post_type=wdt_listings';
				$parent_file = 'edit.php?post_type=wdt_listings';
			}
			return $parent_file;

		}

		/**
		 * Action Hooks
		 */
		public function wdt_includes() {

			// Register Custom Post Types
			require_once WDT_PLUGIN_PATH . 'custom-post-types/register-post-types.php';

			// Register Shortcodes
			require_once WDT_PLUGIN_PATH . 'shortcodes/shortcodes-default.php';
			require_once WDT_PLUGIN_PATH . 'shortcodes/shortcodes-singlepage.php';

			// Util files
			require_once WDT_PLUGIN_PATH . 'utils/utils-admin.php';
			require_once WDT_PLUGIN_PATH . 'utils/utils.php';
			require_once WDT_PLUGIN_PATH . 'utils/utils-listings.php';
			require_once WDT_PLUGIN_PATH . 'utils/utils-fields.php';

			// Settings
			require_once WDT_PLUGIN_PATH . 'settings/settings.php';

		}

		/**
		 * Scan & Include Active Modules
		 */
		function wdt_load_modules() {

			if(is_dir(WDT_PLUGIN_MODULE_PATH)) {
				$wdt_modules = scandir(WDT_PLUGIN_MODULE_PATH);
				$wdt_modules = array_diff($wdt_modules, array('..', '.', 'pricing'));
				if(is_array($wdt_modules) && !empty($wdt_modules)) {
					rsort($wdt_modules); // To extend search module class in elementor
					$this->active_modules = $wdt_modules;
					foreach($wdt_modules as $wdt_module) {
						$module_path = WDT_PLUGIN_MODULE_PATH . '/'.$wdt_module.'/register-module.php';
						if(file_exists($module_path)) {
							require_once $module_path;
						}
					}
				}
			}

		}

	}

}


if( !function_exists('wdtportfolio_instance') ) {
	function wdtportfolio_instance() {
		return WDTPortfolioAddon::instance();
	}
}

wdtportfolio_instance();