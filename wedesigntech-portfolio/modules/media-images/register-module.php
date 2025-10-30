<?php

if (!class_exists ( 'WDTPortfolioRegisterMediaImagesModule' )) {

	class WDTPortfolioRegisterMediaImagesModule extends WDTPortfolioAddon {

		private $module_name;
		private $module_url;

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

			$this->wdt_define_constants( 'WDT_MIMAGES_PLUGIN_PATH', WDT_PLUGIN_PATH . 'modules/media-images/' );
			$this->wdt_define_constants( 'WDT_MIMAGES_PLUGIN_URL', WDT_PLUGIN_URL . 'modules/media-images/' );

			add_filter ( 'wdt_metabox_tabs', array ( $this, 'wdt_metabox_tabs_tab' ) );

			add_action ( 'admin_enqueue_scripts', array ( $this, 'wdt_admin_enqueue_scripts' ), 120 );
			add_action ( 'wp_enqueue_scripts', array ( $this, 'wdt_enqueue_scripts' ), 20 );

			add_action ( 'wdt_addorupdate_listing_module', array ( $this, 'wdt_addorupdate_listing_mediaimages_module' ), 10, 2 );

			require_once WDT_MIMAGES_PLUGIN_PATH . 'utils.php';
			require_once WDT_MIMAGES_PLUGIN_PATH . 'shortcodes.php';

		}

		function wdt_metabox_tabs_tab($tabs) {

			$tabs['media-images'] = array (
				'label' => esc_html__('Media - Images','wdt-portfolio'),
				'icon' => 'fas fa-camera-retro',
				'path' => WDT_MIMAGES_PLUGIN_PATH . 'metabox-tab-listing.php'
			);

			return $tabs;

		}

		function wdt_admin_enqueue_scripts() {

			$this->wdt_register_dependent_files();

			$current_screen = get_current_screen();
			if($current_screen->id == 'wdt_listings') {
				wp_enqueue_style ( 'wdt-media-images-fields' );
				wp_enqueue_script ( 'wdt-media-images-fields' );
			}

		}

		function wdt_enqueue_scripts() {

			$this->wdt_register_dependent_files();
			$this->wdt_enqueue_registered_files();

		}

		function wdt_register_dependent_files() {

			wp_register_style ( 'wdt-media-images-frontend', WDT_MIMAGES_PLUGIN_URL . 'assets/media-images-frontend.css', array ( 'fontawesome','wdt-base', 'wdt-common', 'swiper' ) );

			wp_register_script ( 'wdt-media-images-fields', WDT_MIMAGES_PLUGIN_URL . 'assets/fields.js', array ('jquery', 'wdt-fields'), false, true );
			wp_register_script ( 'wdt-media-images-frontend', WDT_MIMAGES_PLUGIN_URL . 'assets/frontend.js', array ('jquery', 'wdt-frontend', 'portfolio-jquery-swiper'), false, true );

		}

		function wdt_enqueue_registered_files() {

			wp_enqueue_style ( 'wdt-media-images-frontend' );

			wp_enqueue_script ( 'wdt-media-images-frontend' );

		}

		function wdt_addorupdate_listing_mediaimages_module($data, $listing_id) {

			extract($data);

			update_post_meta($listing_id, 'wdt_media_images_ids', $wdt_media_attachment_ids);
			update_post_meta($listing_id, 'wdt_media_images_titles', $wdt_media_attachment_titles);

			if(isset($wdt_featured_image_id) && $wdt_featured_image_id != '') {
				set_post_thumbnail($listing_id, $wdt_featured_image_id);
			}

		}

	}

}

if( !function_exists('wdtMediaImagesModule') ) {
	function wdtMediaImagesModule() {
		return WDTPortfolioRegisterMediaImagesModule::instance();
	}
}

wdtMediaImagesModule();

?>