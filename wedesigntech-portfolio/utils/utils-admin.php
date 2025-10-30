<?php

if( !function_exists( 'wdt_adminpanel_image_preview' ) ){
	function wdt_adminpanel_image_preview($src) {

		$default = WDT_PLUGIN_URL.'assets/images/backend/no-image.jpg';
		$src = !empty($src) ? $src : $default;

		$output = '';

		$output .= '<div class="wdt-image-preview-holder">';
			$output .= '<a href="#" class="wdt-image-preview" onclick="return false;">';
				$output .= '<img src="'.WDT_PLUGIN_URL.'assets/images/backend/image-preview.png" alt="'.esc_attr__('Image Preview','wdt-portfolio').'" title="'.esc_attr__('Image Preview','wdt-portfolio').'" />';
				$output .= '<div class="wdt-image-preview-tooltip">';
					$output .= '<img src="'.esc_url( $src ).'" data-default="'.esc_url( $default ).'"  alt="'.esc_attr__('Image Preview Tooltip','wdt-portfolio').'" title="'.esc_attr__('Image Preview Tooltip','wdt-portfolio').'" />';
				$output .= '</div>';
			$output .= '</a>';
		$output .= '</div>';

		return $output;

	}
}

if( !function_exists( 'wdt_adminpanel_image_holder' ) ){
	function wdt_adminpanel_image_holder($src) {

		$default = WDT_PLUGIN_URL.'assets/images/backend/no-image.jpg';
		$src = !empty($src) ? $src : $default;

		$output = '';

		$output .= '<div class="wdt-image-holder">
			<img src="'.esc_url( $src ).'" data-default="'.esc_url( $default ).'"  alt="'.esc_attr__('Image Preview','wdt-portfolio').'" title="'.esc_attr__('Image Preview','wdt-portfolio').'" />
		</div>';

		return $output;

	}
}
?>