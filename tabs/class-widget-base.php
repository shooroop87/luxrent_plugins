<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Tabs {

	private static $_instance = null;

	private $cc_repeater_contents;
	private $cc_style;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() {

		// Options
			$options_group = array( 'default', 'template' );
			$options['default'] = array(
				'icon'           => esc_html__( 'Icon', 'wdt-elementor-addon'),
				'title'          => esc_html__( 'Title', 'wdt-elementor-addon'),
				'description'    => esc_html__( 'Description', 'wdt-elementor-addon')
			);
			$options['template'] = array(
				'icon'           => esc_html__( 'Icon', 'wdt-elementor-addon'),
				'image'          => esc_html__( 'Image', 'wdt-elementor-addon'),
				'title'          => esc_html__( 'Title', 'wdt-elementor-addon')
			);

		// Module defaults
			$option_defaults = array(
				array(
					'item_type' => 'default',
					'media_icon' => array (
						'value' => 'fas fa-star',
						'library' => 'fa-solid'
					),
					'media_icon_style' => 'default',
					'media_icon_shape' => 'circle',
					'item_title' => esc_html__( 'Ut accumsan mass', 'wdt-elementor-addon' ),
					'item_description' => esc_html__( 'Donec sed lectus mi. Vestibulum et augue ultricies, tempus augue non, consectetur est. In arcu justo, pulvinar sit amet turpis id, tincidunt fermentum eros. Nam porttitor massa ac leo porta congue nec at leo. Maecenas rutrum, neque bibendum vestibulum imperdiet, ex tellus molestie ante, at semper justo neque vel nisi. In tellus felis, suscipit pellentesque imperdiet sit amet, posuere nec sem. Sed at fringilla justo. Fusce dictum condimentum turpis vitae interdum.', 'wdt-elementor-addon' )
				),
				array(
					'item_type' => 'default',
					'media_icon' => array (
						'value' => 'fas fa-star',
						'library' => 'fa-solid'
					),
					'media_icon_style' => 'default',
					'media_icon_shape' => 'circle',
					'item_title' => esc_html__( 'Pellentesque ornare', 'wdt-elementor-addon' ),
					'item_sub_title' => esc_html__( 'Tesque ornare', 'wdt-elementor-addon' ),
					'item_description' => esc_html__( 'Vestibulum et augue ultricies, tempus augue non, consectetur est. In arcu justo, pulvinar sit amet turpis id, tincidunt fermentum eros. Nam porttitor massa ac leo porta congue nec at leo. Maecenas rutrum, neque bibendum vestibulum imperdiet, ex tellus molestie ante, at semper justo neque vel nisi. In tellus felis, suscipit pellentesque imperdiet sit amet, posuere nec sem. Sed at fringilla justo. Fusce dictum condimentum turpis vitae interdum.', 'wdt-elementor-addon' )
				)
			);

		// Module Details
			$module_details = array (
				'content_positions' => array ( 'group1', 'group1_element_group', 'group2', 'group2_element_group'),
				'group1_title'    => esc_html__( 'Image Group', 'wdt-elementor-addon'),
				'group2_title'    => esc_html__( 'Content Group', 'wdt-elementor-addon'),
				'group_cp_label'    => esc_html__( 'Content Positions', 'wdt-elementor-addon'),
				'group_eg_cp_label' => esc_html__( 'Element Group - Content Positions', 'wdt-elementor-addon'),
				'jsSlug'          => 'wdtRepeaterTabsContent',
				'title'           => esc_html__( 'Tab Items', 'wdt-elementor-addon' ),
				'description'     => ''
			);

		// Initialize depandant class
			$this->cc_repeater_contents = new WeDesignTech_Common_Controls_Repeater_Contents($options_group, $options, $option_defaults, $module_details);
			$this->cc_style = new WeDesignTech_Common_Controls_Style();

	}

	public function name() {
		return 'wdt-tabs';
	}

	public function title() {
		return esc_html__( 'Tabs', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array_merge(
			$this->cc_repeater_contents->init_styles(),
			array (
				//'scrolltabs' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/tabs/assets/css/scrolltabs.css',
				$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/tabs/assets/css/style.css'
			)
		);
	}

	public function init_inline_styles() {
		return array ();
	}

	public function init_scripts() {
		return array (
			'jquery-ui-tabs' => '',
			'jquery.scrolltabs' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/tabs/assets/js/jquery.scrolltabs.min.js',
			$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/tabs/assets/js/script.js'
		);
	}

	public function create_elementor_controls($elementor_object) {

		$this->cc_repeater_contents->get_controls($elementor_object);

		$elementor_object->start_controls_section( 'wdt_section_settings', array(
			'label' => esc_html__( 'Settings', 'wdt-elementor-addon'),
		) );

			$elementor_object->add_control( 'layout', array(
				'label'   => esc_html__( 'Layout', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'wdt-elementor-addon' ),
					'vertical' => esc_html__( 'Vertical', 'wdt-elementor-addon' ),
				)
			) );

			$elementor_object->add_control( 'template', array(
				'label'   => esc_html__( 'Template', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'  => esc_html__( 'Default', 'wdt-elementor-addon' ),
					'bordered' => esc_html__( 'Bordered', 'wdt-elementor-addon' ),
					'modern' => esc_html__( 'Modern', 'wdt-elementor-addon' ),
					'classic' => esc_html__( 'Classic', 'wdt-elementor-addon' ),
					'minimal' => esc_html__( 'Minimal', 'wdt-elementor-addon' ),
					'hunch-back-icon' => esc_html__( 'Hunch Back Icon', 'wdt-elementor-addon' )
				)
			) );

			$elementor_object->add_control(
				'icon_show',
				array(
					'label'              => esc_html__( 'Show Icon', 'wdt-elementor-addon' ),
					'type'               => \Elementor\Controls_Manager::SWITCHER,
					'frontend_available' => true,
					'default'            => 'true',
					'return_value'       => 'true',
					'condition' => array (
						'template!' => 'hunch-back-icon'
					)
				)
			);

			$elementor_object->add_control( 'icon_style', array(
				'label'   => esc_html__( 'Icon Style', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'block',
				'options' => array(
					'block'  => esc_html__( 'Block', 'wdt-elementor-addon' ),
					'inline' => esc_html__( 'Inline', 'wdt-elementor-addon' ),
				),
				'condition' => array (
					'template!' => 'hunch-back-icon',
					'icon_show' => 'true'
				)
			) );
			$elementor_object->add_control( 'content_display', array(
				'label'   => esc_html__( 'Content Display', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'click',
				'options' => array(
					'click'  => esc_html__( 'Click', 'wdt-elementor-addon' ),
					'hover' => esc_html__( 'Hover', 'wdt-elementor-addon' )
				)
			) );

			$elementor_object->add_control( 'icon_position', array(
				'label'   => esc_html__( 'Icon Position', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'before-title',
				'options' => array(
					'before-title' => array (
						'title' => esc_html__( 'Before Title', 'wdt-elementor-addon' ),
						'icon' => 'eicon-h-align-left',
					),
					'after-title' => array (
						'title' => esc_html__( 'After Title', 'wdt-elementor-addon' ),
						'icon' => 'eicon-h-align-right',
					)
				),
				'condition' => array (
					'template!' => 'hunch-back-icon',
					'icon_show' => 'true',
					'icon_style' => 'inline'
				)
			) );

		$elementor_object->end_controls_section();


		// Tab Title / Icon
			$this->cc_style->get_style_controls($elementor_object, array (
				'slug' => 'tab_title_icon',
				'title' => esc_html__( 'Tab Title / Icon', 'wdt-elementor-addon' ),
				'styles' => array (
					'alignment' => array (
						'field_type' => 'alignment',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-list' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}; justify-items: {{VALUE}};'
						),
						'condition' => array ()
					),
					'margin' => array (
						'field_type' => 'margin',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-list li .ui-tabs-anchor' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'padding' => array (
						'field_type' => 'padding',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-list li .ui-tabs-anchor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'typography' => array (
						'field_type' => 'typography',
						'selector' => '{{WRAPPER}} .wdt-tabs-container .wdt-tabs-list li .ui-tabs-anchor',
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
											'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-list li .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li' => 'color: {{VALUE}};',
											'{{WRAPPER}} .wdt-tabs-container[class*="-template-minimal"] .wdt-tabs-list li:before' => 'background-color: {{VALUE}};'
										),
										'condition' => array ()
									),
									'background' => array (
										'field_type' => 'background',
										'selector' => '{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list-wrapper .wdt-hunch-back-icon-border, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li',
										'color_selector' => array (
											'{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list-wrapper .wdt-hunch-back-icon-border, {{WRAPPER}} .wdt-tabs-container[class*="-template-minimal"] .wdt-tabs-list:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li' => 'background-color: {{VALUE}};'
										),
										'condition' => array ()
									),
									'border' => array (
										'field_type' => 'border',
										'selector' => '{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li',
										'condition' => array ()
									),
									'border_radius' => array (
										'field_type' => 'border_radius',
										'selector' => array (
											'{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										),
										'condition' => array ()
									),
									'box_shadow' => array (
										'field_type' => 'box_shadow',
										'selector' => '{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li',
										'condition' => array ()
									)
								)
							),
							'hover' => array (
								'title' => esc_html__( 'Hover / Active', 'wdt-elementor-addon' ),
								'styles' => array (
									'color' => array (
										'field_type' => 'color',
										'selector' => array (
											'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-list li.ui-state-active .ui-tabs-anchor, .wdt-tabs-container .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover' => 'color: {{VALUE}};',
											'{{WRAPPER}} .wdt-tabs-container[class*="-template-bordered"] .wdt-tabs-list li .ui-tabs-anchor::before, {{WRAPPER}} .wdt-tabs-container[class*="-template-bordered"] .wdt-tabs-list li .ui-tabs-anchor::after, {{WRAPPER}} .wdt-tabs-container[class*="-template-classic"] .wdt-tabs-list li .ui-tabs-anchor:before' => 'background-color: {{VALUE}};',
											'{{WRAPPER}} .wdt-tabs-container[class*="-template-bordered"] .wdt-tabs-list li.ui-state-active .ui-tabs-anchor:after, {{WRAPPER}} .wdt-tabs-container[class*="-template-bordered"] .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor::after' => 'background: transparent;'
										),
										'condition' => array ()
									),
									'background' => array (
										'field_type' => 'background',
										'selector' => '{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-active .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-active .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover',
										'color_selector' => array (
											'{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-active .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container .wdt-tabs-list:before, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-active .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover' => 'background-color: {{VALUE}};'
										),
										'condition' => array ()
									),
									'border' => array (
										'field_type' => 'border',
										'selector' => '{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-active .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-active .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover',
										'condition' => array ()
									),
									'border_radius' => array (
										'field_type' => 'border_radius',
										'selector' => array (
											'{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-active .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-active .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										),
										'condition' => array ()
									),
									'box_shadow' => array (
										'field_type' => 'box_shadow',
										'selector' => '{{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-active .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container:not([class*="-template-hunch-back-icon"]) .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-active .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-template-hunch-back-icon"] .wdt-tabs-list li.ui-state-hover .ui-tabs-anchor:before, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li.wdt-active, {{WRAPPER}} .wdt-tabs-container[class*="-tabs-toggle-mode"] .wdt-tabs-list-wrapper .wdt-tabs-list li:hover',
										'condition' => array ()
									)
								)
							)
						)
					)
				)
			));


		// Tab Content
			$this->cc_style->get_style_controls($elementor_object, array (
				'slug' => 'tab_content',
				'title' => esc_html__( 'Tab Content', 'wdt-elementor-addon' ),
				'styles' => array (
					'alignment' => array (
						'field_type' => 'alignment',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}; justify-items: {{VALUE}};'
						),
						'condition' => array ()
					),
                    'vertical_align' => array (
                        'field_type' => 'vertical_align',
                        'label' => esc_html__( 'Vertical Position', 'wdt-elementor-addon' ),
                        'options' => array (
                            'start' => array (
                                'title' => esc_html__( 'Start', 'wdt-elementor-addon' ),
                                'icon' => 'eicon-v-align-top',
                            ),
                            'center' => array (
                                'title' => esc_html__( 'Center', 'wdt-elementor-addon' ),
                                'icon' => 'eicon-v-align-middle',
                            ),
                            'end' => array (
                                'title' => esc_html__( 'End', 'wdt-elementor-addon' ),
                                'icon' => 'eicon-v-align-bottom',
                            )
                        ),
                        'default' => 'center',
                        'selector' => array (
                            '{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper' => 'align-items: {{VALUE}};'
                        ),
                        'condition' => array (
                            'layout' => 'vertical'
                        )
                    ),
					'margin' => array (
						'field_type' => 'margin',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'padding' => array (
						'field_type' => 'padding',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'typography' => array (
						'field_type' => 'typography',
						'selector' => '{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper',
						'condition' => array ()
					),
					'color' => array (
						'field_type' => 'color',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper' => 'color: {{VALUE}};'
						),
						'condition' => array ()
					),
					'background' => array (
						'field_type' => 'background',
						'selector' => '{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper',
						'condition' => array ()
					),
					'border' => array (
						'field_type' => 'border',
						'selector' => '{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper',
						'condition' => array ()
					),
					'border_radius' => array (
						'field_type' => 'border_radius',
						'selector' => array (
							'{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'box_shadow' => array (
						'field_type' => 'box_shadow',
						'selector' => '{{WRAPPER}} .wdt-tabs-container .wdt-tabs-content-wrapper',
						'condition' => array ()
					)
				)
			));

	}

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		$output = '';

		if( count( $settings['item_contents'] ) > 0 ):

			$settings['module_id'] = $widget_object->get_id();
			$settings['module_class'] = 'tabs';

			$classes = array (
				'wdt-layout-'.$settings['layout'],
				'wdt-template-'.$settings['template']
			);

			if($settings['icon_show'] == 'true') {
				array_push($classes, 'wdt-icon-style-'.$settings['icon_style']);
				if(isset($settings['icon_position'])) {
					array_push($classes, 'wdt-icon-position-'.$settings['icon_position']);
				}
			}
			if( $settings['content_display'] == 'hover' ) {
				array_push($classes, 'wdt-tabs-hover');
			} else {
				array_push($classes, 'wdt-tabs-click');
			}

			$output .= '<div class="wdt-tabs-container '.esc_attr(implode(' ', $classes)).'" data-class-items="'.esc_attr(implode(' ', $classes)).'">';
				$output .= '<div class="wdt-tabs-list-wrapper">';
					$output .= '<ul class="wdt-tabs-list">';
						foreach( $settings['item_contents'] as $key => $item ) {
							$output .= '<li><a href="#wdt-tabs-'.esc_attr($key).'">';
								if( $item['item_type'] == 'template' ) {
									if($settings['template'] == 'hunch-back-icon') {
										$output .= $this->cc_repeater_contents->render_template_icon($key, $item, $widget_object);
									} else {
										if($settings['icon_show'] == 'true') {
											$output .= $this->cc_repeater_contents->render_template_icon($key, $item, $widget_object);
										}
										if($item['media_image_template']['url'] != '') {
											$output .= '<div class="wdt-content-image"><img src="'.esc_url($item['media_image_template']['url']).'" alt="'.esc_attr($item['item_title']).'"></div>';
										}
										$output .= '<div class="wdt-content-title">'.esc_html($item['item_title']).'</div>';
									}
								} else {
									if($settings['template'] == 'hunch-back-icon') {
										$output .= $this->cc_repeater_contents->render_icon($key, $item, $widget_object);
									} else {
										if($settings['icon_show'] == 'true') {
											$output .= $this->cc_repeater_contents->render_icon($key, $item, $widget_object);
										}
										$output .= '<div class="wdt-content-title">'.esc_html($item['item_title']).'</div>';
									}
								}
							$output .= '</a></li>';
						}
					$output .= '</ul>';
					if($settings['template'] == 'hunch-back-icon') {
						$output .= '<div class="wdt-hunch-back-icon-border"></div>';
					}
				$output .= '</div>';
				$output .= '<div class="wdt-tabs-content-wrapper">';
					foreach( $settings['item_contents'] as $key => $item ) {
						$output .= '<div id="wdt-tabs-'.esc_attr($key).'" class="wdt-tabs-content">';
							if( $item['item_type'] == 'template' ) {
								$frontend = Elementor\Frontend::instance();
								$output .= $frontend->get_builder_content( $item['item_template'], true );
							} else {
								$output .= $item['item_description'];
							}
						$output .= '</div>';
					}
				$output .= '</div>';
			$output .= '</div>';

		else:
			$output .= '<div class="wdt-tabs-container no-records">';
				$output .= esc_html__('No records found!', 'wdt-elementor-addon');
			$output .= '</div>';
		endif;

		return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_tabs' ) ) {
    function wedesigntech_widget_base_tabs() {
        return WeDesignTech_Widget_Base_Tabs::instance();
    }
}