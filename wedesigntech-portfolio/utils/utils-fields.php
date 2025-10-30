<?php

// Dashboard Features Field
function wdt_listing_features_field($item_id) {

	$output = '';

    $output .= '<div class="wdt-features-box-container">';

    	$output .= '<div class="wdt-features-box-item-holder">';

			$wdt_features_title = $wdt_features_image = $wdt_features_description = '';
			if($item_id > 0) {
				$wdt_features_title = get_post_meta($item_id, 'wdt_features_title', true);
				$wdt_features_image = get_post_meta($item_id, 'wdt_features_image', true);
				$wdt_features_description = get_post_meta($item_id, 'wdt_features_description', true);
			}

			$j = 0;
			if(is_array($wdt_features_title) && !empty($wdt_features_title)) {
				foreach($wdt_features_title as $wdt_feature_title) {
					$image_url = wp_get_attachment_image_src($wdt_features_image[$j], 'full');
					$image_url = isset($image_url[0]) ? $image_url[0] : '';

					$output .= '<div class="wdt-features-box-item">
									<div class="wdt-column wdt-one-half first">
										<input name="wdt_tab_id" class="wdt_tab_id" type="text" value="'.esc_attr($j).'" readonly />
									</div>
									<div class="wdt-column wdt-one-half">
										<input name="wdt_features_title[]" type="text" value="'.esc_attr($wdt_feature_title).'" placeholder="'.esc_attr__('Title','wdt-portfolio').'" />
									</div>
									<div class="wdt-column wdt-one-column">
										<textarea name="wdt_features_description[]" placeholder="'.esc_attr__('Description','wdt-portfolio').'">'.(isset($wdt_features_description[$j]) ? esc_textarea($wdt_features_description[$j]) : '').'</textarea>
									</div>
									<div class="wdt-column wdt-one-column first wdt-upload-media-items-container">
										<input name="wdt_features_image_url" type="text" value="'.esc_url($image_url).'" placeholder="'.esc_attr__('Image','wdt-portfolio').'" class="uploadfieldurl" readonly />
										<input name="wdt_features_image[]" type="hidden" value="'.esc_attr($wdt_features_image[$j]).'" placeholder="'.esc_attr__('Image','wdt-portfolio').'" class="uploadfieldid" readonly />
						                <input type="button" value="'.esc_attr__('Upload','wdt-portfolio').'" class="wdt-upload-media-item-button show-preview" />
						                <input type="button" value="'.esc_attr__('Remove','wdt-portfolio').'" class="wdt-upload-media-item-reset" />
						                '.wdt_adminpanel_image_preview($image_url).'
									</div>
									<div class="wdt-features-box-options">
										<span class="wdt-remove-features"><span class="fas fa-times"></span></span>
					                    <span class="wdt-sort-features"><span class="fas fa-arrows-alt"></span></span>
									</div>
								</div>';
					$j++;
				}
			}

		$output .= '</div>';

		$output .= '<a href="#" class="wdt-add-features-box custom-button-style">'.esc_html__('Add Feature','wdt-portfolio').'</a>';

		$output .= '<div class="wdt-features-box-item-toclone hidden">
						<div class="wdt-column wdt-one-half first">
							<input name="wdt_tab_id" id="wdt_tab_id" type="text" value="" readonly/>
						</div>
						<div class="wdt-column wdt-one-half">
							<input id="wdt_features_title" type="text" placeholder="'.esc_attr__('Title','wdt-portfolio').'" />
						</div>
						<div class="wdt-column wdt-one-column">
							<textarea id="wdt_features_description" placeholder="'.esc_attr__('Description','wdt-portfolio').'"></textarea>
						</div>
						<div class="wdt-column wdt-one-column first wdt-upload-media-items-container">
							<input name="wdt_features_image_url" type="text" placeholder="'.esc_attr__('Image','wdt-portfolio').'" class="uploadfieldurl" readonly />
							<input id="wdt_features_image" type="hidden" placeholder="'.esc_attr__('Image','wdt-portfolio').'" class="uploadfieldid" readonly />
			                <input type="button" value="'.esc_attr__('Upload','wdt-portfolio').'" class="wdt-upload-media-item-button show-preview" />
			                <input type="button" value="'.esc_attr__('Remove','wdt-portfolio').'" class="wdt-upload-media-item-reset" />
			                '.wdt_adminpanel_image_preview('').'
						</div>
						<div class="wdt-features-box-options">
							<span class="wdt-remove-features"><span class="fas fa-times"></span></span>
		                    <span class="wdt-sort-features"><span class="fas fa-arrows-alt"></span></span>
						</div>
					</div>';

    $output .= '</div>';

    return $output;

}

