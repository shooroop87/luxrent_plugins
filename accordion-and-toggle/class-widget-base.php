<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Accordion_And_Toggle {

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
			$options['template'] = array(
				'title' => esc_html__( 'Title', 'wdt-elementor-addon'),
				'custom'      => array (
					'control_action' => 'wdt_widgets_custom_accordion_toggle_control'
				)
			);
			$options['default'] = array(
				'title'        => esc_html__( 'Title', 'wdt-elementor-addon'),
				'description'  => esc_html__( 'Description', 'wdt-elementor-addon'),
				'custom'      => array (
					'control_action' => 'wdt_widgets_custom_accordion_toggle_control'
				)
			);

		// Module defaults
			$option_defaults = array(
				array(
					'item_title' => esc_html__( 'Ut accumsan mass', 'wdt-elementor-addon' ),
					'item_description' => esc_html__( 'Donec sed lectus mi. Vestibulum et augue ultricies, tempus augue non, consectetur est. In arcu justo, pulvinar sit amet turpis id, tincidunt fermentum eros. Nam porttitor massa ac leo porta congue nec at leo. Maecenas rutrum, neque bibendum vestibulum imperdiet, ex tellus molestie ante, at semper justo neque vel nisi. In tellus felis, suscipit pellentesque imperdiet sit amet, posuere nec sem. Sed at fringilla justo. Fusce dictum condimentum turpis vitae interdum.', 'wdt-elementor-addon' )
				),
				array(
					'item_title' => esc_html__( 'Pellentesque ornare', 'wdt-elementor-addon' ),
					'item_description' => esc_html__( 'Donec sed lectus mi. Vestibulum et augue ultricies, tempus augue non, consectetur est. In arcu justo, pulvinar sit amet turpis id, tincidunt fermentum eros. Nam porttitor massa ac leo porta congue nec at leo. Maecenas rutrum, neque bibendum vestibulum imperdiet, ex tellus molestie ante, at semper justo neque vel nisi. In tellus felis, suscipit pellentesque imperdiet sit amet, posuere nec sem. Sed at fringilla justo. Fusce dictum condimentum turpis vitae interdum.', 'wdt-elementor-addon' )
				)
			);

		// Module Details
			$module_details = array(
				'title'       => esc_html__( 'Items', 'wdt-elementor-addon' ),
				'description' => ''
			);

		// Initialize depandant class
			$this->cc_repeater_contents = new WeDesignTech_Common_Controls_Repeater_Contents($options_group, $options, $option_defaults, $module_details);
			$this->cc_style = new WeDesignTech_Common_Controls_Style();

		// Actions
			add_action('wdt_widgets_custom_accordion_toggle_control', array ( $this, 'wdt_widgets_custom_accordion_toggle_control_register' ), 10, 1);

	}

	public function name() {
		return 'wdt-accordion-and-toggle';
	}

	public function title() {
		return esc_html__( 'Accordion And Toggle', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array (
			$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/accordion-and-toggle/assets/css/style.css'
		);
	}

	public function init_inline_styles() {
		return array ();
	}

	public function init_scripts() {
		return array (
			'jquery-ui-accordion' =>  '',
			$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/accordion-and-toggle/assets/js/script.js'
		);
	}

	public function create_elementor_controls($elementor_object) {

		$this->cc_repeater_contents->get_controls($elementor_object);

		$elementor_object->start_controls_section( 'wdt_section_settings', array(
			'label' => esc_html__( 'Settings', 'wdt-elementor-addon'),
		) );

			$elementor_object->add_control( 'module', array(
				'label'   => esc_html__( 'Module', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'accordion',
				'options' => array(
					'accordion'  => esc_html__( 'Accordion', 'wdt-elementor-addon' ),
					'toggle' => esc_html__( 'Toggle', 'wdt-elementor-addon' )
				)
			) );

			$elementor_object->add_control( 'template', array(
				'label'   => esc_html__( 'Template', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'  => esc_html__( 'Default', 'wdt-elementor-addon' ),
					'bordered' => esc_html__( 'Bordered', 'wdt-elementor-addon' ),
					'simple' => esc_html__( 'Simple', 'wdt-elementor-addon' ),
					'classic' => esc_html__( 'Classic', 'wdt-elementor-addon' )
				)
			) );

			$elementor_object->add_control(
				'title_prefix',
				array (
					'label' => esc_html__( 'Title Prefix', 'wdt-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => array (
						''  => esc_html__( 'None', 'wdt-elementor-addon' ),
						'number'   => esc_html__( 'Number', 'wdt-elementor-addon' ),
						'question'   => esc_html__( 'Question', 'wdt-elementor-addon' ),
						'alphabet' => esc_html__( 'Alphabet', 'wdt-elementor-addon' ),
						'icon'     => esc_html__( 'Icon', 'wdt-elementor-addon' )
					),
					'default' => ''
				)
			);

			$elementor_object->add_control(
				'expand_collapse_position',
				array (
					'label' => esc_html__( 'Expand / Collapse Icon Position', 'wdt-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => array (
						'start'  => esc_html__( 'Start', 'wdt-elementor-addon' ),
						'end' => esc_html__( 'End', 'wdt-elementor-addon' )
					),
					'default' => 'end'
				)
			);

			$elementor_object->add_control(
				'expand_icon',
				array (
					'label' => esc_html__( 'Expand Icon', 'wdt-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'label_block' => false,
					'skin' => 'inline',
					'default' => array( 'value' => 'fas fa-plus', 'library' => 'fa-solid' )
				)
			);

			$elementor_object->add_control(
				'collapse_icon',
				array (
					'label' => esc_html__( 'Collapse Icon', 'wdt-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'label_block' => false,
					'skin' => 'inline',
					'default' => array( 'value' => 'fas fa-minus', 'library' => 'fa-solid' )
				)
			);

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
							'{{WRAPPER}} .wdt-accordion-toggle-holder' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}; justify-items: {{VALUE}};'
						),
						'condition' => array ()
					),
					'padding' => array (
						'field_type' => 'padding',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'background' => array (
						'field_type' => 'background',
						'selector' => '{{WRAPPER}} .wdt-accordion-toggle-holder',
						'condition' => array ()
					),
					'border' => array (
						'field_type' => 'border',
						'selector' => '{{WRAPPER}} .wdt-accordion-toggle-holder',
						'condition' => array ()
					),
					'border_radius' => array (
						'field_type' => 'border_radius',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'box_shadow' => array (
						'field_type' => 'box_shadow',
						'selector' => '{{WRAPPER}} .wdt-accordion-toggle-holder',
						'condition' => array ()
					)
				)
			));

		// Style
			$this->cc_style->get_style_controls($elementor_object, array (
				'slug' => 'style',
				'title' => esc_html__( 'Style', 'wdt-elementor-addon' ),
				'styles' => array (
					'heading_title_section' => array (
						'field_type' => 'heading',
						'unique_key' => 'title_section',
						'title' => esc_html__( 'Title Section', 'wdt-elementor-addon' ),
						'condition' => array ()
					),
					'typography_title_section' => array (
						'field_type' => 'typography',
						'unique_key' => 'title_section',
						'selector' => '{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder .wdt-accordion-toggle-title',
						'condition' => array ()
					),
					'font_size_title_section_icon' => array (
						'field_type' => 'font_size',
						'unique_key' => 'title_section_icon',
						'label' => esc_html__( 'Icon Font Size', 'wdt-elementor-addon' ),
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder .wdt-accordion-toggle-icon' => 'font-size: {{SIZE}}{{UNIT}};'
						),
						'condition' => array ()
					),
					'padding_title_section' => array (
						'field_type' => 'padding',
						'unique_key' => 'title_section',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'margin_title_section' => array (
						'field_type' => 'margin',
						'unique_key' => 'title_section',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'tabs_title_section' => array (
						'field_type' => 'tabs',
						'unique_key' => 'title_section',
						'tab_items' => array (
							'normal' => array (
								'title' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
								'styles' => array (
									'border' => array (
										'field_type' => 'border',
										'selector' => '{{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-title-holder',
										'condition' => array ()
									),
									'border_radius' => array (
										'field_type' => 'border_radius',
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-title-holder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										),
										'condition' => array ()
									),
									'color_title' => array (
										'field_type' => 'color',
										'unique_key' => 'title',
										'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder .wdt-accordion-toggle-title, {{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder .wdt-accordion-toggle-icon' => 'color: {{VALUE}};'
										),
										'condition' => array ()
									),
									'color_icon' => array (
										'field_type' => 'color',
										'unique_key' => 'icon',
										'label' => esc_html__( 'Icon Color', 'wdt-elementor-addon' ),
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder .wdt-accordion-toggle-icon' => 'color: {{VALUE}};'
										),
										'condition' => array ()
									),
									'color_background' => array (
										'field_type' => 'color',
										'unique_key' => 'background',
										'label' => esc_html__( 'Background Color', 'wdt-elementor-addon' ),
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-holder:not([class*="-template-simple"]) .wdt-accordion-toggle-title-holder, {{WRAPPER}} .wdt-accordion-toggle-holder[class*="-template-simple"] .wdt-accordion-toggle-title-holder .wdt-accordion-toggle-icon' => 'background-color: {{VALUE}};'
										),
										'condition' => array ()
									)
								)
							),
							'active' => array (
								'title' => esc_html__( 'Active', 'wdt-elementor-addon' ),
								'styles' => array (
									'border' => array (
										'field_type' => 'border',
										'selector' => '{{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-title-holder.ui-state-active, {{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-title-holder.ui-state-hover',
										'condition' => array ()
									),
									'border_radius' => array (
										'field_type' => 'border_radius',
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-title-holder.ui-state-active, {{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-title-holder.ui-state-hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										),
										'condition' => array ()
									),
									'color_title' => array (
										'field_type' => 'color',
										'unique_key' => 'title',
										'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder.ui-state-active .wdt-accordion-toggle-title, {{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder.ui-state-hover .wdt-accordion-toggle-title, {{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder.ui-state-active .wdt-accordion-toggle-icon, {{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder.ui-state-hover .wdt-accordion-toggle-icon' => 'color: {{VALUE}};',
											'{{WRAPPER}} .wdt-accordion-toggle-holder[class*="-template-bordered"] .wdt-accordion-toggle-description' => 'border-left-color: {{VALUE}};',
										),
										'condition' => array ()
									),
									'color_icon' => array (
										'field_type' => 'color',
										'unique_key' => 'icon',
										'label' => esc_html__( 'Icon Color', 'wdt-elementor-addon' ),
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder.ui-state-active .wdt-accordion-toggle-icon, {{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-title-holder.ui-state-hover .wdt-accordion-toggle-icon' => 'color: {{VALUE}};'
										),
										'condition' => array ()
									),
									'color_background' => array (
										'field_type' => 'color',
										'unique_key' => 'background',
										'label' => esc_html__( 'Background Color', 'wdt-elementor-addon' ),
										'selector' => array (
											'{{WRAPPER}} .wdt-accordion-toggle-holder:not([class*="-template-simple"]) .wdt-accordion-toggle-title-holder.ui-state-active, {{WRAPPER}} .wdt-accordion-toggle-holder:not([class*="-template-simple"]) .wdt-accordion-toggle-title-holder.ui-state-hover, {{WRAPPER}} .wdt-accordion-toggle-holder[class*="-template-simple"] .wdt-accordion-toggle-title-holder.ui-state-active .wdt-accordion-toggle-icon, {{WRAPPER}} .wdt-accordion-toggle-holder[class*="-template-simple"] .wdt-accordion-toggle-title-holder.ui-state-hover .wdt-accordion-toggle-icon' => 'background-color: {{VALUE}};'
										),
										'condition' => array ()
									)
								)
							)
						)
					),

					'heading_content_section' => array (
						'field_type' => 'heading',
						'unique_key' => 'content_section',
						'title' => esc_html__( 'Content Section', 'wdt-elementor-addon' ),
						'separator' => 'before',
						'condition' => array ()
					),
					'typography_content_section' => array (
						'field_type' => 'typography',
						'unique_key' => 'content_section',
						'selector' => '{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-description',
						'condition' => array ()
					),
					'padding_content_section' => array (
						'field_type' => 'padding',
						'unique_key' => 'content_section',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'margin_content_section' => array (
						'field_type' => 'margin',
						'unique_key' => 'content_section',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'border' => array (
						'field_type' => 'border',
						'unique_key' => 'content_section',
						'selector' => '{{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-description',
						'condition' => array ()
					),
					'border_radius' => array (
						'field_type' => 'border_radius',
						'unique_key' => 'content_section',
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-wrapper .wdt-accordion-toggle-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition' => array ()
					),
					'color_content_background' => array (
						'field_type' => 'color',
						'unique_key' => 'content_background',
						'label' => esc_html__( 'Background Color', 'wdt-elementor-addon' ),
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-description.ui-accordion-content-active' => 'background-color: {{VALUE}};'
						),
						'condition' => array ()
					),
					'color_content_text' => array (
						'field_type' => 'color',
						'unique_key' => 'content_text',
						'label' => esc_html__( 'Content Color', 'wdt-elementor-addon' ),
						'selector' => array (
							'{{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-description.ui-accordion-content-active, {{WRAPPER}} .wdt-accordion-toggle-holder .wdt-accordion-toggle-description.ui-accordion-content-active p' => 'color: {{VALUE}};'
						),
						'condition' => array ()
					)
				)
			));


	}

	public function wdt_widgets_custom_accordion_toggle_control_register($elementor_object) {

		$elementor_object->add_control(
			'title_prefix_heading',
			array (
				'label' => esc_html__( 'Title Prefix', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			)
		);

		$elementor_object->add_control(
			'title_prefix_icon',
			array (
				'label' => esc_html__( 'Icon', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline'
			)
		);

		$elementor_object->add_control(
			'title_prefix_icon_description',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Icon option will be used only when "Title Prefix" is "Icon".', 'wdt-elementor-addon' ),
				'content_classes' => 'elementor-descriptor'
			)
		);

	}

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		$output = '';

		$classes = array ();
		array_push($classes, 'wdt-module-'.$settings['module']);
		array_push($classes, 'wdt-template-'.$settings['template']);
		array_push($classes, 'wdt-expand-collapse-position-'.$settings['expand_collapse_position']);

		$module_id = $widget_object->get_id();

		if(is_array($settings['item_contents']) && !empty($settings['item_contents'])) {
			// Add data attributes to prevent jQuery UI from adding incorrect ARIA roles
			$output .= '<div class="wdt-accordion-toggle-holder '.esc_attr(implode(' ', $classes)).'" 
							id="wdt-accordion-and-toggle-'.esc_attr($module_id).'"
							data-role="accordion">';
			foreach( $settings['item_contents'] as $key => $item ) {
		
				if($item['item_type'] == 'default') {
					// Create unique IDs for each accordion item
					$heading_id = 'wdt-accordion-heading-'.esc_attr($module_id).'-'.$key;
					$panel_id = 'wdt-accordion-panel-'.esc_attr($module_id).'-'.$key;
		
					$output .= '<div class="wdt-accordion-toggle-wrapper">';
						// Make this a button for better accessibility
						$output .= '<button class="wdt-accordion-toggle-title-holder" 
										id="'.$heading_id.'" 
										aria-expanded="false" 
										aria-controls="'.$panel_id.'">';
							$output .= '<div class="wdt-accordion-toggle-title">';
								if($settings['title_prefix'] == 'icon') {
									if($item['title_prefix_icon']['value']) {
										$output .= '<div class="wdt-accordion-toggle-title-prefix icon">';
											ob_start();
											\Elementor\Icons_Manager::render_icon( $item['title_prefix_icon'], [ 'aria-hidden' => 'true' ] );
											$output .= ob_get_clean();
										$output .= '</div>';
									}
								} elseif($settings['title_prefix'] == 'alphabet') {
									$alphabets = range('A', 'Z');
									$output .= '<div class="wdt-accordion-toggle-title-prefix alphabet">';
										$output .= $alphabets[$key];
									$output .= '</div>';
								} else if($settings['title_prefix'] == 'question') {
									$output .= '<div class="wdt-accordion-toggle-title-prefix question">';
										$output .= 'Q'.($key+1);
									$output .= '</div>';
								} else if($settings['title_prefix'] == 'number') {
									$output .= '<div class="wdt-accordion-toggle-title-prefix number">';
										$output .= $key+1;
									$output .= '</div>';
								}
								$output .= esc_html($item['item_title']);
							$output .= '</div>';
							$output .= '<div class="wdt-accordion-toggle-icon">';
								$output .= '<div class="wdt-accordion-toggle-icon-expand">';
									ob_start();
									\Elementor\Icons_Manager::render_icon( $settings['expand_icon'], [ 'aria-hidden' => 'true' ] );
									$output .= ob_get_clean();
								$output .= '</div>';
								$output .= '<div class="wdt-accordion-toggle-icon-collapse">';
									ob_start();
									\Elementor\Icons_Manager::render_icon( $settings['collapse_icon'], [ 'aria-hidden' => 'true' ] );
									$output .= ob_get_clean();
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</button>';
		
						$output .= '<div class="wdt-accordion-toggle-description" 
										id="'.$panel_id.'" 
										aria-labelledby="'.$heading_id.'"
										data-role="region">'.do_shortcode($item['item_description']).'</div>';
					$output .= '</div>';
				}
		
				if( $item['item_type'] == 'template' && isset($item['item_template']) ) {
					// Create unique IDs for each accordion item
					$heading_id = 'wdt-accordion-heading-'.esc_attr($module_id).'-'.$key;
					$panel_id = 'wdt-accordion-panel-'.esc_attr($module_id).'-'.$key;
		
					$output .= '<div class="wdt-accordion-toggle-wrapper">';
						// Make this a button for better accessibility
						$output .= '<button class="wdt-accordion-toggle-title-holder" 
										id="'.$heading_id.'" 
										aria-expanded="false" 
										aria-controls="'.$panel_id.'">';
							$output .= '<div class="wdt-accordion-toggle-title">';
								if($settings['title_prefix'] == 'icon') {
									if($item['title_prefix_icon']['value']) {
										$output .= '<div class="wdt-accordion-toggle-title-icon">';
											ob_start();
											\Elementor\Icons_Manager::render_icon( $item['title_prefix_icon'], [ 'aria-hidden' => 'true' ] );
											$output .= ob_get_clean();
										$output .= '</div>';
									}
								} elseif($settings['title_prefix'] == 'alphabet') {
									$alphabets = range('A', 'Z');
									$output .= '<div class="wdt-accordion-toggle-title-alphabet">';
										$output .= $alphabets[$key];
									$output .= '</div>';
								} else if($settings['title_prefix'] == 'number') {
									$output .= '<div class="wdt-accordion-toggle-title-number">';
										$output .= $key+1;
									$output .= '</div>';
								}
								$output .= esc_html($item['item_title']);
							$output .= '</div>';
							$output .= '<div class="wdt-accordion-toggle-icon">';
								$output .= '<div class="wdt-accordion-toggle-icon-expand">';
									ob_start();
									\Elementor\Icons_Manager::render_icon( $settings['expand_icon'], [ 'aria-hidden' => 'true' ] );
									$output .= ob_get_clean();
								$output .= '</div>';
								$output .= '<div class="wdt-accordion-toggle-icon-collapse">';
									ob_start();
									\Elementor\Icons_Manager::render_icon( $settings['collapse_icon'], [ 'aria-hidden' => 'true' ] );
									$output .= ob_get_clean();
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</button>';
		
						$output .= '<div class="wdt-accordion-toggle-description" 
										id="'.$panel_id.'" 
										aria-labelledby="'.$heading_id.'"
										data-role="region">';
							$frontend = Elementor\Frontend::instance();
							$output .= $frontend->get_builder_content( $item['item_template'], true );
						$output .= '</div>';
					$output .= '</div>';
				}
			}
			$output .= '</div>';
		}

		return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_accordion_and_toggle' ) ) {
    function wedesigntech_widget_base_accordion_and_toggle() {
        return WeDesignTech_Widget_Base_Accordion_And_Toggle::instance();
    }
}