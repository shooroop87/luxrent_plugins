<?php

// Save General Options
add_action( 'wp_ajax_wdt_save_options_settings', 'wdt_save_options_settings' );
add_action( 'wp_ajax_nopriv_wdt_save_options_settings', 'wdt_save_options_settings' );
function wdt_save_options_settings() {

	$settings = wdt_sanitize_fields($_REQUEST['settings']);
	$wdt_settings = get_option('wdt-settings');

	$wdt_settings[$settings] = wdt_sanitize_fields($_REQUEST['wdt'][$settings]);
	$wdt_settings['plugin-status'] = 'activated';

	if (get_option('wdt-settings') != $wdt_settings) {
		if (update_option('wdt-settings', $wdt_settings)) {
			echo esc_html__('Options have been updated successfully!','wdt-portfolio');
		}
	} else {
		echo esc_html__('No changes done!','wdt-portfolio');
	}

	die();
}

// Listing Label
if(!function_exists('wdt_get_listing_label')) {
	function wdt_get_listing_label($label_type) {

	    if($label_type == 'singular') {
	    	$label = wdt_option('label', 'listing-singular-label');
	    }

	    if($label_type == 'plural') {
	    	$label = wdt_option('label', 'listing-plural-label');
	    }


	    return $label;

	}
	add_filter( 'listing_label', 'wdt_get_listing_label', 10, 1 );
}

// Amenity Label
if(!function_exists('wdt_get_amenity_label')) {
	function wdt_get_amenity_label($label_type) {

	    if($label_type == 'singular') {
	    	$label = wdt_option('label','amenity-singular-label');
	    }

	    if($label_type == 'plural') {
	    	$label = wdt_option('label','amenity-plural-label');
	    }

	    return $label;

	}
	add_filter( 'amenity_label', 'wdt_get_amenity_label', 10, 1 );
}


?>