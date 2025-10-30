<?php

if (!class_exists ( 'WDTPortfolioCustomPostTypes' )) {

	class WDTPortfolioCustomPostTypes {

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

			/* Listings Custom Post Type */
			require_once WDT_PLUGIN_PATH . 'custom-post-types/listings-post-type.php';

		}

	}

	WDTPortfolioCustomPostTypes::instance();

}

?>