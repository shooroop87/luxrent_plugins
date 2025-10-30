<?php

if( !class_exists('WDTPortfolioSinglePageShortcodes') ) {

	class WDTPortfolioSinglePageShortcodes {

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

			add_shortcode ( 'wdt_sp_featured_image', array ( $this, 'wdt_sp_featured_image' ) );
			add_shortcode ( 'wdt_sp_features', array ( $this, 'wdt_sp_features' ) );
			add_shortcode ( 'wdt_sp_contact_details', array ( $this, 'wdt_sp_contact_details' ) );
			add_shortcode ( 'wdt_sp_social_links', array ( $this, 'wdt_sp_social_links' ) );
			add_shortcode ( 'wdt_sp_utils', array ( $this, 'wdt_sp_utils' ) );
			add_shortcode ( 'wdt_sp_taxonomy', array ( $this, 'wdt_sp_taxonomy' ) );
			add_shortcode ( 'wdt_sp_post_date', array ( $this, 'wdt_sp_post_date' ) );

		}


		function wdt_shortcodeHelper($content = null) {
			$content = do_shortcode ( shortcode_unautop ( $content ) );
			$content = preg_replace ( '#^<\/p>|^<br \/>|<p>$#', '', $content );
			$content = preg_replace ( '#<br \/>#', '', $content );
			return trim ( $content );
		}

		function wdt_sp_featured_image( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id' => '',
				'image_size' => 'full',
				'with_link'  => '',
				'class'      => '',
			), $attrs, 'wdt_sp_featured_image' );


			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				$featured_image_id = get_post_thumbnail_id($attrs['listing_id']);
				$image_details = wp_get_attachment_image_src($featured_image_id, $attrs['image_size']);

                $image_sizes = wp_get_attachment_metadata($featured_image_id);
                $width = (isset($image_sizes['width']) && !empty($image_sizes['width'])) ? 'width="'.$image_sizes['width'].'"' : '';
                $height = (isset($image_sizes['height']) && !empty($image_sizes['height'])) ? 'height="'.$image_sizes['height'].'"' : '';

				$output .= '<div class="wdt-listings-feature-image-holder '.esc_attr( $attrs['class'] ).'">';

					if($attrs['with_link'] == 'true') {
						$output .= '<a href="'.esc_url( get_permalink($attrs['listing_id']) ).'">';
					}
						$output .= '<img src="'.esc_url($image_details[0]).'" title="'.esc_attr__('Featured Image','wdt-portfolio').'" alt="'.esc_attr__('Featured Image','wdt-portfolio').'" '.$width.' '.$height.' />';
					if($attrs['with_link'] == 'true') {
						$output .= '</a>';
					}

				$output .= '</div>';

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

		function wdt_sp_features( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id'             => '',
				'include'                => '',
				'columns'                => 4,
				'features_image_or_icon' => '',
				'class'                  => '',
				'type'                   => '',
			), $attrs, 'wdt_sp_features' );

			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				if($attrs['columns'] == 1) {
					$column_class = 'wdt-column wdt-one-column';
				} else if($attrs['columns'] == 2) {
					$column_class = 'wdt-column wdt-one-half';
				} else if($attrs['columns'] == 3) {
					$column_class = 'wdt-column wdt-one-third';
				} else if($attrs['columns'] == 4) {
					$column_class = 'wdt-column wdt-one-fourth';
				} else if($attrs['columns'] == 5) {
					$column_class = 'wdt-column wdt-one-fifth';
				}
				else if($attrs['columns'] == -1) {
					if($attrs['type'] == 'listing') {
						$column_class = '';
					} else {
						$column_class = '';
						$attrs['class'] .= ' wdt-no-column';
					}
				}

                $wdt_features_title = $wdt_features_image = array ();
                if($attrs['listing_id'] > 0) {
                    $wdt_features_title = get_post_meta($attrs['listing_id'], 'wdt_features_title', true);
                    $wdt_features_image = get_post_meta($attrs['listing_id'], 'wdt_features_image', true);
					$wdt_features_description = get_post_meta($attrs['listing_id'], 'wdt_features_description', true);
                }

                $j = 0; $i = 1;
                if(is_array($wdt_features_title) && !empty($wdt_features_title)) {

                    if($attrs['include'] != '') {
                        $include_keys = explode(',', $attrs['include']);
                    } else {
                        if($attrs['type'] == 'listing') {
                            $include_keys = array_keys($wdt_features_title);
                            array_splice($include_keys, 4);
                        } else {
                            $include_keys = array_keys($wdt_features_title);
                        }
                    }

                    $output .= '<div class="wdt-listings-features-box-container '.esc_attr( $attrs['type'] ).' '.esc_attr( $attrs['class'] ).'">';
                        foreach($wdt_features_title as $wdt_feature_title) {

                            if(in_array($j, $include_keys)) {

                                if($i == 1 && $attrs['columns'] != -1) { $first_class = 'first';  } else { $first_class = ''; }
                                if($i == $attrs['columns']) { $i = 1; } else { $i = $i + 1; }

                                $wdt_features_image_html = $style_attr = '';
                                $image_url = wp_get_attachment_image_src($wdt_features_image[$j], 'full');
                                if($image_url != '') {
                                    $wdt_features_image_html .= ' <div class="wdt-listings-features-box-item-img"  style="background-image:url('.esc_url($image_url[0]).');"></div>';
                                    if($attrs['type'] == 'listing' && $attrs['features_image_or_icon'] == 'image') {
                                        $style_attr .= 'style="background-image:url('.esc_url($image_url[0]).');"';
                                    }
                                }

                                $wdt_features_title_html = '';
                                if(isset($wdt_feature_title) && !empty($wdt_feature_title)) {
                                    $wdt_features_title_html .= '<div class="wdt-listings-features-box-item-title">'.esc_attr($wdt_feature_title).'</div>';
                                }

								$wdt_features_description_html = '';
								if(isset($wdt_features_description[$j]) && !empty($wdt_features_description[$j])) {
									$wdt_features_description_html .= '<div class="wdt-listings-features-box-item-description">'.esc_attr($wdt_features_description[$j]).'</div>';
								}

                                $output .= '<div class="wdt-listings-features-box-item '.esc_attr($column_class).' '.esc_attr($first_class).'" '.$style_attr.'>';

									$output .= $wdt_features_image_html;
									$output .= $wdt_features_title_html;
									$output .= $wdt_features_description_html;

                                $output .= '</div>';

                            }

                            $j++;

                        }
                    $output .= '</div>';
                }

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );
				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

		function wdt_sp_contact_details( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id'              => '',
				'contact_details'         => 'list',
				'include_email'           => '',
				'include_phone'           => '',
				'include_mobile'          => '',
				'include_website'         => '',
				'class'                   => '',
				'type'                    => '',
			), $attrs, 'wdt_sp_contact_details' );

			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				if($attrs['type'] == 'listing') {
					$attrs['type'] = '';
				}

                $output .= '<div class="wdt-listings-contactdetails-container '.esc_attr( $attrs['class'] ).'">';

                    $output .= '<ul class="wdt-listings-contactdetails-list">';

						if($attrs['include_email'] == 'true') {
							$wdt_email = get_post_meta($attrs['listing_id'], 'wdt_email', true);
							if($wdt_email != '') {
								$output .= '<li><span class="fa fa-envelope"></span><a href="mailto:'.esc_attr($wdt_email).'">'.esc_attr($wdt_email).'</a></li>';
							}
						}

						if($attrs['include_phone'] == 'true') {
							$wdt_phone = get_post_meta($attrs['listing_id'], 'wdt_phone', true);
							if($wdt_phone != '') {
								$output .= '<li><span class="fa fa-phone"></span><a href="tel:'.sanitize_email($wdt_phone).'" class="phone" data-listingid="'.esc_attr($attrs['listing_id']).'" target="_blank">'.esc_html($wdt_phone).'</a></li>';
							}
						}

						if($attrs['include_mobile'] == 'true') {
							$wdt_mobile = get_post_meta($attrs['listing_id'], 'wdt_mobile', true);
							if($wdt_mobile != '') {
								$output .= '<li><span class="fa fa-mobile"></span><a href="tel:'.esc_attr($wdt_mobile).'" class="mobile" data-listingid="'.esc_attr($attrs['listing_id']).'" target="_blank">'.esc_html($wdt_mobile).'</a></li>';
							}
						}

						if($attrs['include_website'] == 'true') {
							$wdt_website = get_post_meta($attrs['listing_id'], 'wdt_website', true);
							if($wdt_website != '') {
								$output .= '<li><span class="fa fa-globe"></span><a href="'.esc_url($wdt_website).'" class="web" data-listingid="'.esc_attr($attrs['listing_id']).'" target="_blank">'.esc_html($wdt_website).'</a></li>';
							}
						}

                    $output .= '</ul>';

                $output .= '</div>';

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

		function wdt_sp_social_links( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id'   => '',
				'class'        => '',
			), $attrs, 'wdt_sp_social_links' );

			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				$output .= '<div class="wdt-listings-sociallinks-container '.esc_attr( $attrs['class'] ).'">';

                    $output .= '<label>'.esc_html__('Socials:', 'wdt-portfolio').'</label>';

					$output .= '<ul class="wdt-listings-sociallinks-list">';

                        $wdt_social_items = get_post_meta($attrs['listing_id'], 'wdt_social_items', true);
                        $wdt_social_items = (isset($wdt_social_items) && is_array($wdt_social_items)) ? $wdt_social_items : array ();

                        $wdt_social_items_value = get_post_meta($attrs['listing_id'], 'wdt_social_items_value', true);
                        $wdt_social_items_value = (isset($wdt_social_items_value) && is_array($wdt_social_items_value)) ? $wdt_social_items_value : array ();

						$i = 0;
						if(is_array($wdt_social_items) && !empty($wdt_social_items)) {
							foreach($wdt_social_items as $wdt_social_item) {
								$output .= '<li><a href="'.esc_url($wdt_social_items_value[$i]).'"><span class="fab '.esc_attr($wdt_social_item).'"></span></a></li>';
								$i++;
							}
						}

					$output .= '</ul>';

				$output .= '</div>';

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

		function wdt_sp_utils( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id'                    => '',
				'show_title'                    => '',
				'show_favourite'                => '',
				'show_socialshare'              => '',
				'show_categories'               => '',
				'show_contracttype'             => '',
				'show_amenity'                  => '',
				'show_excerpt'                  => '',
				'class'                         => '',
			), $attrs, 'wdt_sp_utils' );

			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				$output .= '<div class="wdt-listings-utils-container '.esc_attr( $attrs['class'] ).'">';

					if($attrs['show_title'] == 'true') {

						$output .= '<div class="wdt-listings-utils-item wdt-listings-utils-title">';
							$output .= '<h3 class="wdt-listings-utils-title-item"><a href="'.esc_url( get_permalink($attrs['listing_id']) ).'">'.get_the_title($attrs['listing_id']).'</a></h3>';
						$output .= '</div>';

					}

					if($attrs['show_favourite'] == 'true') {

						$current_user = wp_get_current_user();
						$user_id = $current_user->ID;

						$favourite_items = get_user_meta($user_id, 'favourite_items', true);
						$favourite_items = (is_array($favourite_items) && !empty($favourite_items)) ? $favourite_items : array();

						$favourite_attr = 'data-listingid="'.$attrs['listing_id'].'"';
						if($user_id > 0) {
							if(in_array($attrs['listing_id'], $favourite_items)) {
								$favourite_class = 'removefavourite';
								$favourite_icon_class = 'fa fa-heart';
							} else {
								$favourite_class = 'addtofavourite';
								$favourite_icon_class = 'far fa-heart';
							}
							$favourite_attr .= ' data-userid="'.$user_id.'"';
						} else {
							$favourite_class = 'wdt-login-link';
							$favourite_attr = '';
							$favourite_icon_class = 'far fa-heart';
						}

						$output .= '<div class="wdt-listings-utils-item wdt-listings-utils-favourite">';
							$output .= '<a class="wdt-listings-utils-favourite-item '.esc_attr( $favourite_class ).'" '.$favourite_attr.'><span class="'.$favourite_icon_class.'"></span></a>';
						$output .= '</div>';

					}

					if($attrs['show_excerpt'] == 'true') {

						$output .= '<div class="wdt-listings-utils-item wdt-listings-utils-excerpt">';
							$output .= '<div class="wdt-listings-utils-excerpt-item">'.get_the_excerpt($attrs['listing_id']).'</div>';
						$output .= '</div>';

					}

					if($attrs['show_categories'] == 'true') {

						$output .= '<div class="wdt-listings-utils-item wdt-listings-utils-categories">';
							$output .= do_shortcode('[wdt_sp_taxonomy listing_id="'.esc_attr($attrs['listing_id']).'" taxonomy="wdt_listings_category" type="utils" show_label="true" /]');
						$output .= '</div>';

					}

					if($attrs['show_amenity'] == 'true') {

						$output .= '<div class="wdt-listings-utils-item wdt-listings-utils-tags">';
							$output .= do_shortcode('[wdt_sp_taxonomy listing_id="'.esc_attr($attrs['listing_id']).'" taxonomy="wdt_listings_amenity" type="utils" show_label="true" /]');
						$output .= '</div>';

					}

					if($attrs['show_socialshare'] == 'true') {

						$output .= '<div class="wdt-listings-utils-item wdt-listings-utils-socialshare">';
							$output .= do_shortcode('[wdt_sp_social_links listing_id="'.esc_attr($attrs['listing_id']).'" class="" /]');
						$output .= '</div>';

					}

				$output .= '</div>';

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

		function wdt_sp_taxonomy( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id' => '',
				'taxonomy'   => 'wdt_listings_category',
				'show_label' => 'false',
				'splice'     => '',
				'class'      => '',
			), $attrs, 'wdt_sp_taxonomy' );

			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$listing_taxonomies = wp_get_post_terms($attrs['listing_id'], $attrs['taxonomy'], array ('orderby' => 'parent'));
				if(isset($attrs['splice']) && $attrs['splice'] != '') {
					array_splice($listing_taxonomies, $attrs['splice']);
				}

				if(!empty($listing_taxonomies)) {

					$output .= '<div class="wdt-listings-taxonomy-container ' .esc_attr( $attrs['class'] ).'">';

                        if($attrs['show_label'] == 'true') {
                            if($attrs['taxonomy'] == 'wdt_listings_category') {
                                $output .= '<label>'.esc_html__('Category:', 'wdt-portfolio').'</label>';
                            } else if($attrs['taxonomy'] == 'wdt_listings_amenity') {
                                $output .= '<label>'.apply_filters( 'amenity_label', 'singular' ).':</label>';
                            }
                        }

						$output .= '<ul class="wdt-listings-taxonomy-list">';

							foreach($listing_taxonomies as $listing_taxonomy) {

								if(isset($listing_taxonomy->term_id)) {

									$icon_image_url   = get_term_meta($listing_taxonomy->term_id, 'wdt-taxonomy-icon-image-url', true);
									$icon             = get_term_meta($listing_taxonomy->term_id, 'wdt-taxonomy-icon', true);
									$background_color = get_term_meta($listing_taxonomy->term_id, 'wdt-taxonomy-background-color', true);

									$tax_bg_color     = (isset($background_color) && !empty($background_color)) ? 'style="background-color:'.$background_color.';"': '';


									$output .= '<li>';
										$output .= '<a href="'.esc_url( get_term_link($listing_taxonomy->term_id) ).'">';
											if($icon != '') {
												$output .= '<span class="'.esc_attr( $icon ).'"></span>';
											}
											if($icon_image_url != '') {
												$output .= '<span class="wdt-listings-taxonomy-image" '.$tax_bg_color.'><img src="'.esc_url( $icon_image_url ).'" alt="'.sprintf( esc_html__('%1$s Taxonomy Image','wdt-portfolio'), $listing_singular_label ).'" title="'.sprintf( esc_attr__('%1$s Taxonomy Image','wdt-portfolio'), $listing_singular_label ).'" /></span>';
											}
											$output .= '<span>'.esc_html($listing_taxonomy->name).'</span>';
										$output .= '</a>';
									$output .= '</li>';


								}

							}

						$output .= '</ul>';

					$output .= '</div>';

				}

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

		function wdt_sp_post_date( $attrs, $content = null ) {

			$attrs = shortcode_atts ( array (
				'listing_id'       => '',
				'include_posttime' => '',
				'with_label'       => '',
				'with_icon'        => '',
				'class'            => ''
			), $attrs, 'wdt_sp_post_date' );

			$output = '';

			if($attrs['listing_id'] == '' && is_singular('wdt_listings')) {
				global $post;
				$attrs['listing_id'] = $post->ID;
			}

			if($attrs['listing_id'] != '') {

				$output .= '<div class="wdt-listings-post-dates-container '.esc_attr( $attrs['class'] ).'">';

					$wdt_post_date = get_the_date( get_option('date_format'), $attrs['listing_id'] );

					if($wdt_post_date != '') {

						$output .= '<div class="wdt-listings-post-date-container">';

							if($attrs['with_icon'] == 'true') {
								$output .= '<span class="wdt-listings-post-date-icon"></span>';
							}

							if($attrs['with_label'] == 'true') {
								$output .= '<label class="wdt-listings-post-date-label">'.esc_html__('Posted On: ','wdt-portfolio').'</label>';
							}

							$output .= '<div class="wdt-listings-post-datetime-holder">';

								$output .= '<div class="wdt-listings-post-date-holder">';
									$output .= $wdt_post_date;
								$output .= '</div>';

								if($attrs['include_posttime'] == 'true') {

									$output .= '<div class="wdt-listings-post-time-holder">';

										$wdt_24_hour_format = get_post_meta($attrs['listing_id'], 'wdt_24_hour_format', true);

										if($wdt_24_hour_format == 'true') {
											$output .= get_the_time( 'G:i', $attrs['listing_id'] );
										} else {
											$output .= get_the_time( 'g:i A', $attrs['listing_id'] );
										}

									$output .= '</div>';

								}

							$output .= '</div>';

						$output .= '</div>';
					}

				$output .= '</div>';

			} else {

				$listing_singular_label = apply_filters( 'listing_label', 'singular' );

				$output .= sprintf( esc_html__('Please provide %1$s id to display corresponding data!','wdt-portfolio'), strtolower($listing_singular_label) );

			}

			return $output;

		}

	}

	WDTPortfolioSinglePageShortcodes::instance();
}
?>