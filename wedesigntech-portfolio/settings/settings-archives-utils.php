<?php

function wdt_settings_archives_content() {

 	$listing_singular_label = apply_filters( 'listing_label', 'singular' );

	$output = '';

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__('Types','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';

				$archive_page_type = wdt_option('archives','archive-page-type');

				$archive_types = array (
					'type1' => esc_html__('Type 1','wdt-portfolio'),
					'type2' => esc_html__('Type 2','wdt-portfolio'),
					'type3' => esc_html__('Type 3','wdt-portfolio')
				);

				$output .= '<select name="wdt[archives][archive-page-type]" class="wdt-chosen-select">';

					if(is_array($archive_types) && !empty($archive_types)) {
						foreach($archive_types as $key => $archive_type) {
							$output .= '<option value="'.esc_attr( $key ).'" '.selected($key, $archive_page_type, false ).'>';
								$output .= esc_html( $archive_type );
							$output .= '</option>';
						}
					}

				$output .= '</select>';

				$output .= '<div class="wdt-note">'.sprintf( esc_html__('Choose type for your %1$s archive pages.','wdt-portfolio'), strtolower($listing_singular_label) ).'</div>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__('Gallery','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
					$archive_page_gallery = wdt_option('archives','archive-page-gallery');
					$archive_galleries = array (
						'featured_image' => esc_html__('Featured Image','wdt-portfolio'),
					);

				$output .= '<select name="wdt[archives][archive-page-gallery]" class="wdt-chosen-select">';

					if(is_array($archive_galleries) && !empty($archive_galleries)) {
						foreach($archive_galleries as $key => $archive_gallery) {
							$output .= '<option value="'.esc_attr( $key ).'" '.selected($key, $archive_page_gallery, false ).'>';
								$output .= esc_html( $archive_gallery );
							$output .= '</option>';
						}
					}

				$output .= '</select>';

				$output .= '<div class="wdt-note">'.sprintf( esc_html__('Choose gallery type for your %1$s archive pages.','wdt-portfolio'), strtolower($listing_singular_label) ).'</div>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__('Columns','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';

				$archive_page_column = wdt_option('archives','archive-page-column');
				$archive_columns     = array (
					1 => esc_html__('I Column','wdt-portfolio'),
					2 => esc_html__('II Columns','wdt-portfolio'),
					3 => esc_html__('III Columns','wdt-portfolio')
				);

				$output .= '<select name="wdt[archives][archive-page-column]" class="wdt-chosen-select">';

					if(is_array($archive_columns) && !empty($archive_columns)) {
						foreach($archive_columns as $key => $archive_column) {
							$output .= '<option value="'.esc_attr( $key ).'" '.selected($key, $archive_page_column, false ).'>';
								$output .= esc_html( $archive_column );
							$output .= '</option>';
						}
					}

				$output .= '</select>';

				$output .= '<div class="wdt-note">'.sprintf( esc_html__('Choose column for your %1$s archive pages.','wdt-portfolio'), strtolower($listing_singular_label) ).'</div>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Apply Isotope','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
				$checked = ( 'true' ==  wdt_option('archives', 'archive-page-apply-isotope') ) ? ' checked="checked"' : '';
				$switchclass = ( 'true' ==  wdt_option('archives', 'archive-page-apply-isotope') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
				$output .= '<div data-for="archive-page-apply-isotope" class="wdt-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
				$output .= '<input id="archive-page-apply-isotope" class="hidden" type="checkbox" name="wdt[archives][archive-page-apply-isotope]" value="true" '.esc_attr( $checked ).' />';
				$output .= '<div class="wdt-note">'.sprintf( esc_html__('If you like to apply isotope for your %1$s archive pages, check this options.','wdt-portfolio'), strtolower($listing_singular_label) ).'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Excerpt Length','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
				$archive_page_excerpt_length = wdt_option('archives','archive-page-excerpt-length');
				$output .= '<input id="archive-page-excerpt-length" name="wdt[archives][archive-page-excerpt-length]" type="number" value="'.esc_attr( $archive_page_excerpt_length ).'" min="1" max="2000" step="1"  />';
				$output .= '<div class="wdt-note">'.sprintf( esc_html__('Provide excerpt length for your %1$s archive pages.','wdt-portfolio'), strtolower($listing_singular_label) ).'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__('Features Image or Icon','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
				$archive_page_features_image_or_icon = wdt_option('archives','archive-page-features-image-or-icon');
				$archive_features_image_or_icons = array (
					''      => esc_html__('None','wdt-portfolio'),
					'image' => esc_html__('Image','wdt-portfolio'),
					'icon'  => esc_html__('Icon','wdt-portfolio')
				);

				$output .= '<select name="wdt[archives][archive-page-features-image-or-icon]" class="wdt-chosen-select">';

					if(is_array($archive_features_image_or_icons) && !empty($archive_features_image_or_icons)) {
						foreach($archive_features_image_or_icons as $key => $archive_features_image_or_icon) {
							$output .= '<option value="'.esc_attr( $key ).'" '.selected($key, $archive_page_features_image_or_icon, false ).'>';
								$output .= esc_html( $archive_features_image_or_icon );
							$output .= '</option>';
						}
					}

				$output .= '</select>';

				$output .= '<div class="wdt-note">'.sprintf( esc_html__('Choose features image or icon to use for your %1$s archive pages.','wdt-portfolio'), strtolower($listing_singular_label) ).'</div>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Features Include','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';
				$archive_page_features_include = wdt_option('archives','archive-page-features-include');
				$output .= '<input id="archive-page-features-include" name="wdt[archives][archive-page-features-include]" type="text" value="'.esc_attr( $archive_page_features_include ).'" />';
				$output .= '<div class="wdt-note">'.esc_html__('Give features id separated by comma. Only 4 maximum number of features allowed','wdt-portfolio').'</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="wdt-settings-options-holder">';
			$output .= '<div class="wdt-column wdt-one-fifth first">';
				$output .= '<label>'.esc_html__('No. Of Categories to Display','wdt-portfolio').'</label>';
			$output .= '</div>';
			$output .= '<div class="wdt-column wdt-four-fifth">';

				$archive_page_noofcat = wdt_option('archives','archive-page-noofcat-to-display');
				$archive_noofcats = array (
					1  => 1,
					2  => 2,
					3  => 3,
					4  => 4
				);

				$output .= '<select name="wdt[archives][archive-page-noofcat-to-display]" class="wdt-chosen-select">';

					if(is_array($archive_noofcats) && !empty($archive_noofcats)) {
						foreach($archive_noofcats as $key => $archive_noofcat) {
							$output .= '<option value="'.esc_attr( $key ).'" '.selected($key, $archive_page_noofcat, false ).'>';
								$output .= esc_html( $archive_noofcat );
							$output .= '</option>';
						}
					}

				$output .= '</select>';

				$output .= '<div class="wdt-note">'.esc_html__( 'Number of categories you like to display on your items.','wdt-portfolio').'</div>';

			$output .= '</div>';
		$output .= '</div>';



		$output .= '<div class="wdt-note">'.esc_html__('This setting is applicable for all archive pages.','wdt-portfolio').'</div>';

		$output .= '<div class="wdt-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="custom-button-style wdt-save-options-settings" data-settings="archives">'.esc_html__('Save Settings','wdt-portfolio').'</a>';

	$output .= '</form>';

	return $output;

}

echo wdt_settings_archives_content();

?>