<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Services {

    private static $_instance = null;

    private $cc_layout;
	private $cc_style;

    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    function __construct() {

		// Initialize depandant class
        $this->cc_layout = new WeDesignTech_Common_Controls_Layout('both');
        $this->cc_style = new WeDesignTech_Common_Controls_Style();

	}

    public function name() {
		return 'wdt-services';
	}

    public function title() {
		return esc_html__( 'Services', 'wdt-elementor-addon' );
	}

    public function icon() {
		return 'eicon-apps';
	}

    public function init_styles() {
		return array_merge(
            $this->cc_layout->init_styles(),
            array(
                $this->name() => WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/services/assets/css/style.css'
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
            array($this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/services/assets/js/script.js'	)
        );
    }

    // Helper method to get service categories
    private function get_service_categories() {
        $categories = array();
        
        // Get custom taxonomy terms for services (adjust taxonomy name as needed)
        $terms = get_terms(array(
            'taxonomy' => 'wdt_service_category', // Adjust this to your actual service category taxonomy
            'hide_empty' => false,
        ));
        
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $categories[$term->term_id] = $term->name;
            }
        }
        
        return $categories;
    }

    // Helper method to get service post IDs
    private function get_service_post_ids() {
        $services = array();
        
        $posts = get_posts(array(
            'post_type' => 'wdt_services',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));
        
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $services[$post->ID] = $post->post_title;
            }
        }
        
        return $services;
    }

    public function create_elementor_controls($elementor_object) {

        $elementor_object->start_controls_section( 'wdt_section_settings', array(
			'label' => esc_html__( 'Services Settings', 'wdt-elementor-addon'),
		));

            $elementor_object->add_control('services_type', array(
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Services Type', 'wdt-elementor-addon'),
                'default' => 'type-1',
                'options' => array(
                    'type-1'    => esc_html__('Type-1', 'wdt-elementor-addon'),
                    'type-2'    => esc_html__('Type-2', 'wdt-elementor-addon'),
                    'type-3'    => esc_html__('Type-3', 'wdt-elementor-addon'),
					'type-4'    => esc_html__('Type-4', 'wdt-elementor-addon')
                )
            ));

            $elementor_object->add_control('button_text', array(
                'label'       => esc_html__('Button Text', 'wdt-elementor-addon'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Read More', 'wdt-elementor-addon'),
                'description' => esc_html__('This text will be used as the button label if the individual service does not have a custom button text set.', 'wdt-elementor-addon'),
            ));
			$elementor_object->add_control('button_curve', array(
				'label'       => esc_html__('Button Curve', 'wdt-elementor-addon'),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'no',
			));

        $elementor_object->end_controls_section();

        // Query Settings Section
        $elementor_object->start_controls_section( 'wdt_section_query', array(
			'label' => esc_html__( 'Service Listing Settings', 'wdt-elementor-addon'),
		));

            // Query posts by
            $elementor_object->add_control( 'query_posts_by', array(
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Query Services by', 'wdt-elementor-addon'),
                'default' => 'category',
                'options' => array(
                    'category'  => esc_html__('From Category', 'wdt-elementor-addon'),
                    'ids'       => esc_html__('By Specific IDs', 'wdt-elementor-addon'),
                    'all'       => esc_html__('All Services', 'wdt-elementor-addon'),
                )
            ));

            // Service categories
            $elementor_object->add_control( '_service_categories', array(
                'label'       => esc_html__( 'Categories', 'wdt-elementor-addon' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => $this->get_service_categories(),
                'condition'   => array( 'query_posts_by' => 'category' )
            ));

            // Service IDs
            $elementor_object->add_control( '_service_ids', array(
                'label'       => esc_html__( 'Select Specific Services', 'wdt-elementor-addon' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => $this->get_service_post_ids(),
                'condition'   => array( 'query_posts_by' => 'ids' )
            ));

            // Post count
            $elementor_object->add_control( '_service_count', array(
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'label'       => esc_html__('Service Count', 'wdt-elementor-addon'),
                'default'     => '6',
                'min'         => 1,
                'max'         => 50,
                'placeholder' => esc_html__( 'Enter service count', 'wdt-elementor-addon' ),
                'condition'   => array( 'query_posts_by!' => 'ids' )
            ));
			// Post pagination
			$elementor_object->add_control( '_service_pagination', array(
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label'       => esc_html__('Pagination', 'wdt-elementor-addon'),
				'default'     => 'yes'
			));

        $elementor_object->end_controls_section();

		$this->cc_layout->get_controls($elementor_object);

        // Items
        $this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'item',
			'title' => esc_html__( 'Item', 'wdt-elementor-addon' ),
			'styles' => array (
				'alignment' => array (
					'field_type' => 'alignment',
                    'control_type' => 'responsive',
                    'default' => 'center',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};'
					),
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
									'selector' => '{{WRAPPER}} .wdt-service-item',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-service-item',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-service-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-service-item',
									'condition' => array ()
								)
							)
						),
						'hover' => array (
							'title' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
							'styles' => array (
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-service-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover',
									'condition' => array ()
								)
							)
						)
					)
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
						'{{WRAPPER}} .wdt-service-item .wdt-service-image' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};'
					),
					'condition' => array ()
				),
				'width' => array (
					'field_type' => 'width',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-image > a' => 'width: {{SIZE}}{{UNIT}};'
                    ),
					'condition' => array ()
				),
				'height' => array (
					'field_type' => 'height',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-image > a' => 'height: {{SIZE}}{{UNIT}};'
                    ),
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item .wdt-service-image > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'border' => array (
					'field_type' => 'border',
					'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-image > a',
					'condition' => array ()
				),
				'border_radius' => array (
					'field_type' => 'border_radius',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item .wdt-service-image > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'box_shadow' => array (
					'field_type' => 'box_shadow',
					'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-image > a',
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
                        '{{WRAPPER}} .wdt-service-item .wdt-service-type-icon' => 'font-size: {{SIZE}}{{UNIT}};'
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
						'{{WRAPPER}} .wdt-service-item .wdt-service-type-icon' => 'width: {{SIZE}}{{UNIT}};'
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
						'{{WRAPPER}} .wdt-service-item .wdt-service-type-icon' => 'height: {{SIZE}}{{UNIT}};'
					)
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-type-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item .wdt-service-type-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
										'{{WRAPPER}} .wdt-service-item .wdt-service-type-icon' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-type-icon',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-type-icon',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}}  .wdt-service-item .wdt-service-type-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-type-icon',
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
										'{{WRAPPER}} .wdt-service-item:hover .wdt-service-type-icon' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover .wdt-service-type-icon',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover .wdt-service-type-icon',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-service-item:hover .wdt-service-type-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover .wdt-service-type-icon',
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
					'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-title h5',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
										'{{WRAPPER}} .wdt-service-item .wdt-service-title h5, 
										 {{WRAPPER}} .wdt-service-item .wdt-service-title h5 > a' => 'color: {{VALUE}};'
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
										'{{WRAPPER}} .wdt-service-item:hover .wdt-service-title h5 > a:hover' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
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
					'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-description',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item .wdt-service-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array ()
				),
				'color' => array (
					'field_type' => 'color',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item .wdt-service-description' => 'color: {{VALUE}};'
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
					'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-button > a',
					'condition' => array ()
				),
				'margin' => array (
					'field_type' => 'margin',
					'selector' => array (
                        '{{WRAPPER}} .wdt-service-item .wdt-service-button > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
					'condition' => array ()
				),
				'padding' => array (
					'field_type' => 'padding',
					'selector' => array (
						'{{WRAPPER}} .wdt-service-item .wdt-service-button > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
										'{{WRAPPER}} .wdt-service-item .wdt-service-button > a' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-button > a',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-button > a',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-service-item .wdt-service-button > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-service-item .wdt-service-button > a',
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
										'{{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:focus, 
										 {{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:hover' => 'color: {{VALUE}};'
									),
									'condition' => array ()
								),
								'background' => array (
									'field_type' => 'background',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:focus, 
									 			   {{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:hover',
									'condition' => array ()
								),
								'border' => array (
									'field_type' => 'border',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:focus, 
												   {{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:hover',
									'condition' => array ()
								),
								'border_radius' => array (
									'field_type' => 'border_radius',
									'selector' => array (
										'{{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:focus, 
										 {{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition' => array ()
								),
								'box_shadow' => array (
									'field_type' => 'box_shadow',
									'selector' => '{{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:focus, 
												   {{WRAPPER}} .wdt-service-item:hover .wdt-service-button > a:hover',
									'condition' => array ()
								)
							)
						)
					)
				)
			)
		));

        // Pagination Styles
        $this->cc_style->get_style_controls($elementor_object, array (
            'slug' => 'pagination',
            'title' => esc_html__( 'Pagination', 'wdt-elementor-addon' ),
            'condition' => array( '_service_pagination' => 'yes' ),
            'styles' => array (
                'alignment' => array (
                    'field_type' => 'alignment',
                    'selector' => array (
                        '{{WRAPPER}} .wdt-services-pagination' => 'text-align: {{VALUE}};'
                    ),
                    'condition' => array ()
                ),
                'margin' => array (
                    'field_type' => 'margin',
                    'selector' => array (
                        '{{WRAPPER}} .wdt-services-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                    'condition' => array ()
                ),
                'padding' => array (
                    'field_type' => 'padding',
                    'selector' => array (
                        '{{WRAPPER}} .wdt-services-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                    'condition' => array ()
                ),
                'typography' => array (
                    'field_type' => 'typography',
                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers',
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
                                        '{{WRAPPER}} .wdt-services-pagination .page-numbers' => 'color: {{VALUE}};'
                                    ),
                                    'condition' => array ()
                                ),
                                'background' => array (
                                    'field_type' => 'background',
                                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers',
                                    'condition' => array ()
                                ),
                                'border' => array (
                                    'field_type' => 'border',
                                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers',
                                    'condition' => array ()
                                ),
                                'border_radius' => array (
                                    'field_type' => 'border_radius',
                                    'selector' => array (
                                        '{{WRAPPER}} .wdt-services-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                        '{{WRAPPER}} .wdt-services-pagination .page-numbers:hover' => 'color: {{VALUE}};'
                                    ),
                                    'condition' => array ()
                                ),
                                'background' => array (
                                    'field_type' => 'background',
                                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers:hover',
                                    'condition' => array ()
                                ),
                                'border' => array (
                                    'field_type' => 'border',
                                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers:hover',
                                    'condition' => array ()
                                ),
                                'border_radius' => array (
                                    'field_type' => 'border_radius',
                                    'selector' => array (
                                        '{{WRAPPER}} .wdt-services-pagination .page-numbers:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    ),
                                    'condition' => array ()
                                ),
                            )
                        ),
                        'active' => array (
                            'title' => esc_html__( 'Active', 'wdt-elementor-addon' ),
                            'styles' => array (
                                'color' => array (
                                    'field_type' => 'color',
                                    'selector' => array (
                                        '{{WRAPPER}} .wdt-services-pagination .page-numbers.current' => 'color: {{VALUE}};'
                                    ),
                                    'condition' => array ()
                                ),
                                'background' => array (
                                    'field_type' => 'background',
                                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers.current',
                                    'condition' => array ()
                                ),
                                'border' => array (
                                    'field_type' => 'border',
                                    'selector' => '{{WRAPPER}} .wdt-services-pagination .page-numbers.current',
                                    'condition' => array ()
                                ),
                                'border_radius' => array (
                                    'field_type' => 'border_radius',
                                    'selector' => array (
                                        '{{WRAPPER}} .wdt-services-pagination .page-numbers.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    ),
                                    'condition' => array ()
                                ),
                            )
                        )
                    )
                )
            )
        ));
		//Curve Button
		$this->cc_style->get_style_controls($elementor_object, array (
			'slug' => 'button_curve',
			'title' => esc_html__( 'Button Curve', 'wdt-elementor-addon' ),
			'styles' => array (
				'width' => array(
					'field_type' => 'width',
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'width: {{SIZE}}{{UNIT}};'
					),
					'condition' => array()
				),
				'height' => array(
					'field_type' => 'height',
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'height: {{SIZE}}{{UNIT}};'
					),
					'condition' => array()
				),
				'border_radius' => array(
					'field_type' => 'border_radius',
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					),
					'condition' => array()
				),
				'background' => array(
					'field_type' => 'background',
					'selector' => '{{WRAPPER}} .wdt-service-curve-button',
					'condition' => array()
				),
				'color' => array(
					'field_type' => 'color',
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button a' => 'color: {{VALUE}};'
					),
					'condition' => array()
				),
				'box_shadow' => array(
					'field_type' => 'box_shadow',
					'selector'   => '{{WRAPPER}} .wdt-service-curve-button::before, {{WRAPPER}} .wdt-service-curve-button::after',
					'condition'  => array()
				),
								'heading_curve_button_position' => array(
					'field_type' => 'heading',
					'unique_key' => 'curve_button_position',
					'title' => esc_html__( 'Curve Button Positioning', 'wdt-elementor-addon' ),
					'separator' => 'before',
				),

				'curve_button_top_position' => array(
					'field_type' => 'indent', // or 'slider' if your helper prefers
					'unique_key' => 'curve_button_top',
					'label' => esc_html__( 'Top', 'wdt-elementor-addon' ),
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'position: absolute; top: {{SIZE}}{{UNIT}};'
					),
				),

				'curve_button_bottom_position' => array(
					'field_type' => 'indent',
					'unique_key' => 'curve_button_bottom',
					'label' => esc_html__( 'Bottom', 'wdt-elementor-addon' ),
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'position: absolute; bottom: {{SIZE}}{{UNIT}};'
					),
				),

				'curve_button_left_position' => array(
					'field_type' => 'indent',
					'unique_key' => 'curve_button_left',
					'label' => esc_html__( 'Left', 'wdt-elementor-addon' ),
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'position: absolute; left: {{SIZE}}{{UNIT}};'
					),
				),

				'curve_button_right_position' => array(
					'field_type' => 'indent',
					'unique_key' => 'curve_button_right',
					'label' => esc_html__( 'Right', 'wdt-elementor-addon' ),
					'selector' => array(
						'{{WRAPPER}} .wdt-service-curve-button' => 'position: absolute; right: {{SIZE}}{{UNIT}};'
					),
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
        $classes = array ();

        $settings['module_id']    = $widget_object->get_id();
        $settings['module_class'] = 'services';
        $settings['classes'] = $classes;
        $this->cc_layout->set_settings($settings);
        $module_layout_class = $this->cc_layout->get_item_class();

        $query_posts_by = isset($settings['query_posts_by']) ? $settings['query_posts_by'] : 'all';
        $_service_categories = isset($settings['_service_categories']) ? $settings['_service_categories'] : array();
        $_service_ids = isset($settings['_service_ids']) ? $settings['_service_ids'] : array();
        $count = isset($settings['_service_count']) ? intval($settings['_service_count']) : 6;
        $pagination_enabled = isset($settings['_service_pagination']) && $settings['_service_pagination'] === 'yes';

        // Get current page for pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $form_args = array(
            'post_type'      => 'wdt_services',
            'post_status'    => 'publish',
            'posts_per_page' => $count,
            'ignore_sticky_posts' => true,
            'paged'          => $paged,
        );

        // Don't use pagination for specific IDs query
        if( $query_posts_by == 'ids' ) {
            $pagination_enabled = false;
        }

        if( !empty( $_service_categories ) && $query_posts_by == 'category' ) {
            $form_args['tax_query'] = array(
                array(
                    'taxonomy' => 'wdt_service_category', 
                    'field'    => 'term_id',
                    'terms'    => $_service_categories,
                )
            );
            $warning = esc_html__('No Services Found in Selected Categories','wdt-elementor-addon');
        } elseif( $query_posts_by == 'ids' && !empty( $_service_ids ) ) {
            $form_args['post__in'] = $_service_ids;
            $form_args['posts_per_page'] = -1; 
            $warning = esc_html__('No Services Found in Selected IDs','wdt-elementor-addon');
        } else {
            $warning = esc_html__('No Services Found','wdt-elementor-addon');
        }

        $form_query = new WP_Query($form_args);

        if ($form_query->have_posts()) {

            $output .= $this->cc_layout->get_wrapper_start();

            while ($form_query->have_posts()) {
                $form_query->the_post();
                $service_id = get_the_ID();

				$service_settings = get_post_meta(get_the_ID(), '_lumoria_service_settings', true);
				$icon  = !empty($service_settings['service_icon']) ? $service_settings['service_icon'] : '';
				$price = !empty($service_settings['service_price']) ? $service_settings['service_price'] : '';
				$offerprice = !empty($service_settings['service_offer_price']) ? $service_settings['service_offer_price'] : '';
                $service_type = isset($settings['services_type']) ? $settings['services_type'] : 'type-1';
				$active_class = '';
				if ( is_singular('wdt_services') && get_queried_object_id() == $service_id ) {
					$active_class = ' dt-services-active';
				}
                $output .= '<div class="'.esc_attr($module_layout_class).'">';
					$output .= '<div class="wdt-service-item wdt-' . esc_attr($service_type) . ' '. esc_attr($active_class) . '">';

                        if ($service_type === 'type-1') {
                               
                                $output .= '<div class="wdt-service-title"><h5>';
                                    $output .= '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
                                $output .= '</h5></div>';
								$output .= '<div class="wdt-service-image">';
									$output .= $this->render_service_image($service_id);
								$output .= '</div>';
                                $excerpt = get_the_excerpt($service_id);
                                if ( !empty($excerpt) ) {
                                    $output .= '<div class="wdt-service-description">' . esc_html($excerpt) . '</div>';
                                }                    
								$output .= '<div class="wdt-service-button">';
									$button_text = !empty($settings['button_text']) ? $settings['button_text'] : '';
									$output .= '<a href="' . esc_url(get_permalink()) . '" aria-label="' . esc_attr(get_the_title()) . '"><i class="icon-right-arrow"></i>' . esc_html($button_text) . '<span class="screen-reader-text">' . esc_html(get_the_title()) . '</span></a>';
                                $output .= '</div>';

                        } elseif ($service_type === 'type-2') {

                            $output .= '<div class="wdt-service-media-group">';
                                $output .= '<div class="wdt-service-image">';
                                	$output .= $this->render_service_image($service_id);
								$output .= '</div>';
                                $output .= $this->render_service_icon($icon);
                            $output .= '</div>';

                            $output .= '<div class="wdt-service-detail-group">';
                                $output .= '<div class="wdt-service-title"><h5>';
                                    $output .= '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
                                $output .= '</h5></div>';

                                $excerpt = get_the_excerpt($service_id);
                                if ( !empty($excerpt) ) {
                                    $output .= '<div class="wdt-service-description">' . esc_html($excerpt) . '</div>';
                                }                    
                               $output .= '<div class="wdt-service-button">';
									$button_text = !empty($settings['button_text']) ? $settings['button_text'] : '';
									$output .= '<a href="' . esc_url(get_permalink()) . '" aria-label="' . esc_attr(get_the_title()) . '"><i class="icon-right-arrow"></i>' . esc_html($button_text) . '<span class="screen-reader-text">' . esc_html(get_the_title()) . '</span></a>';
								$output .= '</div>';
                            $output .= '</div>';
							if($settings['button_curve'] == 'yes') {
								$output .= '<div class="wdt-service-curve-button">';
								$output .= '</div>';
							}

                        } elseif ($service_type === 'type-3') {
                            $output .= '<div class="wdt-service-media-group">';
								$output .= '<div class="wdt-service-image">';
                                	$output .= $this->render_service_image($service_id);
								$output .= '</div>';
                            $output .= '</div>';
                            
                            $output .= '<div class="wdt-service-detail-group">';
                                $output.='<div class="wdt-services-icon-title">';
                                    $output .= $this->render_service_icon($icon);
                                    $output .= '<div class="wdt-service-title"><h5>';
                                        $output .= '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
                                    $output .= '</h5></div>';
                            	$output.='</div>';
                           		 $output .= '<div class="wdt-service-button-group">';
									$excerpt = get_the_excerpt($service_id);
									if ( !empty($excerpt) ) {
										$output .= '<div class="wdt-service-description">' . esc_html($excerpt) . '</div>';
									}                    
									$output .= '<div class="wdt-service-button">';
									$button_text = !empty($settings['button_text']) ? $settings['button_text'] : '';
									$output .= '<a href="' . esc_url(get_permalink()) . '" aria-label="' . esc_attr(get_the_title()) . '"><i class="icon-right-arrow"></i>' . esc_html($button_text) . '<span class="screen-reader-text">' . esc_html(get_the_title()) . '</span></a>';
								$output .= '</div>';
								$output .='</div>';
                            $output .= '</div>';
							if($settings['button_curve'] == 'yes') {
								$output .= '<div class="wdt-service-curve-button">';
								$output .= '</div>';
							}
                            
                        }elseif ($service_type === 'type-4') {
							
							$output.='<div class="wdt-services-icon-title">';
								$output .= '<div class="wdt-service-title"><h5>';
									$output .= '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
								$output .= '</h5></div>';
							$output.='</div>';
                        }

                    $output .= '</div>';
                $output .= '</div>';
            }

            wp_reset_postdata();
            $output .= $this->cc_layout->get_column_edit_mode_css();
            $output .= $this->cc_layout->get_wrapper_end();

            // Add pagination if enabled and there are multiple pages
            if ($pagination_enabled && $form_query->max_num_pages > 1) {
                $output .= $this->render_pagination($form_query);
            }

        } else {
            // No services found
            $output .= '<div class="wdt-no-services-found">';
                $output .= '<p>' . esc_html($warning) . '</p>';
            $output .= '</div>';
        }

        return $output;
    }

    /**
     * Render pagination links using WordPress paginate_links()
     */
    private function render_pagination($query) {
        $output = '';
        
        if ($query->max_num_pages <= 1) {
            return $output;
        }

        $paged = max(1, get_query_var('paged'));
        
        $pagination_args = array(
            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'    => '?paged=%#%',
            'current'   => $paged,
            'total'     => $query->max_num_pages,
            'prev_text' => esc_html__('&laquo; Previous', 'wdt-elementor-addon'),
            'next_text' => esc_html__('Next &raquo;', 'wdt-elementor-addon'),
            'type'      => 'array',
            'end_size'  => 2,
            'mid_size'  => 2,
        );

        $pagination_links = paginate_links($pagination_args);

        if ($pagination_links) {
            $output .= '<div class="wdt-services-pagination">';
            foreach ($pagination_links as $link) {
                $output .= $link;
            }
            $output .= '</div>';
        }

        return $output;
    }

    public function render_service_icon($icon) {
        
        $output = '';
		

        if (!empty($icon)) {
            if (strpos($icon, '.svg') !== false) {
                $svg_path = ABSPATH . str_replace(site_url('/'), '', $icon);
                if (file_exists($svg_path)) {
                    $svg_content = file_get_contents($svg_path);
                    if ($svg_content !== false) {
                        $output .= '<div class="wdt-service-type-icon svg-icon">' . $svg_content . '</div>';
                    }
                }
            } else {
                $output .= '<div class="wdt-service-type-icon"><img src="' . esc_url($icon) . '" alt="Service Icon" title="Service Icon"/></div>';
            }
        }

        return $output;
    }

    public function render_service_image($service_id) {

		if (has_post_thumbnail($service_id)) {

			$image = get_the_post_thumbnail($service_id, 'full');
			$permalink = get_permalink($service_id);

			return '<a href="' . esc_url($permalink) . '">' . $image . '</a>';

		} else {

			$title = get_the_title($service_id);
			$permalink = get_permalink($service_id);
			$img_tag = '<img src="https://dummyimage.com/1200x800/cccccc/999999.jpg?text=' . esc_attr($title) . '" alt="' . esc_attr($title) . '" />';

			return '<a href="' . esc_url($permalink) . '">' . $img_tag . '</a>';
		}

    }
}


if( !function_exists( 'wedesigntech_widget_base_services' ) ) {
    function wedesigntech_widget_base_services() {
        return WeDesignTech_Widget_Base_Services::instance();
    }
}