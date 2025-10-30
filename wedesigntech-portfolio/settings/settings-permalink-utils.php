<?php

function wdt_settings_permalink_content() {

	$listing_singular_label = apply_filters( 'listing_label', 'singular' );

	$output = '';

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Slug','wdt-portfolio'), $listing_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $listing_slug = wdt_option('permalink','listing-slug');
	            $output .= '<input id="listing-slug" name="wdt[permalink][listing-slug]" type="text" value="'.esc_attr( $listing_slug ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. listing After change go to Settings > Permalinks and click Save changes.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Category Slug','wdt-portfolio'), $listing_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $wdt_listings_category_slug = wdt_option('permalink','listing-category-slug');
	            $output .= '<input id="listing-category-slug" name="wdt[permalink][listing-category-slug]" type="text" value="'.esc_attr( $wdt_listings_category_slug ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. listing-category After change go to Settings > Permalinks and click Save changes.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Amenity Slug','wdt-portfolio'), $listing_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $wdt_listings_amenity_slug = wdt_option('permalink','listing-amenity-slug');
	            $output .= '<input id="listing-amenity-slug" name="wdt[permalink][listing-amenity-slug]" type="text" value="'.esc_attr( $wdt_listings_amenity_slug ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. listing-amenity After change go to Settings > Permalinks and click Save changes.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';


		$output .= '<div class="wdt-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. courses After change go to Settings > Permalinks and click Save changes.','wdt-portfolio').'</div>';

		$output .= '<div class="wdt-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="custom-button-style wdt-save-options-settings" data-settings="permalink">'.esc_html__('Save Settings','wdt-portfolio').'</a>';

	$output .= '</form>';

	return $output;

}

echo wdt_settings_permalink_content();

?>