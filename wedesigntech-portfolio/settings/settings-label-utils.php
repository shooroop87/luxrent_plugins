<?php

function wdt_settings_label_content() {

	$output = '';

	$listing_singular_label = apply_filters( 'listing_label', 'singular' );
	$listing_plural_label = apply_filters( 'listing_label', 'plural' );

	$amenity_singular_label = apply_filters( 'amenity_label', 'singular' );
	$amenity_plural_label = apply_filters( 'amenity_label', 'plural' );

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Singular Label','wdt-portfolio'), $listing_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $output .= '<input id="listing-singular-label" name="wdt[label][listing-singular-label]" type="text" value="'.esc_attr( $listing_singular_label ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('You can replace the "Listing" label as per your requirement.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Plural Label','wdt-portfolio'), $listing_plural_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $output .= '<input id="listing-plural-label" name="wdt[label][listing-plural-label]" type="text" value="'.esc_attr( $listing_plural_label ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('You can replace the "Listings" label as per your requirement.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Singular Label','wdt-portfolio'), $amenity_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $output .= '<input id="amenity-singular-label" name="wdt[label][amenity-singular-label]" type="text" value="'.esc_attr( $amenity_singular_label ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('You can replace the "Amenity" label as per your requirement.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Plural Label','wdt-portfolio'), $amenity_plural_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
	            $output .= '<input id="amenity-plural-label" name="wdt[label][amenity-plural-label]" type="text" value="'.esc_attr( $amenity_plural_label ).'" />';
	            $output .= '<div class="wdt-note">'.esc_html__('You can replace the "Amenities" label as per your requirement.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="custom-button-style wdt-save-options-settings" data-settings="label">'.esc_html__('Save Settings','wdt-portfolio').'</a>';

	$output .= '</form>';

	return $output;

}

echo wdt_settings_label_content();

?>