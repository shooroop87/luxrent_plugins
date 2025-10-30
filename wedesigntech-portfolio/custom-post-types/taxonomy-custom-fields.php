<?php

if( !class_exists('WDTPortfolioTaxonomyCustomFields') ) {

	class WDTPortfolioTaxonomyCustomFields {

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

			add_filter ( 'wdt_taxonomies', array ( $this, 'wdt_update_taxonomies' ), 10, 1 );

			$taxonomies = apply_filters( 'wdt_taxonomies', array () );

			foreach($taxonomies as $taxonomy => $taxonomy_label) {
				add_action ( $taxonomy.'_add_form_fields', array ( $this, 'wdt_add_taxonomy_form_fields' ), 10, 2 );
				add_action ( 'created_'.$taxonomy, array ( $this, 'wdt_save_taxonomy_form_fields' ), 10, 2 );
				add_action ( $taxonomy.'_edit_form_fields', array ( $this, 'wdt_update_taxonomy_form_fields' ), 10, 2 );
				add_action ( 'edited_'.$taxonomy, array ( $this, 'wdt_updated_taxonomy_form_fields' ), 10, 2 );
			}

		}

		function wdt_update_taxonomies($taxonomies) {

			$amenity_singular_label      = apply_filters( 'amenity_label', 'singular' );

			$taxonomies['wdt_listings_category'] = esc_html__('Category','wdt-portfolio');
			$taxonomies['wdt_listings_amenity']  = sprintf( esc_html__('%1$s','wdt-portfolio'), $amenity_singular_label );

			return $taxonomies;

		}

		function wdt_add_taxonomy_form_fields ( $taxonomy ) {

			$listing_singular_label = apply_filters( 'listing_label', 'singular' );

			echo '<div class="form-field term-group">
					<label for="taxonomy-image">'.esc_html__('Image','wdt-portfolio').'</label>
					<div class="wdt-upload-media-items-container">
						<input name="wdt-taxonomy-image-url" type="hidden" class="uploadfieldurl" readonly value=""/>
						<input name="wdt-taxonomy-image-id" type="hidden" class="uploadfieldid" readonly value=""/>
						<input type="button" value="'.esc_html__( 'Add Image','wdt-portfolio').'" class="wdt-upload-media-item-button show-preview with-image-holder" />
						'.wdt_adminpanel_image_preview('').'
					</div>
					<p>'.esc_html__('This image will be used for "Taxonomy" listing or single page shortcodes.','wdt-portfolio').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="taxonomy-icon-image">'.esc_html__('Icon Image','wdt-portfolio').'</label>
					<div class="wdt-upload-media-items-container">
						<input name="wdt-taxonomy-icon-image-url" type="hidden" class="uploadfieldurl" readonly value=""/>
						<input name="wdt-taxonomy-icon-image-id" type="hidden" class="uploadfieldid" readonly value=""/>
						<input type="button" value="'.esc_html__( 'Add Image','wdt-portfolio').'" class="wdt-upload-media-item-button show-preview with-image-holder" />
						'.wdt_adminpanel_image_preview('').'
					</div>
                    <p>'.esc_html__('This icon image will be used for "Taxonomy" listing or single page shortcodes.','wdt-portfolio').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="taxonomy-icon">'.esc_html__('Icon','wdt-portfolio').'</label>
					<input type="text" name="wdt-taxonomy-icon" value="">
                    <p>'.esc_html__('This icon will be used for "Taxonomy" listing or single page shortcodes.','wdt-portfolio').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="taxonomy-icon-color">'.esc_html__( 'Icon Color','wdt-portfolio').'</label>
					<input name="wdt-taxonomy-icon-color" class="wdt-color-field color-picker" data-alpha="true" type="text" value="" />
                    <p>'.esc_html__('This icon color will be used for "Taxonomy" listing or single page shortcodes.','wdt-portfolio').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="taxonomy-background-color">'.esc_html__( 'Background Color','wdt-portfolio').'</label>
					<input name="wdt-taxonomy-background-color" class="wdt-color-field color-picker" data-alpha="true" type="text" value="" />
                    <p>'.esc_html__('This background color will be used for "Taxonomy" listing or single page shortcodes.','wdt-portfolio').'</p>
				</div>';

		}

		function wdt_save_taxonomy_form_fields ( $term_id, $tt_id ) {

			if( isset( $_POST['wdt-taxonomy-image-url'] ) ){
				$image_url = wdt_sanitize_fields( $_POST['wdt-taxonomy-image-url'] );
				add_term_meta( $term_id, 'wdt-taxonomy-image-url', $image_url, true );
			}

			if( isset( $_POST['wdt-taxonomy-image-id'] ) ){
				$image_id = wdt_sanitize_fields( $_POST['wdt-taxonomy-image-id'] );
				add_term_meta( $term_id, 'wdt-taxonomy-image-id', $image_id, true );
			}

			if( isset( $_POST['wdt-taxonomy-icon-image-url'] ) ){
				$image_url = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon-image-url'] );
				add_term_meta( $term_id, 'wdt-taxonomy-icon-image-url', $image_url, true );
			}

			if( isset( $_POST['wdt-taxonomy-icon-image-id'] ) ){
				$image_id = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon-image-id'] );
				add_term_meta( $term_id, 'wdt-taxonomy-icon-image-id', $image_id, true );
			}

			if( isset( $_POST['wdt-taxonomy-icon'] ) ){
				$icon = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon'] );
				add_term_meta( $term_id, 'wdt-taxonomy-icon', $icon, true );
			}

			if( isset( $_POST['wdt-taxonomy-icon-color'] ) ){
				$icon_color = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon-color'] );
				add_term_meta( $term_id, 'wdt-taxonomy-icon-color', $icon_color, true );
			}

			if( isset( $_POST['wdt-taxonomy-background-color'] ) ){
				$background_color = wdt_sanitize_fields( $_POST['wdt-taxonomy-background-color'] );
				add_term_meta( $term_id, 'wdt-taxonomy-background-color', $background_color, true );
			}

		}

		function wdt_update_taxonomy_form_fields ( $term, $taxonomy ) {

			$listing_singular_label = apply_filters( 'listing_label', 'singular' );

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="taxonomy-image">'.esc_html__('Image','wdt-portfolio').'</label>
					</th>
					<td>';
						$image_url = get_term_meta( $term->term_id, 'wdt-taxonomy-image-url', true );
						$image_id = get_term_meta( $term->term_id, 'wdt-taxonomy-image-id', true );
					echo '<div class="wdt-upload-media-items-container">
							<input name="wdt-taxonomy-image-url" type="hidden" class="uploadfieldurl" readonly value="'.esc_attr( $image_url ).'"/>
							<input name="wdt-taxonomy-image-id" type="hidden" class="uploadfieldid" readonly value="'.esc_attr( $image_id ).'"/>
							<input type="button" value="'.esc_html__( 'Add Image','wdt-portfolio').'" class="wdt-upload-media-item-button show-preview with-image-holder" />
							<input type="button" value="'.esc_html__('Remove Image','wdt-portfolio').'" class="wdt-upload-media-item-reset" />
							'.wdt_adminpanel_image_preview($image_url).'
						</div>
						<p>'.esc_html__('This image will be used for "Taxonomy" shortcodes.','wdt-portfolio').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="taxonomy-icon-image">'.esc_html__('Icon Image','wdt-portfolio').'</label>
					</th>
					<td>';
						$image_url = get_term_meta( $term->term_id, 'wdt-taxonomy-icon-image-url', true );
						$image_id = get_term_meta( $term->term_id, 'wdt-taxonomy-icon-image-id', true );
					echo '<div class="wdt-upload-media-items-container">
							<input name="wdt-taxonomy-icon-image-url" type="hidden" class="uploadfieldurl" readonly value="'.esc_attr( $image_url ).'"/>
							<input name="wdt-taxonomy-icon-image-id" type="hidden" class="uploadfieldid" readonly value="'.esc_attr( $image_id ).'"/>
							<input type="button" value="'.esc_html__( 'Add Image','wdt-portfolio').'" class="wdt-upload-media-item-button show-preview with-image-holder" />
							<input type="button" value="'.esc_html__('Remove Image','wdt-portfolio').'" class="wdt-upload-media-item-reset" />
							'.wdt_adminpanel_image_preview($image_url).'
						</div>
						<p>'.sprintf( esc_html__('This icon image will be used in "Taxonomy" shortcodes, %1$s listing & Maps.','wdt-portfolio'), $listing_singular_label ).'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="taxonomy-icon">'.esc_html__('Icon','wdt-portfolio').'</label>
					</th>
					<td>';
						$icon = get_term_meta ( $term->term_id, 'wdt-taxonomy-icon', true );
						echo '<input type="text" name="wdt-taxonomy-icon" value="'.esc_attr( $icon ).'">
						<p>'.esc_html__('This icon will be used for both "Taxonomy" shortcodes & Maps.','wdt-portfolio').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="taxonomy-icon-color">'.esc_html__('Icon Color','wdt-portfolio').'</label>
					</th>
					<td>';
						$icon_color = get_term_meta ( $term->term_id, 'wdt-taxonomy-icon-color', true );
						echo '<input name="wdt-taxonomy-icon-color" class="wdt-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $icon_color ).'" />
						<p>'.esc_html__('This icon color will be used for both "Taxonomy" shortcodes & Maps.','wdt-portfolio').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="background-color">'.esc_html__('Background Color','wdt-portfolio').'</label>
					</th>
					<td>';
						$background_color = get_term_meta ( $term->term_id, 'wdt-taxonomy-background-color', true );
						echo '<input name="wdt-taxonomy-background-color" class="wdt-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $background_color ).'" />
						<p>'.sprintf( esc_html__('This background color will be used in "Taxonomy" shortcodes, %1$s listing & Maps.','wdt-portfolio'), $listing_singular_label ).'</p>
					</td>
				</tr>';

		}

		function wdt_updated_taxonomy_form_fields ( $term_id, $tt_id ) {

			//Don't update on Quick Edit
			if (defined('DOING_AJAX') ) {
				return $post_id;
			}

			if( isset( $_POST['wdt-taxonomy-image-url'] ) && '' !== $_POST['wdt-taxonomy-image-url'] ){
				$image_url = wdt_sanitize_fields( $_POST['wdt-taxonomy-image-url'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-image-url', $image_url );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-image-url', '' );
			}

			if( isset( $_POST['wdt-taxonomy-image-id'] ) && '' !== $_POST['wdt-taxonomy-image-id'] ){
				$image_id = wdt_sanitize_fields( $_POST['wdt-taxonomy-image-id'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-image-id', $image_id );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-image-id', '' );
			}

			if( isset( $_POST['wdt-taxonomy-icon-image-url'] ) && '' !== $_POST['wdt-taxonomy-icon-image-url'] ){
				$image_url = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon-image-url'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-icon-image-url', $image_url );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-icon-image-url', '' );
			}

			if( isset( $_POST['wdt-taxonomy-icon-image-id'] ) && '' !== $_POST['wdt-taxonomy-icon-image-id'] ){
				$image_id = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon-image-id'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-icon-image-id', $image_id );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-icon-image-id', '' );
			}

			if( isset( $_POST['wdt-taxonomy-icon'] ) && '' !== $_POST['wdt-taxonomy-icon'] ){
				$icon = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-icon',  $icon );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-icon', '' );
			}

			if( isset( $_POST['wdt-taxonomy-icon-color'] ) && '' !== $_POST['wdt-taxonomy-icon-color'] ){
				$icon_color = wdt_sanitize_fields( $_POST['wdt-taxonomy-icon-color'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-icon-color',  $icon_color );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-icon-color', '' );
			}

			if( isset( $_POST['wdt-taxonomy-background-color'] ) && '' !== $_POST['wdt-taxonomy-background-color'] ){
				$background_color = wdt_sanitize_fields( $_POST['wdt-taxonomy-background-color'] );
				update_term_meta ( $term_id, 'wdt-taxonomy-background-color',  $background_color );
			} else {
				update_term_meta ( $term_id, 'wdt-taxonomy-background-color', '' );
			}

		}


	}

	WDTPortfolioTaxonomyCustomFields::instance();

}?>