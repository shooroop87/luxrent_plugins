<?php
if( !class_exists('WDTPortfolioShortcodes') ) {

	class WDTPortfolioShortcodes {

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

			add_shortcode ( 'wdt_listings_listing', array ( $this, 'wdt_listings_listing' ) );

		}

		function wdt_shortcodeHelper($content = null) {
			$content = do_shortcode ( shortcode_unautop ( $content ) );
			$content = preg_replace ( '#^<\/p>|^<br \/>|<p>$#', '', $content );
			$content = preg_replace ( '#<br \/>#', '', $content );
			return trim ( $content );
		}

		function wdt_listings_listing($attrs, $content = null) {

			$attrs = shortcode_atts ( array (
				'type'                       => 'type1',
				'gallery'                    => 'featured_image',
				'post_per_page'              => -1,
				'columns'                    => 1,
				'apply_isotope'              => 'true',
				'isotope_filter'             => '',
                'show_isotope_filter_count'  => 'false',
				'apply_child_of'             => 'false',
				'featured_items'             => '',
				'features_image_or_icon'     => '',
				'features_include'           => '',
				'no_of_cat_to_display'       => 2,
				'excerpt_length'             => 20,
				'apply_equal_height'         => 'false',
				'apply_custom_height'        => 'false',
				'height'                     => '',
				'vc_height'                  => '',
				'sidebar_widget'             => 'false',
				'pagination_type'            => '',

				'list_item_ids'              => '',
				'category_ids'               => '',
				'tag_ids'                    => '',

				'show_image_popup' 			 => '',
				'enable_view_details_btn'    => '',

                'masonary_one_items'         => '',
                'masonary_one_half_items'    => '',
                'masonary_one_third_items'   => '',
                'masonary_two_third_items'   => '',
                'masonary_one_fourth_items'  => '',
                'masonary_three_fourth_items'=> '',
                'masonary_two_five_items'=> '',

				'enable_carousel'            => '',
				'carousel_effect'            => '',
				'carousel_autoplay'          => 0,
				'carousel_centered_slides'   => '',
				'carousel_slidesperview'     => 2,
				'carousel_loopmode'          => '',
				'carousel_mousewheelcontrol' => '',
				'carousel_bulletpagination'  => 'true',
				'carousel_arrowpagination'   => '',
				'carousel_progressbar'       => '',
				'carousel_overflow_opacity' => 'false',
				'carousel_spacebetween'      => 30,

				'module_id'					 => '',

				'class'                      => '',
				'carousel_slidesperview_widescreen'=> '',
				'carousel_slidesperview_laptop' => '',
				'carousel_slidesperview_tablet_extra'=>'',
				'carousel_slidesperview_tablet'=>'',
				'carousel_slidesperview_mobile_extra'=>'',
				'carousel_slidesperview_mobile'=>''

			), $attrs, 'wdt_listings_listing' );

			$carousel_settings_value = '';
			
			if($attrs['enable_carousel'] == 'true') {
				$attrs['columns'] = $attrs['carousel_slidesperview'];

				$slides_to_show = $attrs['carousel_slidesperview'];
				$slides_to_scroll = 1;
		
				extract($attrs);
					// Responsive control carousel
					$carousel_settings = array (
						'carousel_slidesperview' 			=> $slides_to_show
					);
			
					$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
					$breakpoint_keys = array_keys($active_breakpoints);
		
					$swiper_breakpoints = array ();
					$swiper_breakpoints[] = array (
						'breakpoint' => 319
					);
					$swiper_breakpoints_slides = array ();
					
					foreach($breakpoint_keys as $breakpoint) {
						$breakpoint_show_str = 'carousel_slidesperview_'.$breakpoint;
						$breakpoint_toshow = $$breakpoint_show_str;
						if($breakpoint_toshow == '') {
							if($breakpoint == 'mobile') {
								$breakpoint_toshow = $attrs['carousel_slidesperview_mobile'];
							} else if($breakpoint == 'mobile_extra') {
								$breakpoint_toshow = $attrs['carousel_slidesperview_mobile_extra'];
							} else if($breakpoint == 'tablet') {
								$breakpoint_toshow = $attrs['carousel_slidesperview_tablet'];
							} else if($breakpoint == 'tablet_extra') {
								$breakpoint_toshow = $attrs['carousel_slidesperview_tablet_extra'];
							} else if($breakpoint == 'laptop') {
								$breakpoint_toshow = $attrs['carousel_slidesperview_laptop'];
							} else if($breakpoint == 'widescreen') {
								$breakpoint_toshow = $attrs['carousel_slidesperview_widescreen'];
							} else {
								$breakpoint_toshow = 4;
							}
						}
		
						$breakpoint_toscroll = 1;
			
						array_push($swiper_breakpoints, array (
								'breakpoint' => $active_breakpoints[$breakpoint]->get_value() + 1
							)
						);
						array_push($swiper_breakpoints_slides, array (
								'toshow' => (int)$breakpoint_toshow,
								'toscroll' => (int)$breakpoint_toscroll
							)
						);
			
					}
		
					array_push($swiper_breakpoints_slides, array (
							'toshow' => (int)$slides_to_show,
							'toscroll' => (int)$slides_to_scroll
						)
					);
		
					$responsive_breakpoints = array ();
		
					if(is_array($swiper_breakpoints) && !empty($swiper_breakpoints)) {
						foreach($swiper_breakpoints as $key => $swiper_breakpoint) {
							$responsive_breakpoints[] = array_merge($swiper_breakpoint, $swiper_breakpoints_slides[$key]);
						}
					}
		
					$carousel_settings['responsive'] = $responsive_breakpoints;
		
					$carousel_settings_value = wp_json_encode($carousel_settings);

			}
			
			$data_attributes = array ();
			array_push($data_attributes, 'data-type="'.esc_attr($attrs['type']).'"');
			array_push($data_attributes, 'data-gallery="'.esc_attr($attrs['gallery']).'"');
			array_push($data_attributes, 'data-postperpage="'.esc_attr($attrs['post_per_page']).'"');
			array_push($data_attributes, 'data-columns="'.esc_attr($attrs['columns']).'"');
			array_push($data_attributes, 'data-applyisotope="'.esc_attr($attrs['apply_isotope']).'"');
			array_push($data_attributes, 'data-isotopefilter="'.esc_attr($attrs['isotope_filter']).'"');
            array_push($data_attributes, 'data-showisotopefiltercount="'.esc_attr($attrs['show_isotope_filter_count']).'"');
			array_push($data_attributes, 'data-applychildof="'.esc_attr($attrs['apply_child_of']).'"');
			array_push($data_attributes, 'data-featureditems="'.esc_attr($attrs['featured_items']).'"');
			array_push($data_attributes, 'data-featuresimageoricon="'.esc_attr($attrs['features_image_or_icon']).'"');
			array_push($data_attributes, 'data-featuresinclude="'.esc_attr($attrs['features_include']).'"');
			array_push($data_attributes, 'data-noofcattodisplay="'.esc_attr($attrs['no_of_cat_to_display']).'"');
			array_push($data_attributes, 'data-excerptlength="'.esc_attr($attrs['excerpt_length']).'"');
			array_push($data_attributes, 'data-applyequalheight="'.esc_attr($attrs['apply_equal_height']).'"');
			array_push($data_attributes, 'data-paginationtype="'.esc_attr($attrs['pagination_type']).'"');
			array_push($data_attributes, 'data-moduleid="'.esc_attr($attrs['module_id']).'"');

			// Custom attributes update from modules
			$wdt_listing_custom_options = apply_filters('wdt_listings_listing_data_attrs_from_modules', '', $attrs);
			array_push($data_attributes, 'data-customoptions="'.esc_attr($wdt_listing_custom_options).'"');

			array_push($data_attributes, 'data-listitemids="'.esc_attr($attrs['list_item_ids']).'"');
			array_push($data_attributes, 'data-categoryids="'.esc_attr($attrs['category_ids']).'"');
			array_push($data_attributes, 'data-tagids="'.esc_attr($attrs['tag_ids']).'"');

			array_push($data_attributes, 'data-showimagepopup="'.esc_attr($attrs['show_image_popup']).'"');
			array_push($data_attributes, 'data-enableviewdetailsbtn="'.esc_attr($attrs['enable_view_details_btn']).'"');

            array_push($data_attributes, 'data-masonaryoneitems="'.esc_attr($attrs['masonary_one_items']).'"');
            array_push($data_attributes, 'data-masonaryonehalfitems="'.esc_attr($attrs['masonary_one_half_items']).'"');
            array_push($data_attributes, 'data-masonaryonethirditems="'.esc_attr($attrs['masonary_one_third_items']).'"');
            array_push($data_attributes, 'data-masonarytwothirditems="'.esc_attr($attrs['masonary_two_third_items']).'"');
            array_push($data_attributes, 'data-masonaryonefourthitems="'.esc_attr($attrs['masonary_one_fourth_items']).'"');
            array_push($data_attributes, 'data-masonarythreefourthitems="'.esc_attr($attrs['masonary_three_fourth_items']).'"');
            array_push($data_attributes, 'data-masonarytwofiveitems="'.esc_attr($attrs['masonary_two_five_items']).'"');
			array_push($data_attributes, 'data-carouselresponsive="'.esc_js($carousel_settings_value).'"');
			array_push($data_attributes, 'data-moduleid="'.esc_attr($attrs['module_id']).'"');

			if(!empty($data_attributes)) {
				$data_attributes_string = implode(' ', $data_attributes);
			}

			$listing_carousel_attributes = array ();
			$listing_carousel_attributes_string = '';
			$swipper_container_class = '';

			if($attrs['enable_carousel'] == 'true') {

				if(($attrs['type'] == 'type3' || $attrs['sidebar_widget'] == 'true') && $attrs['carousel_slidesperview'] > 1) {
					$attrs['carousel_slidesperview'] = 1;
				}

				array_push($listing_carousel_attributes, 'data-enablecarousel="true"');
				array_push($listing_carousel_attributes, 'data-carouseleffect="'.esc_attr($attrs['carousel_effect']).'"');
				array_push($listing_carousel_attributes, 'data-carouselautoplay="'.esc_attr($attrs['carousel_autoplay']).'"');
				array_push($listing_carousel_attributes, 'data-carouselcenteredslides="'.esc_attr($attrs['carousel_centered_slides']).'"');
				array_push($listing_carousel_attributes, 'data-carouselslidesperview="'.esc_attr($attrs['carousel_slidesperview']).'"');
				array_push($listing_carousel_attributes, 'data-carouselloopmode="'.esc_attr($attrs['carousel_loopmode']).'"');
				array_push($listing_carousel_attributes, 'data-carouselmousewheelcontrol="'.esc_attr($attrs['carousel_mousewheelcontrol']).'"');
				array_push($listing_carousel_attributes, 'data-carouselbulletpagination="'.esc_attr($attrs['carousel_bulletpagination']).'"');
				array_push($listing_carousel_attributes, 'data-carouselarrowpagination="'.esc_attr($attrs['carousel_arrowpagination']).'"');
				array_push($listing_carousel_attributes, 'data-carouselprogressbar="'.esc_attr($attrs['carousel_progressbar']).'"');
				array_push($listing_carousel_attributes, 'data-carouseloverflowopacity="'.esc_attr($attrs['carousel_overflow_opacity']).'"');
				array_push($listing_carousel_attributes, 'data-carouselspacebetween="'.esc_attr($attrs['carousel_spacebetween']).'"');
				array_push($listing_carousel_attributes, 'data-moduleid="'.esc_attr($attrs['module_id']).'"');
				

				if(!empty($listing_carousel_attributes)) {
					$listing_carousel_attributes_string = implode(' ', $listing_carousel_attributes);
				}

			}

			if($attrs['sidebar_widget'] == 'true') {
				$attrs['class'] .= " wdt-listings-sidebar-widget";
			}

			if($attrs['apply_custom_height'] == 'true') {
				$attrs['class'] .= " wdt-content-scroll";
			}

			$height_attr = '';
			if($attrs['vc_height'] != '') {
				$height_attr = 'style="height:'.esc_attr( $attrs['vc_height'] ).'px;"';
			}

			$output = '';
			$output .= '<div class="wdt-listing-output-data-container wdt-direct-list-items wdt-listing-no-map '.esc_attr( $attrs['class'] ).'" '.$listing_carousel_attributes_string.' '.$height_attr.'>';

				$output .= '<div class="wdt-listing-output-data-holder" '.$data_attributes_string.'></div>';

				if($attrs['enable_carousel'] == 'true') {

					if($attrs['carousel_bulletpagination'] == 'true' || $attrs['carousel_arrowpagination'] == 'true' || $attrs['carousel_progressbar'] == 'true') {
						$output .= '<div class="wdt-swiper-pagination-holder">';

							if($attrs['carousel_bulletpagination'] == 'true') {
								$output .= '<div class="wdt-swiper-bullet-pagination"></div>';
							}

							if($attrs['carousel_arrowpagination'] == 'true') {
								$output .= '<div class="wdt-swiper-arrow-pagination">';
									$output .= '<a href="#" class="wdt-swiper-arrow-prev wdt-swiper-arrow-prev-'.$attrs['module_id'].'">'.esc_html__('Prev','wdt-portfolio').'</a>';
									$output .= '<a href="#" class="wdt-swiper-arrow-next wdt-swiper-arrow-next-'.$attrs['module_id'].'">'.esc_html__('Next','wdt-portfolio').'</a>';
								$output .= '</div>';
							}
							if($attrs['carousel_progressbar'] == 'true') {
								$output .= '<div class="wdt-swiper-progress-bar wdt-swiper-progress-bar-'.$attrs['module_id'].'"></div>';
							}

						$output .= '</div>';
					}

				}

				$output .= wdt_generate_loader_html(false);

			$output .= '</div>';

			return $output;

		}


	}

	WDTPortfolioShortcodes::instance();

}

?>