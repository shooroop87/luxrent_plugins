<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Image_Box {

	private static $_instance = null;

	private $cc_repeater_contents;
	private $cc_content_position;
	private $cc_layout;
	private $cc_style;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() {

		// Options
			$options_group = array( 'default' );
			$options['default'] = array(
				'image'         => esc_html__( 'Image', 'wdt-elementor-addon'),
				'icon'          => esc_html__( 'Icon', 'wdt-elementor-addon'),
				'title'         => esc_html__( 'Title', 'wdt-elementor-addon'),
				'sub_title'     => esc_html__( 'Sub Title', 'wdt-elementor-addon'),
				'description'   => esc_html__( 'Description', 'wdt-elementor-addon'),
				'link'          => esc_html__( 'Link', 'wdt-elementor-addon'),
				'button'        => esc_html__( 'Button', 'wdt-elementor-addon')
			);

		// Group 1 content positions
			$group1_content_position_elements = array(
				'image'           => esc_html__( 'Image', 'wdt-elementor-addon'),
				'icon'            => esc_html__( 'Icon', 'wdt-elementor-addon'),
				'title'           => esc_html__( 'Title', 'wdt-elementor-addon'),
				'sub_title'       => esc_html__( 'Sub Title', 'wdt-elementor-addon')
			);
			$group1_content_positions = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);


		// Group 2 content positions
			$group2_content_position_elements = array(
				'description'     => esc_html__( 'Description', 'wdt-elementor-addon'),
				'button'          => esc_html__( 'Button', 'wdt-elementor-addon')
			);
			$group2_content_positions = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

		// Content position elements
			$content_position_elements = array_merge($group1_content_position_elements, $group2_content_position_elements);

		// Module defaults
			$option_defaults = array(
				array(
					'item_type' => 'default',
					'media_image' => array (
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),
					'media_image_size' => 'full',
					'media_icon' => array (
						'value' => 'far fa-paper-plane',
						'library' => 'fa-regular'
					),
					'item_title' => esc_html__( 'Ut accumsan mass', 'wdt-elementor-addon' ),
					'item_sub_title' => esc_html__( 'Accumsan mass', 'wdt-elementor-addon' ),
					'item_description' => esc_html__( 'Donec sit amet turpis tincidunt eros, nam porttitor massa leo porta maecenas reque.', 'wdt-elementor-addon' ),
					'item_link'    => array (
						'url' => '#',
						'is_external' => true,
						'nofollow' => true,
						'custom_attributes' => ''
					),
					'item_button_text' => esc_html__( 'Click Here', 'wdt-elementor-addon' )
				),
				array(
					'item_type' => 'default',
					'media_image' => array (
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					),
					'media_image_size' => 'full',
					'media_icon' => array (
						'value' => 'far fa-paper-plane',
						'library' => 'fa-regular'
					),
					'item_title' => esc_html__( 'Pellentesque ornare', 'wdt-elementor-addon' ),
					'item_sub_title' => esc_html__( 'Tesque ornare', 'wdt-elementor-addon' ),
					'item_description' => esc_html__( 'Donec sit amet turpis tincidunt eros, nam porttitor massa leo porta maecenas reque.', 'wdt-elementor-addon' ),
					'item_link'    => array (
						'url' => '#',
						'is_external' => true,
						'nofollow' => true,
						'custom_attributes' => ''
					),
					'item_button_text' => esc_html__( 'Click Here', 'wdt-elementor-addon' )
				)
			);

		// Module Details
			$module_details = array(
				'content_positions' => array ( 'group1', 'group1_element_group', 'group2', 'group2_element_group', 'title_subtitle_position'),
				'group1_title'    => esc_html__( 'Image Group', 'wdt-elementor-addon'),
				'group2_title'    => esc_html__( 'Content Group', 'wdt-elementor-addon'),
				'group_cp_label'    => esc_html__( 'Content Positions', 'wdt-elementor-addon'),
				'group_eg_cp_label' => esc_html__( 'Element Group - Content Positions', 'wdt-elementor-addon'),
				'jsSlug'          => 'wdtRepeaterImageBoxContent',
				'title'           => esc_html__( 'Carousel Items', 'wdt-elementor-addon' ),
				'icon_default_library' => array (
					'value'               => 'far fa-paper-plane',
					'library'             => 'fa-regular'
				),
				'description'     => ''
			);

		// Initialize depandant class
			$this->cc_repeater_contents = new WeDesignTech_Common_Controls_Repeater_Contents($options_group, $options, $option_defaults, $module_details);
			$this->cc_layout = new WeDesignTech_Common_Controls_Layout('both');
			$this->cc_style = new WeDesignTech_Common_Controls_Style();

	}

	public function name() {
		return 'wdt-image-box';
	}

	public function title() {
		return esc_html__( 'Image Box', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array_merge(
			$this->cc_layout->init_styles(),
			$this->cc_repeater_contents->init_styles(),
			array (
				$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/image-box/assets/css/style.css',
				'jquery.magnific-image-box-popup' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/image-box/assets/css/jquery.magnific-popup.css'
			)
		);
	}

	public function init_inline_styles() {
		if(!\Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return array (
				$this->name() => $this->cc_layout->get_column_css()
			);
		}
		return array ();
	}

	public function init_scripts() {
		return array_merge(
			$this->cc_layout->init_scripts(),
			array (
				'jquery.cookie' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/image-box/assets/js/jquery.cookie.min.js',
				'jquery.magnific-image-box-popup' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/image-box/assets/js/jquery.magnific-popup.min.js',
				$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/image-box/assets/js/script.js'
			)
		);
	}

	public function create_elementor_controls($elementor_object) {

		$this->cc_repeater_contents->get_controls($elementor_object);
        $this->cc_layout->get_controls($elementor_object);

		$elementor_object->start_controls_section( 'wdt_section_settings', array(
			'label' => esc_html__( 'Settings', 'wdt-elementor-addon'),
		) );

			$elementor_object->add_control( 'template', array(
				'label'   => esc_html__( 'Template', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'default' => 'default',
				'options' => array(
					'default'  => esc_html__( 'Default', 'wdt-elementor-addon' ),
					'standard' => esc_html__( 'Standard', 'wdt-elementor-addon' ),
					'icon-aside-content' => esc_html__( 'Icon Aside Content', 'wdt-elementor-addon' ),
					'process-block' => esc_html__( 'Process Icon Block', 'wdt-elementor-addon' ),
					'review-block' => esc_html__( 'Review Block', 'wdt-elementor-addon' ),
					'with-description' => esc_html__( 'With Description', 'wdt-elementor-addon' ),
					'icon-block' => esc_html__( 'Icon Block', 'wdt-elementor-addon' ),
					'process-block-2' => esc_html__( 'Process Image Block', 'wdt-elementor-addon' ),
					'process-block-3' => esc_html__( 'Work Process Block', 'wdt-elementor-addon' ),
					'custom-block' => esc_html__( 'Custom Block', 'wdt-elementor-addon' ),
					'modern_image' => esc_html__( 'Modern Image', 'wdt-elementor-addon' )
				)
			) );

			$elementor_object->add_control( 'show_image_popup', array(
				'label'   => esc_html__( 'Show image in popup', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wdt-elementor-addon' ),
                'label_off'    => esc_html__( 'No', 'wdt-elementor-addon' ),
				'return_value' => 'yes',
                'default'      => ''
			) );

			$elementor_object->add_control( 'enable_hover_class', array(
				'label'   => esc_html__( 'Enable hover class name', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wdt-elementor-addon' ),
                'label_off'    => esc_html__( 'No', 'wdt-elementor-addon' ),
				'return_value' => 'yes',
                'default'      => ''
			) );

		$elementor_object->end_controls_section();

		// Item
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'item',
			'title' => esc_html__( 'Item', 'wdt-elementor-addon' ),
			'styles' => array (
				'alignment' => array (
					'field_type' => 'alignment',
                    'control_type' => 'responsive',
                    'default' => 'center',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};'
					),
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'tabs' => array (
					'field_type' => 'tabs',
					'tab_items' => array (
						'normal' => array (
							'title' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
							'styles' => array (
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-content-item',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-content-item',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-content-item',
									'condition' => array ()
								)
							)
						),
						'hover' => array (
							'title' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
							'styles' => array (
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover',
									'condition' => array ()
								)
							)
						)
					)
				)
			)
		));

		// Title
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'title',
			'title' => esc_html__( 'Title', 'wdt-elementor-addon' ),
			'styles' => array (
				'typography' => array (
					'field_type' => 'typography',
					'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-title h5',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'tabs' => array (
					'field_type' => 'tabs',
					'tab_items' => array (
						'normal' => array (
							'title' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
							'styles' => array (
								'color' => array (
									'field_type' => 'color',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item .wdt-content-title h5, 
										 {{WRAPPER}} .wdt-content-item .wdt-content-title h5 > a' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
							)
						),
						'hover' => array (
							'title' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
							'styles' => array (
								'color' => array (
									'field_type' => 'color',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item:hover .wdt-content-title h5 > a:hover' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
							)
						)
					)
				)
			)
		));

		// Sub Title
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'subtitle',
			'title' => esc_html__( 'Sub Title', 'wdt-elementor-addon' ),
			'styles' => array (
				'typography' => array (
					'field_type' => 'typography',
					'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-subtitle',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'color' => array (
					'field_type' => 'color',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-subtitle' => 'color: {{VALUE}};'
					),
					'condition' => array ()
				)
			)
		));

		// Image
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'image',
			'title' => esc_html__( 'Image', 'wdt-elementor-addon' ),
			'styles' => array (
				'alignment' => array (
					'field_type' => 'alignment',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper, 
						 {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}; justify-items: {{VALUE}};'
					),
					'condition' => array ()
				),
				'width' => array (
					'field_type' => 'width',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > span, 
						 {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > a' => 'width: {{SIZE}}{{UNIT}};'
                    ),
					'condition' => array ()
				),
				'height' => array (
					'field_type' => 'height',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > span, 
						 {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > a' => 'height: {{SIZE}}{{UNIT}};'
                    ),
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > span, 
						 {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'border' => array (
					'field_type' => 'border',
					'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > span, 
					  			   {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > a',
					'condition' => array ()
				),
				'border_radius' => array (
					'field_type' => 'border_radius',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > span, 
						 {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'box_shadow' => array (
					'field_type' => 'box_shadow',
					'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > span, 
					    		   {{WRAPPER}} .wdt-content-item .wdt-content-image-wrapper .wdt-content-image > a',
					'condition' => array ()
				)
			)
		));

		// Icon
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'icon',
			'title' => esc_html__( 'Icon', 'wdt-elementor-addon' ),
			'styles' => array (
				'font_size' => array (
					'field_type' => 'font_size',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span' => 'font-size: {{SIZE}}{{UNIT}};'
                    ),
					'condition' => array ()
				),
				'width' => array (
					'field_type' => 'width',
					'default' => array (
						'unit' => 'px'
					),
					'size_units' => array ( 'px' ),
					'range' => array (
                        'px' => array (
                            'min' => 10,
                            'max' => 500,
                        )
                    ),
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span' => 'width: {{SIZE}}{{UNIT}};'
					)
				),
				'height' => array (
					'field_type' => 'height',
					'default' => array (
						'unit' => 'px'
					),
					'size_units' => array ( 'px' ),
					'range' => array (
                        'px' => array (
                            'min' => 10,
                            'max' => 500,
                        )
                    ),
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span' => 'height: {{SIZE}}{{UNIT}};'
					)
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'tabs_default' => array (
					'field_type' => 'tabs',
					'unique_key' => 'default',
					'tab_items' => array (
						'normal' => array (
							'title' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
							'styles' => array (
								'color' => array (
									'field_type' => 'color',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}}  .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-icon-wrapper .wdt-content-icon span',
									'condition' => array ()
								)
							)
						),
						'hover' => array (
							'title' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
							'styles' => array (
								'color' => array (
									'field_type' => 'color',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item:hover .wdt-content-icon-wrapper .wdt-content-icon span' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover .wdt-content-icon-wrapper .wdt-content-icon span',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover .wdt-content-icon-wrapper .wdt-content-icon span',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item:hover .wdt-content-icon-wrapper .wdt-content-icon span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover .wdt-content-icon-wrapper .wdt-content-icon span',
									'condition' => array ()
								)
							)
						)
					)
				)
			)
		));

		// Description
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'description',
			'title' => esc_html__( 'Description', 'wdt-elementor-addon' ),
			'styles' => array (
				'typography' => array (
					'field_type' => 'typography',
					'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-description',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'color' => array (
					'field_type' => 'color',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-description' => 'color: {{VALUE}};'
					),
					'condition' => array ()
				)
			)
		));

		// Button
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'button',
			'title' => esc_html__( 'Button', 'wdt-elementor-addon' ),
			'styles' => array (
				'typography' => array (
					'field_type' => 'typography',
					'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-button > a',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-content-item .wdt-content-button > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-content-item .wdt-content-button > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'tabs' => array (
					'field_type' => 'tabs',
					'tab_items' => array (
						'normal' => array (
							'title' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
							'styles' => array (
								'color' => array (
									'field_type' => 'color',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item .wdt-content-button > a' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-button > a',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-button > a',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item .wdt-content-button > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-content-item .wdt-content-button > a',
									'condition' => array ()
								)
							)
						),
						'hover' => array (
							'title' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
							'styles' => array (
								'color' => array (
									'field_type' => 'color',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:focus, 
										 {{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:hover' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:focus, 
									 			   {{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:hover',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:focus, 
												   {{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:hover',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:focus, 
										 {{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:focus, 
												   {{WRAPPER}} .wdt-content-item:hover .wdt-content-button > a:hover',
									'condition' => array ()
								)
							)
						)
					)
				)
			)
		));

		// Carousel
        $this->cc_layout->get_carousel_style_controls($elementor_object, array ('layout' => 'carousel'));

	}

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		$output = '';

		if( count( $settings['item_contents'] ) > 0 ):

			$classes = array ();
			array_push($classes, 'wdt-rc-template-'.$settings['template']);

			$settings['module_id'] = $widget_object->get_id();
			$settings['module_class'] = 'image-box';
			$settings['custom_attributes'] = array (
				'enable_popup' => $settings['show_image_popup'],
				'enable_hover_class' => $settings['enable_hover_class']
			);
			if($settings['show_image_popup'] == true) {
				array_push($classes, 'wdt-image-lightbox-popup');
			}
			if($settings['enable_hover_class'] == true) {
				array_push($classes, 'wdt-image-active-class');
			}
			$settings['classes'] = $classes;
			$this->cc_layout->set_settings($settings);
			$settings['module_layout_class'] = $this->cc_layout->get_item_class();

			$output .= $this->cc_layout->get_wrapper_start();
				if($settings['template'] == 'default') {

					$group1_content_position_elements = array(
						'icon' => esc_html__( 'Icon', 'wdt-elementor-addon'),
						'title_sub_title' 		  => esc_html__( 'Title & Subtitle', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'description'	  => esc_html__( 'Description', 'wdt-elementor-addon'),
						'button' 		  => esc_html__( 'Button', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				} else if($settings['template'] == 'standard') {

					$group1_content_position_elements = array(
						'image'           => esc_html__( 'Image', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'sub_title' 	  => esc_html__( 'Sub Title', 'wdt-elementor-addon'),
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'description'     => esc_html__( 'Description', 'wdt-elementor-addon'),
						'button'          => esc_html__( 'Button', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				} else if($settings['template'] == 'icon-aside-content') {

					$group1_content_position_elements = array(
						'icon' => esc_html__( 'Icon', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'description'     => esc_html__( 'Description', 'wdt-elementor-addon'),
						'button'          => esc_html__( 'Button', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				} else if($settings['template'] == 'process-block') {

					$group1_content_position_elements = array(
						'icon' => esc_html__( 'Icon', 'wdt-elementor-addon'),
						'image' => esc_html__( 'Image', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'description'     => esc_html__( 'Description', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				}  else if($settings['template'] == 'review-block') {

					$group1_content_position_elements = array(
						'image'           => esc_html__( 'Image', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				} else if($settings['template'] == 'with-description') {

					$group1_content_position_elements = array(
						'image'           => esc_html__( 'Image', 'wdt-elementor-addon'),
						'icon'            => esc_html__( 'Icon', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'sub_title' 	  => esc_html__( 'Sub Title', 'wdt-elementor-addon'),
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'description'     => esc_html__( 'Description', 'wdt-elementor-addon'),
						'button'          => esc_html__( 'Button', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					if(isset($settings['media_image_type'])) {
						$settings['media_image_type'] = 'cover';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);
				}
				else if($settings['template'] == 'process-block-2') {

					$group1_content_position_elements = array(
						'image' => esc_html__( 'Image', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'description'     => esc_html__( 'Description', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				}
				else if($settings['template'] == 'icon-block') {
					$group1_content_position_elements = array(
						'icon' => esc_html__( 'Icon', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				}
				else if($settings['template'] == 'process-block-3') {
					$group1_content_position_elements = array(
						'image' => esc_html__( 'Image', 'wdt-elementor-addon'),
						'icon' => esc_html__( 'Icon', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'description'     => esc_html__( 'Description', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				}
				else if($settings['template'] == 'custom-block') {
					$group1_content_position_elements = array(
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon'),
						'button' => esc_html__( 'Button', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
						'sub_title' 		  => esc_html__( 'Sub Title', 'wdt-elementor-addon'),
						'image'     => esc_html__( 'Image', 'wdt-elementor-addon')
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);

				}
				else if($settings['template'] == 'modern_image') {
					$group1_content_position_elements = array(
						'image'     => esc_html__( 'Image', 'wdt-elementor-addon'),
						'title' 		  => esc_html__( 'Title', 'wdt-elementor-addon')
					);
					$group2_content_position_elements = array(
					);

					$settings['group1_content_positions'] = wedesigntech_elementor_format_repeater_values($group1_content_position_elements);
					$settings['group2_content_positions'] = wedesigntech_elementor_format_repeater_values($group2_content_position_elements);

					if(!isset($settings['title_subtitle_position'])) {
						$settings['title_subtitle_position'] = 'below';
					}

					$output .= $this->cc_repeater_contents->render_html($widget_object, $settings);
				}
                $output .= $this->cc_layout->get_column_edit_mode_css();
			$output .= $this->cc_layout->get_wrapper_end();

		else:
			$output .= '<div class="wdt-image-box-container no-records">';
				$output .= esc_html__('No records found!', 'wdt-elementor-addon');
			$output .= '</div>';
		endif;

		return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_image_box' ) ) {
    function wedesigntech_widget_base_image_box() {
        return WeDesignTech_Widget_Base_Image_Box::instance();
    }
}