// Dashboard Social Details Field
function wdt_social_details_field($item_id, $item_type) {

	$output = '';

	$sociables = array('fa-dribbble' => 'Dribble', 'fa-flickr' => 'Flickr', 'fa-github' => 'GitHub', 'fa-pinterest' => 'Pinterest', 'fa-stack-overflow' => 'Stack Overflow', 'fa-twitter' => 'Twitter', 'fa-youtube' => 'YouTube', 'fa-android' => 'Android', 'fa-dropbox' => 'Dropbox', 'fa-instagram' => 'Instagram', 'fa-facebook' => 'Facebook', 'fa-google-plus' => 'Google Plus', 'fa-linkedin' => 'LinkedIn', 'fa-tumblr' => 'Tumblr', 'fa-vimeo-square' => 'Vimeo');

	$output .= '<div class="wdt-social-item-details-container">';

			if($item_type == 'user') {
				$wdt_social_items = get_the_author_meta('wdt_user_social_items', $item_id);
				$wdt_social_items = (isset($wdt_social_items) && is_array($wdt_social_items)) ? $wdt_social_items : array ();
			} else {
				$wdt_social_items = get_post_meta($item_id, 'wdt_social_items', true);
				$wdt_social_items = (isset($wdt_social_items) && is_array($wdt_social_items)) ? $wdt_social_items : array ();
			}

			if($item_type == 'user') {
				$wdt_social_items_value = get_the_author_meta('wdt_user_social_items_value', $item_id);
				$wdt_social_items_value = (isset($wdt_social_items_value) && is_array($wdt_social_items_value)) ? $wdt_social_items_value : array ();
			} else {
				$wdt_social_items_value = get_post_meta($item_id, 'wdt_social_items_value', true);
				$wdt_social_items_value = (isset($wdt_social_items_value) && is_array($wdt_social_items_value)) ? $wdt_social_items_value : array ();
			}

			$i = 0;
			foreach($wdt_social_items as $wdt_social_item) {

			    $output .=  '<div class="wdt-social-item-section">';

					$output .=  '<select class="wdt-social-item-list wdt-social-chosen-select" name="wdt_social_items[]">';
						foreach ( $sociables as $sociable_key => $sociable_value ) :
							$s = ($sociable_key == $wdt_social_item) ? 'selected="selected"' : '';
							$v = ucwords ( $sociable_value );
							$output .=  '<option value="'.esc_attr( $sociable_key ).'" '.esc_attr( $s ).'>'.esc_html( $v ).'</option>';
						endforeach;
					$output .=  '</select>';

			        $output .=  '<input class="large" type="text" placeholder="'.esc_attr__('Social Link','wdt-portfolio').'" name="wdt_social_items_value[]" value="'.$wdt_social_items_value[$i].'" />';

					$output .=  '<div class="wdt-social-item-section-options">
						<span class="wdt-remove-social-item"><span class="fas fa-times"></span></span>
						<span class="wdt-sort-features"><span class="fas fa-arrows-alt"></span></span>
					</div>';

			    $output .=  '</div>';

			    $i++;

			}

	$output .=  '</div>';

    $output .=  '<a href="#" class="wdt-add-social-details custom-button-style">'.esc_html__('Add Social Item','wdt-portfolio').'</a>';

    $output .=  '<div id="wdt-social-details-section-to-clone" class="hidden">';

		$output .=  '<select class="wdt-social-item-list">';
			foreach ( $sociables as $key => $value ) :
				$v = ucwords ( $value );
				$output .=  '<option value="'.esc_attr__( $key ).'">'.esc_html( $v ).'</option>';
			endforeach;
		$output .=  '</select>';

        $output .=  '<input class="large" type="text" placeholder="'.esc_attr__('Social Link','wdt-portfolio').'" />';

		$output .=  '<div class="wdt-social-item-section-options">
						<span class="wdt-remove-social-item"><span class="fas fa-times"></span></span>
	                    <span class="wdt-sort-features"><span class="fas fa-arrows-alt"></span></span>
					</div>';

    $output .=  '</div>';

    return $output;

}

// Page Template Field
function wdt_listing_page_template_field($item_id, $admin = false) {

	$listing_singular_label = apply_filters( 'listing_label', 'singular' );

	$output = '';

	$output .= '<div class="wdt-page-template-module-container">';

		$wdt_page_template = get_post_meta($item_id, 'wdt_page_template', true);
		$wdt_page_template = ($wdt_page_template != '') ? $wdt_page_template : 'admin-option';

		$tpl_args = array (
			'post_type'        => 'page',
			'meta_key'         => '_wp_page_template',
			'meta_value'       => 'tpl-single-listing.php',
			'suppress_filters' => 0
		);
		$single_tpl_posts = get_posts($tpl_args);

		$output .= '<select name="wdt_page_template" class="wdt-chosen-select">';

			$output .= '<option value="admin-option" '.selected('admin-option', $wdt_page_template, false ).'>'.esc_html__('Admin Option','wdt-portfolio').'</option>';
			$output .= '<option value="custom-template" '.selected('custom-template', $wdt_page_template, false ).'>'.esc_html__('Custom Template','wdt-portfolio').'</option>';
			$output .= '<option value="default-template" '.selected('default-template', $wdt_page_template, false ).'>'.esc_html__('Default Template','wdt-portfolio').'</option>';

			if(is_array($single_tpl_posts) && !empty($single_tpl_posts)) {
				foreach($single_tpl_posts as $single_tpl_post) {
					$output .= '<option value="'.esc_attr( $single_tpl_post->ID ).'" '.selected($single_tpl_post->ID, $wdt_page_template, false ).'>';
						$output .= esc_html( $single_tpl_post->post_title );
					$output .= '</option>';
				}
			}

		$output .= '</select>';

		if($admin) {
			$output .= '<div class="wdt-note">'.sprintf( esc_html__('If you like to build your %1$s single page by your own choose "Custom Template" else choose one of the predefined templates created using "Portfolio Single Page Template".','wdt-portfolio'), $listing_singular_label ).'</div>';
		} else {
			$output .= '<div class="wdt-note">'.sprintf( esc_html__('If you like to build your %1$s single page by your own choose "Custom Template" else choose one of the predefined templates created using "Portfolio Single Page Template". Get Admin support to build your "Custom Template"','wdt-portfolio'), $listing_singular_label ).'</div>';
		}

	$output .= '</div>';


	return $output;

}
?>