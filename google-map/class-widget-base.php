<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Google_Map {

	private static $_instance = null;

	private $cc_style;
	private $map_api_key;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() {

		$this->map_api_key = get_option( 'elementor_wdt_google_map_api_key' );

		// Initialize depandant class
			$this->cc_style = new WeDesignTech_Common_Controls_Style();

	}

	public function name() {
		return 'wdt-google-map';
	}

	public function title() {
		return esc_html__( 'Google Map', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array (
				$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/google-map/assets/css/style.css'
			);
	}

	public function init_inline_styles() {
		return array ();
	}

	public function init_scripts() {
		if (!empty($this->map_api_key)) {
			$gmap_api_url = add_query_arg(['key' => $this->map_api_key], 'https://maps.googleapis.com/maps/api/js');
			
			add_filter('script_loader_tag', function ($tag, $handle) {
				if ($handle === 'google-map') { 
					if (strpos($tag, 'defer') === false) {
						return str_replace('<script ', '<script defer ', $tag);
					} 
					return $tag;
				}
				return $tag;
			}, 10, 2);

			return array(
				'google-map' => $gmap_api_url,
				$this->name() => WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL . 'inc/widgets/google-map/assets/js/script.js'
			);
		}
	}

	public function create_elementor_controls($elementor_object) {

		// General

			$elementor_object->start_controls_section( 'wdt_section_general', array(
				'label' => esc_html__( 'General', 'wdt-elementor-addon'),
			) );

				$key = get_option( 'elementor_wdt_google_map_api_key' );
				if( !$key ) {
					$elementor_object->add_control( 'api_key_info', array(
						'type'            => \Elementor\Controls_Manager::RAW_HTML,
						'raw'             => sprintf(
							esc_html__('To display customized Google Map without an issue, you need to configure Google Map API key. Please configure API key from <a href="%s" target="_blank" rel="noopener">here</a>.', 'wdt-elementor-addon'),
							add_query_arg( array('page' => 'elementor#tab-wedesigntech' ), esc_url( admin_url( 'admin.php') ) )
						),
						'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					) );
				}

				$elementor_object->add_control( 'center_latitude', array(
					'label'       => esc_html__('Center Latitude','wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'description' => sprintf(
						esc_html__('Click %1$s to get your location coordinates', 'wdt-elementor-addon'),
						'<a href="https://www.latlong.net/" target="_blank">'.esc_html__('here', 'wdt-elementor-addon').'</a>'
					)
				) );

				$elementor_object->add_control( 'center_longitude', array(
					'label'       => esc_html__('Center Longitude','wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'description' => sprintf(
						esc_html__('Click %1$s to get your location coordinates', 'wdt-elementor-addon'),
						'<a href="https://www.latlong.net/" target="_blank">'.esc_html__('here', 'wdt-elementor-addon').'</a>'
					)
				) );

				$elementor_object->add_control( 'zoom_level', array(
					'label'   => esc_html__( 'Zoom Level', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::SLIDER,
					'default' => array( 'size' => 10 ),
					'range'   => array( 'px' => array( 'min' => 0, 'max' => 20 ) )
				) );


				$elementor_object->add_control( 'map_type', array(
					'label'       => esc_html__( 'Map Type', 'wdt-elementor-addon' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'options'     => array(
						'roadmap'   => esc_html__( 'Road Map', 'wdt-elementor-addon' ),
						'satellite' => esc_html__( 'Satellite', 'wdt-elementor-addon' ),
						'hybrid'    => esc_html__( 'Hybrid', 'wdt-elementor-addon' ),
						'terrain'   => esc_html__( 'Terrain', 'wdt-elementor-addon' ),
					),
					'description' => esc_html__( 'Choose map type for this item.', 'wdt-elementor-addon' ),
					'default' => 'roadmap',
				) );

			$elementor_object->end_controls_section();

		// Markers

			$elementor_object->start_controls_section( 'wdt_section_markers', array(
				'label' => esc_html__( 'Markers', 'wdt-elementor-addon' ),
			) );

				$elementor_object->add_control( 'marker_additional_details', array(
					'label' => esc_html__( 'Enable Additional Details', 'wdt-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'true',
					'frontend_available' => true,
					'return_value' => 'true'
				) );

				$elementor_object->add_control( 'marker_animation', array(
					'label'   => esc_html__( 'Marker Animation', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'separator' => 'after',
					'options' => array(
						''       => esc_html__( 'None', 'wdt-elementor-addon' ),
						'drop'   => esc_html__( 'Drop', 'wdt-elementor-addon' ),
						'bounce' => esc_html__( 'Bounce', 'wdt-elementor-addon' ),
						'soft-beat' => esc_html__( 'Soft Beat', 'wdt-elementor-addon' ),
					)
				) );

				$repeater = new \Elementor\Repeater();

				$repeater->add_control( 'latitude', array(
					'label'       => esc_html__('Latitude','wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'description' => sprintf(
						esc_html__('Click %1$s to get your location coordinates', 'wdt-elementor-addon'),
						'<a href="https://www.latlong.net/" target="_blank">'.esc_html__('here', 'wdt-elementor-addon').'</a>'
					)
				) );

				$repeater->add_control( 'longitude', array(
					'label'       => esc_html__('Longitude','wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'description' => sprintf(
						esc_html__('Click %1$s to get your location coordinates', 'wdt-elementor-addon'),
						'<a href="https://www.latlong.net/" target="_blank">'.esc_html__('here', 'wdt-elementor-addon').'</a>'
					)
				) );

				$repeater->add_control( 'title', array(
					'label'       => esc_html__('Title', 'wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => esc_html__( 'Marker Title', 'wdt-elementor-addon' )
				) );

				$repeater->add_control( 'show_info_window', array(
					'label'        => esc_html__( 'Show Info Window', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$repeater->add_control( 'load_info_window', array(
					'label'     => esc_html__( 'Show info Window On', 'wdt-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => 'click',
					'condition' => array( 'show_info_window' => 'yes' ),
					'options'   => array(
						'click' => esc_html__( 'Mouse Click', 'wdt-elementor-addon' ),
						'load'  => esc_html__( 'Page Load', 'wdt-elementor-addon' ),
					)
				) );

				$repeater->add_control( 'desc', array(
					'label'       => esc_html__('Info Window - Description', 'wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::WYSIWYG,
					'condition'   => array( 'show_info_window' => 'yes' ),
					'label_block' => true
				) );

				$repeater->add_control( 'icon', array(
					'label'     => esc_html__( 'Marker Icon', 'wdt-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => 'default',
					'options'   => array(
						'default' => esc_html__( 'Default', 'wdt-elementor-addon' ),
						'custom'  => esc_html__( 'Custom', 'wdt-elementor-addon' ),
					)
				) );

				$repeater->add_control( 'custom_icon', array(
					'label'     => esc_html__('Custom Icon', 'wdt-elementor-addon'),
					'type'      => \Elementor\Controls_Manager::MEDIA,
					'condition' => array( 'icon' => 'custom' ),
				) );

				$repeater->add_control( 'custom_icon_size', array(
					'label'      => esc_html__('Icon Size', 'wdt-elementor-addon'),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => array('px'),
					'default'    => array( 'size' => 30, 'unit' => 'px'),
					'range'      => array( 'px' => array( 'min' => 5, 'max' => 100 ) ),
					'condition'  => array( 'icon' => 'custom' )
				) );

				$repeater->add_control(
					'marker_additional_details_heading',
					array (
						'label' => esc_html__( 'Additional Details', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before'
					)
				);

				$repeater->add_control( 'marker_additional_details_type', array(
					'label'   => esc_html__( 'Type', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'default' => 'default',
					'options' => array(
						'default'  => esc_html__( 'Default', 'wdt-elementor-addon' ),
						'template' => esc_html__( 'Template', 'wdt-elementor-addon' ),
					)
				) );

				$repeater->add_control( 'marker_additional_details_default', array(
					'label'       => esc_html__('Description', 'wdt-elementor-addon'),
					'type'        => \Elementor\Controls_Manager::WYSIWYG,
					'condition'   => array( 'marker_additional_details_type' => 'default' ),
				) );

				$repeater->add_control('marker_additional_details_template', array(
					'label'     => esc_html__( 'Template', 'wdt-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'options'   => $elementor_object->get_elementor_page_list(),
					'condition' =>  array( 'marker_additional_details_type' => 'template' ),
				) );

				$elementor_object->add_control( 'markers', array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{title}}',
				) );

			$elementor_object->end_controls_section();

		// Controls

			$elementor_object->start_controls_section( 'wdt_section_control', array(
				'label' => esc_html__( 'Controls', 'wdt-elementor-addon' ),
			) );

				$elementor_object->add_control( 'street_view_control', array(
					'label'        => esc_html__( 'Street View Controls', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'map_type_control', array(
					'label'        => esc_html__( 'Map Type Controls', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'zoom_control', array(
					'label'        => esc_html__( 'Zoom Control', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'full_screen_control', array(
					'label'        => esc_html__( 'Full Screen Control', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'scale_control', array(
					'label'        => esc_html__( 'Scale Control', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'rotate_control', array(
					'label'        => esc_html__( 'Rotate Control', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'scroll_zoom_control', array(
					'label'        => esc_html__( 'Scroll Wheel Zoom Control', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

				$elementor_object->add_control( 'draggable_control', array(
					'label'        => esc_html__( 'Is Map Draggable ?', 'wdt-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'wdt-elementor-addon' ),
					'label_off'    => esc_html__( 'Off', 'wdt-elementor-addon' ),
					'return_value' => 'yes',
				) );

			$elementor_object->end_controls_section();

		// Theme

			$elementor_object->start_controls_section( 'wdt_section_theme', array(
				'label' => esc_html__( 'Theme', 'wdt-elementor-addon' ),
			) );

				$elementor_object->add_control( 'theme', array(
					'label'   => esc_html__( 'Theme Source', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::CHOOSE,
					'options' => array(
						'gstandards'  => array( 'title' => esc_html__( 'Google Standard', 'wdt-elementor-addon' ), 'icon' => 'fas fa-map' ),
						'snazzymaps' => array( 'title' => esc_html__( 'Snazzy Maps', 'wdt-elementor-addon' ), 'icon' => 'fas fa-map-marker' ),
						'custom_theme'     => array( 'title' => esc_html__( 'Custom', 'wdt-elementor-addon' ), 'icon' => 'fas fa-edit' )
					),
					'default' => 'gstandards'
				) );

				$elementor_object->add_control( 'gstandards', array(
					'label'       => esc_html__( 'Google Themes', 'wdt-elementor-addon' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'standard',
					'options'     => array(
						'standard'  => esc_html__( 'Standard', 'wdt-elementor-addon' ),
						'silver'    => esc_html__( 'Silver', 'wdt-elementor-addon' ),
						'retro'     => esc_html__( 'Retro', 'wdt-elementor-addon' ),
						'dark'      => esc_html__( 'Dark', 'wdt-elementor-addon' ),
						'night'     => esc_html__( 'Night', 'wdt-elementor-addon' ),
						'aubergine' => esc_html__( 'Aubergine', 'wdt-elementor-addon' )
					),
					'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'wdt-elementor-addon' ), esc_html__( 'to generate your own theme and use JSON within Custom style field.', 'wdt-elementor-addon' ) ),
					'condition'   => array( 'theme'	=> 'gstandards' )
				) );

				$elementor_object->add_control( 'snazzymaps', array(
						'label'       => esc_html__( 'SnazzyMaps Themes', 'wdt-elementor-addon' ),
						'type'        => \Elementor\Controls_Manager::SELECT,
						'label_block' => true,
						'default'     => 'colorful',
						'options'     => array(
							'simple'     => esc_html__( 'Simple', 'wdt-elementor-addon' ),
							'colorful'   => esc_html__( 'Colorful', 'wdt-elementor-addon' ),
							'complex'    => esc_html__( 'Complex', 'wdt-elementor-addon' ),
							'dark'       => esc_html__( 'Dark', 'wdt-elementor-addon' ),
							'greyscale'  => esc_html__( 'Greyscale', 'wdt-elementor-addon' ),
							'light'      => esc_html__( 'Light', 'wdt-elementor-addon' ),
							'monochrome' => esc_html__( 'Monochrome', 'wdt-elementor-addon' ),
							'nolabels'   => esc_html__( 'No Labels', 'wdt-elementor-addon' ),
							'twotone'    => esc_html__( 'Two Tone', 'wdt-elementor-addon' )
						),
						'description' => sprintf( '<a href="https://snazzymaps.com/explore" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'wdt-elementor-addon' ), esc_html__( 'to explore more themes and use JSON within custom style field.', 'wdt-elementor-addon' ) ),
						'condition'   => array( 'theme'	=> 'snazzymaps' )
				) );

				$elementor_object->add_control( 'custom_theme', array(
					'label'       => esc_html__( 'Custom Style', 'wdt-elementor-addon' ),
					'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'wdt-elementor-addon' ), esc_html__( 'to get JSON style code to style your map', 'wdt-elementor-addon' ) ),
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'condition'   => array(
						'theme'     => 'custom_theme',
					)
				) );

			$elementor_object->end_controls_section();


		// Style - Map

			$elementor_object->start_controls_section( 'wdt_style_section_map', array(
				'label' => esc_html__( 'Map', 'wdt-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE
			) );

				$elementor_object->add_responsive_control( 'map_max_width', array(
					'label'      => esc_html__( 'Max Width', 'wdt-elementor-addon' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'default'    => array( 'size' => 1140, 'unit' => 'px' ),
					'size_units' => array( 'px' ),
					'range'      => array( 'px' => array( 'min' => 0, 'max' => 1400, 'step' => 10 ) ),
					'selectors'  => array(
						'{{WRAPPER}}  .wdt-google-map-wrapper .wdt-google-map' => 'max-width: {{SIZE}}{{UNIT}};',
					)
				) );

				$elementor_object->add_responsive_control( 'map_height', array(
					'label'      => esc_html__( 'Max Height', 'wdt-elementor-addon' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'default'    => array( 'size' => 500, 'unit' => 'px' ),
					'size_units' => array( 'px' ),
					'range'      => array( 'px' => array( 'min' => 0, 'max' => 1400, 'step' => 10 ) ),
					'selectors'  => array(
						'{{WRAPPER}}  .wdt-google-map-wrapper .wdt-google-map' => 'height: {{SIZE}}{{UNIT}};',
					)
				) );

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					array (
						'name' => 'map_background',
						'types' => array ( 'classic', 'gradient' ),
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper',
						'separator' => 'before',
						'fields_options' => array (
							'background' => array (
								'frontend_available' => true
							),
							'color' => array (
								'label' => esc_html__( 'Background Color', 'wdt-elementor-addon' ),
								'frontend_available' => true
							),
						)
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array (
						'name' => 'map_border',
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper'
					)
				);

				$elementor_object->add_responsive_control(
					'map_border_radius',
					array (
						'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					array (
						'name' => 'map_box_shadow',
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper',
					)
				);

				$elementor_object->add_responsive_control(
					'map_padding',
					array (
						'label' => esc_html__( 'Padding', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%', 'rem' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'separator' => 'before'
					)
				);

				$elementor_object->add_responsive_control(
					'map_margin',
					array (
						'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%', 'rem' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

			$elementor_object->end_controls_section();


		// Style - Info Window

			$elementor_object->start_controls_section( 'wdt_style_section_info_window', array(
				'label' => esc_html__( 'Info Window', 'wdt-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE
			) );

				$elementor_object->add_responsive_control( 'iw_align', array(
					'label'   => esc_html__( 'Alignment', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::CHOOSE,
					'options' => array(
						'left'    => array( 'title' => esc_html__( 'Left', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-left', ),
						'center'  => array( 'title' => esc_html__( 'Center', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-center', ),
						'right'   => array( 'title' => esc_html__( 'Right', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-right', ),
						'justify' => array( 'title' => esc_html__( 'Justified', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-justify', ),
					),
					'prefix_class' => 'elementor%s-align-',
					'default'      => 'left',
				) );

				$elementor_object->add_control( 'iw_max_width', array(
					'label'   => esc_html__( 'Info Window Max Width', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::SLIDER,
					'default' => array( 'size' => 240 ),
					'range'   => array( 'px' => array( 'min' => 40, 'max' => 500, 'step' => 1 ) )
				) );


				$elementor_object->add_responsive_control(
					'iw_padding',
					array (
						'label' => esc_html__( 'Padding', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%', 'rem' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .gm-style-iw-c' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					)
				);


				$elementor_object->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					array (
						'name' => 'iw_background',
						'types' => array ( 'classic', 'gradient' ),
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .gm-style-iw-c',
						'fields_options' => array (
							'background' => array (
								'frontend_available' => true
							),
							'color' => array (
								'label' => esc_html__( 'Background Color', 'wdt-elementor-addon' ),
								'frontend_available' => true
							),
						)
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array (
						'name' => 'iw_border',
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .gm-style-iw-c'
					)
				);

				$elementor_object->add_responsive_control(
					'iw_border_radius',
					array (
						'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .gm-style-iw-c' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					array (
						'name' => 'iw_box_shadow',
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .gm-style-iw-c',
					)
				);

				$elementor_object->add_control(
					'iw_title_heading',
					array (
						'label' => esc_html__( 'Title', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before'
					)
				);

				$elementor_object->add_control(
					'iw_title_color',
					array (
						'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-info-container .wdt-google-map-info-title' => 'color: {{VALUE}};',
						)
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					array (
						'name' => 'iw_title',
						'label' => esc_html__('Typography', 'wdt-elementor-addon'),
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-info-container .wdt-google-map-info-title'
					)
				);

				$elementor_object->add_control( 'iw_title_bottom_spacing', array(
					'label'   => esc_html__( 'Bottom Spacing', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::SLIDER,
					'range'      => array( 'px' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-info-container .wdt-google-map-info-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					)
				) );


				$elementor_object->add_control(
					'iw_desc_heading',
					array (
						'label' => esc_html__( 'Description', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before'
					)
				);

				$elementor_object->add_control(
					'iw_desc_color',
					array (
						'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-info-container .wdt-google-map-info-desc' => 'color: {{VALUE}};',
						)
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					array (
						'name' => 'iw_desc',
						'label' => esc_html__('Typography', 'wdt-elementor-addon'),
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-info-container .wdt-google-map-info-desc'
					)
				);

			$elementor_object->end_controls_section();

		// Style - Marker - Aditional Details

			$elementor_object->start_controls_section( 'wdt_style_section_marker_additional_details', array(
				'label' => esc_html__( 'Marker - Aditional Details', 'wdt-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE
			) );

				$elementor_object->add_responsive_control( 'mad_align', array(
					'label'   => esc_html__( 'Alignment', 'wdt-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::CHOOSE,
					'options' => array(
						'left'    => array( 'title' => esc_html__( 'Left', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-left', ),
						'center'  => array( 'title' => esc_html__( 'Center', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-center', ),
						'right'   => array( 'title' => esc_html__( 'Right', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-right', ),
						'justify' => array( 'title' => esc_html__( 'Justified', 'wdt-elementor-addon' ), 'icon' => 'eicon-text-align-justify', ),
					),
					'prefix_class' => 'elementor%s-align-',
					'default'      => 'left',
				) );

				$elementor_object->add_responsive_control(
					'mad_margin',
					array (
						'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%', 'rem' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-marker-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$elementor_object->add_responsive_control(
					'mad_padding',
					array (
						'label' => esc_html__( 'Padding', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%', 'rem' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-marker-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					array (
						'name' => 'mad_background',
						'types' => array ( 'classic', 'gradient' ),
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-marker-content-wrapper',
						'fields_options' => array (
							'background' => array (
								'frontend_available' => true
							),
							'color' => array (
								'label' => esc_html__( 'Background Color', 'wdt-elementor-addon' ),
								'frontend_available' => true
							),
						)
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array (
						'name' => 'mad_border',
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-marker-content-wrapper'
					)
				);

				$elementor_object->add_responsive_control(
					'mad_border_radius',
					array (
						'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => array ( 'px', 'em', '%' ),
						'selectors' => array (
							'{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-marker-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$elementor_object->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					array (
						'name' => 'mad_box_shadow',
						'selector' => '{{WRAPPER}} .wdt-google-map-wrapper .wdt-google-map-marker-content-wrapper',
					)
				);

	}

	public function map_markers( $markers ) {

		$locations = array();

		foreach( $markers as $index => $marker ) {

			$obj = array(
				'key'                                => $marker['_id'],
				'latitude'                           => $marker['latitude'],
				'longitude'                          => $marker['longitude'],
				'title'                              => $marker['title'],
				'desc'                               => $marker['desc'],
				'show_info_window'                   => $marker['show_info_window'],
				'load_info_window'                   => $marker['load_info_window'],
				'marker_additional_details_type'     => $marker['marker_additional_details_type'],
				'marker_additional_details_default'  => $marker['marker_additional_details_default'],
				'marker_additional_details_template' => $marker['marker_additional_details_template']
			);

			if( $marker['icon'] == "custom" ) {
				$custom_icon = array_filter( $marker['custom_icon'] );
				if( isset( $custom_icon['url'] ) ) {
					$obj['icon'] = $custom_icon['url'];
					$obj['icon_size'] = isset( $marker['custom_icon_size']['size'] ) ? $marker['custom_icon_size']['size'] : 40;
				}
			}

			$locations[] = $obj;

		}

		return $locations;

	}

	public function map_styles() {

		$gstandards = array(
			'standard'  => '[]',
			'silver'    => '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]',
			'retro'     => '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]',
			'dark'      => '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]',
			'night'     => '[{"elementType":"geometry","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#746855"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#242f3e"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#263c3f"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#6b9a76"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#38414e"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#212a37"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#9ca5b3"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#746855"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#1f2835"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#f3d19c"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2f3948"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#17263c"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#515c6d"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#17263c"}]}]',
			'aubergine' => '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]'
		);

		$snazzymaps = array(
			'simple'     => '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#6195a0"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":"0"},{"saturation":"0"},{"color":"#f5f5f2"},{"gamma":"1"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"lightness":"-3"},{"gamma":"1.00"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#bae5ce"},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#fac9a9"},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#787878"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.station.airport","elementType":"labels.icon","stylers":[{"hue":"#0a00ff"},{"saturation":"-77"},{"gamma":"0.57"},{"lightness":"0"}]},{"featureType":"transit.station.rail","elementType":"labels.text.fill","stylers":[{"color":"#43321e"}]},{"featureType":"transit.station.rail","elementType":"labels.icon","stylers":[{"hue":"#ff6c00"},{"lightness":"4"},{"gamma":"0.75"},{"saturation":"-68"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#eaf6f8"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c7eced"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-49"},{"saturation":"-53"},{"gamma":"0.79"}]}]',
			'colorful'   => '[{"featureType":"all","elementType":"all","stylers":[{"color":"#ff7000"},{"lightness":"69"},{"saturation":"100"},{"weight":"1.17"},{"gamma":"2.04"}]},{"featureType":"all","elementType":"geometry","stylers":[{"color":"#cb8536"}]},{"featureType":"all","elementType":"labels","stylers":[{"color":"#ffb471"},{"lightness":"66"},{"saturation":"100"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"gamma":0.01},{"lightness":20}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"saturation":-31},{"lightness":-33},{"weight":2},{"gamma":0.8}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"lightness":"-8"},{"gamma":"0.98"},{"weight":"2.45"},{"saturation":"26"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":30},{"saturation":30}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":20}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"lightness":20},{"saturation":-20}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":10},{"saturation":-30}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"saturation":25},{"lightness":25}]},{"featureType":"water","elementType":"all","stylers":[{"lightness":-20},{"color":"#ecc080"}]}]',
			'complex'    => '[{"elementType":"geometry","stylers":[{"hue":"#ff4400"},{"saturation":-68},{"lightness":-4},{"gamma":0.72}]},{"featureType":"road","elementType":"labels.icon"},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#0077ff"},{"gamma":3.1}]},{"featureType":"water","stylers":[{"hue":"#00ccff"},{"gamma":0.44},{"saturation":-33}]},{"featureType":"poi.park","stylers":[{"hue":"#44ff00"},{"saturation":-23}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"hue":"#007fff"},{"gamma":0.77},{"saturation":65},{"lightness":99}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"gamma":0.11},{"weight":5.6},{"saturation":99},{"hue":"#0091ff"},{"lightness":-86}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"lightness":-48},{"hue":"#ff5e00"},{"gamma":1.2},{"saturation":-23}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"saturation":-64},{"hue":"#ff9100"},{"lightness":16},{"gamma":0.47},{"weight":2.7}]}]',
			'dark'       => '[{"stylers":[{"hue":"#ff1a00"},{"invert_lightness":true},{"saturation":-100},{"lightness":33},{"gamma":0.5}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#2D333C"}]}]',
			'greyscale'  => '[{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}]',
			'light'      => '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#6195a0"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#e6f3d6"},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#f4d2c5"},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#f4f4f4"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#787878"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#eaf6f8"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#eaf6f8"}]}]',
			'monochrome' => '[{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"simplified"}]}]',
			'nolabels'   => '[{"elementType":"labels","stylers":[{"visibility":"off"},{"color":"#f49f53"}]},{"featureType":"landscape","stylers":[{"color":"#f9ddc5"},{"lightness":-7}]},{"featureType":"road","stylers":[{"color":"#813033"},{"lightness":43}]},{"featureType":"poi.business","stylers":[{"color":"#645c20"},{"lightness":38}]},{"featureType":"water","stylers":[{"color":"#1994bf"},{"saturation":-69},{"gamma":0.99},{"lightness":43}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#f19f53"},{"weight":1.3},{"visibility":"on"},{"lightness":16}]},{"featureType":"poi.business"},{"featureType":"poi.park","stylers":[{"color":"#645c20"},{"lightness":39}]},{"featureType":"poi.school","stylers":[{"color":"#a95521"},{"lightness":35}]},{},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#813033"},{"lightness":38},{"visibility":"off"}]},{},{},{},{},{},{},{},{},{},{},{},{"elementType":"labels"},{"featureType":"poi.sports_complex","stylers":[{"color":"#9e5916"},{"lightness":32}]},{},{"featureType":"poi.government","stylers":[{"color":"#9e5916"},{"lightness":46}]},{"featureType":"transit.station","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","stylers":[{"color":"#813033"},{"lightness":22}]},{"featureType":"transit","stylers":[{"lightness":38}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#f19f53"},{"lightness":-10}]},{},{},{}]',
			'twotone'    => '[{"stylers":[{"hue":"#007fff"},{"saturation":89}]},{"featureType":"water","stylers":[{"color":"#ffffff"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"visibility":"off"}]}]',
		);

		$styles['gstandards'] = $gstandards;
		$styles['snazzymaps'] = $snazzymaps;

		return $styles;
	}

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		extract($settings);

		$output = '';


		$widget_object->add_render_attribute( 'wrapper', array(
			'id'    => 'wdt-google-map-'.esc_attr( $widget_object->get_id() ),
			'class' => 'wdt-google-map-wrapper'
		) );

		$centerLatitude  = !empty( $settings['center_latitude'] ) ? $settings['center_latitude'] : '-37.737707';
		$centerLongitude = !empty( $settings['center_longitude'] ) ? $settings['center_longitude'] : '504.991808';

		$streetViewControl = ( $settings['street_view_control'] == "yes" ) ? true: false;
		$mapTypeControl    = ( $settings['map_type_control']    == "yes" ) ? true: false;
		$zoomControl       = ( $settings['zoom_control']        == "yes" ) ? true: false;
		$fullscreenControl = ( $settings['full_screen_control'] == "yes" ) ? true: false;
		$scaleControl      = ( $settings['scale_control']       == "yes" ) ? true: false;
		$rotateControl     = ( $settings['rotate_control']      == "yes" ) ? true: false;
		$scrollwheel       = ( $settings['scroll_zoom_control'] == "yes" ) ? true: false;
		$draggable         = ( $settings['draggable_control']   == "yes" ) ? true: false;

		$styles = '[]';
		$map_styles = $this->map_styles();
		if( $settings['theme'] == 'gstandards' ) {
			$styles = $map_styles['gstandards'][$settings['gstandards']];
		} elseif( $settings['theme'] == 'snazzymaps' ) {
			$styles = $map_styles['snazzymaps'][$settings['snazzymaps']];
		} elseif( $settings['theme'] == 'custom_theme' ) {
			$styles = $settings['custom_theme'];
		}

		$widget_object->add_render_attribute( 'map', array(
			'class'     => 'wdt-google-map',
			'data-options' => wp_json_encode( array(
				'mapTypeId'         => $settings['map_type'],
				'zoom'              => filter_var( $settings['zoom_level']['size'], FILTER_VALIDATE_INT ),
				'streetViewControl' => filter_var( $streetViewControl, FILTER_VALIDATE_BOOLEAN ),
				'mapTypeControl'    => filter_var( $mapTypeControl, FILTER_VALIDATE_BOOLEAN ),
				'zoomControl'       => filter_var( $zoomControl, FILTER_VALIDATE_BOOLEAN ),
				'fullscreenControl' => filter_var( $fullscreenControl, FILTER_VALIDATE_BOOLEAN ),
				'scaleControl'      => filter_var( $scaleControl, FILTER_VALIDATE_BOOLEAN ),
				'rotateControl'     => filter_var( $rotateControl, FILTER_VALIDATE_BOOLEAN ),
				'scrollwheel'       => filter_var( $scrollwheel, FILTER_VALIDATE_BOOLEAN ),
				'draggable'         => filter_var( $draggable, FILTER_VALIDATE_BOOLEAN ),
				'styles'            => $styles,
				'center'            => array(
					'lat' => filter_var( $centerLatitude, FILTER_VALIDATE_FLOAT ),
					'lng' => filter_var( $centerLongitude, FILTER_VALIDATE_FLOAT )
				),
			) ),
			'data-markers' => wp_json_encode( $this->map_markers( $settings['markers'] ) ),
			'data-marker-animation' => $settings['marker_animation'],
			'data-iw-max-width' => $settings['iw_max_width']['size']
		) );

		$map_markers_list = $this->map_markers($settings['markers']);

		$output .= '<div '.wedesigntech_html_output($widget_object->get_render_attribute_string( 'wrapper' )).'>';
			$output .= '<div '.wedesigntech_html_output($widget_object->get_render_attribute_string( 'map' )).'></div>';
			if(isset($settings['marker_additional_details']) && $settings['marker_additional_details'] == 'true') {
				if(is_array($map_markers_list) && !empty($map_markers_list)) {
					$output .= '<div class="wdt-google-map-marker-content-wrapper">';
						$output .= '<select class="wdt-google-map-marker-content-selection">';
							foreach($map_markers_list as $map_marker_list) {
								$output .= '<option value="'.esc_attr($map_marker_list['key']).'">'.esc_html($map_marker_list['title']).'</option>';
							}
						$output .= '</select>';
						$i = 0;
						foreach($map_markers_list as $map_marker_list) {
							$style_attr = 'style="display:none;"';
							if($i == 0) {
								$style_attr = '';
							}
							$i++;
							$output .= '<div id="wdt-google-map-marker-content-'.esc_attr($map_marker_list['key']).'" class="wdt-google-map-marker-content-item" '.$style_attr.'>';
								if($map_marker_list['marker_additional_details_type'] == 'template') {
									$frontend = Elementor\Frontend::instance();
									$output .= $frontend->get_builder_content($map_marker_list['marker_additional_details_template'], true);
								} else {
									$output .= $map_marker_list['marker_additional_details_default'];
								}
							$output .= '</div>';
						}
					$output .= '</div>';
				}
			}
		$output .= '</div>';


		return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_google_map' ) ) {
    function wedesigntech_widget_base_google_map() {
        return WeDesignTech_Widget_Base_Google_Map::instance();
    }
}