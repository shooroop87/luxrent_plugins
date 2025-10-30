<?php

function wdt_settings_general_content() {

	$output = '';

	$listing_singular_label  = apply_filters( 'listing_label', 'singular' );

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.sprintf(esc_html__('%1$s Single Page Template','wdt-portfolio'), $listing_singular_label).'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';

				$single_page_template = wdt_option('general','single-page-template');
				$tpl_args = array (
					'post_type'        => 'page',
					'meta_key'         => '_wp_page_template',
					'meta_value'       => 'tpl-single-listing.php',
					'suppress_filters' => 0
				);
				$single_tpl_posts = get_posts($tpl_args);

				$output .= '<select name="wdt[general][single-page-template]" class="wdt-chosen-select">';

					$output .= '<option value="custom-template" '.selected('custom-template', $single_page_template, false ).'>'.esc_html__('Custom Template','wdt-portfolio').'</option>';
					$output .= '<option value="default-template" '.selected('default-template', $single_page_template, false ).'>'.esc_html__('Default Template','wdt-portfolio').'</option>';

					if(is_array($single_tpl_posts) && !empty($single_tpl_posts)) {
						foreach($single_tpl_posts as $single_tpl_post) {
							$output .= '<option value="'.esc_attr( $single_tpl_post->ID ).'" '.selected($single_tpl_post->ID, $single_page_template, false ).'>';
								$output .= esc_html( $single_tpl_post->post_title );
							$output .= '</option>';
						}
					}
				$output .= '</select>';

				$output .= '<div class="wdt-note">'.sprintf( esc_html__('If you like to build your %1$s single page by your own choose "Custom Template" else choose one of the predefined templates created using "Portfolio Single Page Template".','wdt-portfolio'), $listing_singular_label ).'</div>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Restrict Page View Counter Over User IP','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
                $checked = ( 'true' ==  wdt_option('general', 'restrict-counter-overuserip') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  wdt_option('general', 'restrict-counter-overuserip') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="restrict-counter-overuserip" class="wdt-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="restrict-counter-overuserip" class="hidden" type="checkbox" name="wdt[general][restrict-counter-overuserip]" value="true" '.$checked.' />';
	            $output .= '<div class="wdt-note">'.esc_html__( 'YES! to restrict page view counter over user ip address. Second entry from same ip address will be restricted.','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="custom-button-style wdt-save-options-settings" data-settings="general">'.esc_html__('Save Settings','wdt-portfolio').'</a>';

	$output .= '</form>';

	return $output;

}

echo wdt_settings_general_content();

?>