<?php

namespace DTElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WDTPortfolioSpFeatures extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-default-widgets' ];
	}

	public function get_name() {
		return 'wdt-widget-sp-features';
	}

	public function get_title() {
		return esc_html__( 'Features','wdt-portfolio');
	}

	public function get_style_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	public function get_script_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	protected function register_controls() {

		$listing_singular_label = apply_filters( 'listing_label', 'singular' );

		$this->start_controls_section( 'features_default_section', array(
			'label' => esc_html__( 'General','wdt-portfolio'),
		) );

			$this->add_control( 'listing_id', array(
				'label'       => sprintf( esc_html__('%1$s Id','wdt-portfolio'), $listing_singular_label ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( esc_html__('Provide %1$s id to display your item. No need to provide ID if it is used in %1$s single page.','wdt-portfolio'), strtolower($listing_singular_label) ),
				'default'     => ''
			) );

			$this->add_control( 'include', array(
				'label'   => esc_html__( 'Include','wdt-portfolio'),
				'type'    => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you like, you can include only certain items. Leave empty if you like to display all.','wdt-portfolio'),
				'default' => ''
			) );

			$this->add_control( 'columns', array(
				'label'       => esc_html__( 'Columns','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					-1  => esc_html__('No Column','wdt-portfolio'),
					1  => esc_html__('I Column','wdt-portfolio'),
					2  => esc_html__('II Columns','wdt-portfolio'),
					3  => esc_html__('III Columns','wdt-portfolio'),
					4  => esc_html__('IV Columns','wdt-portfolio'),
					5  => esc_html__('V Columns','wdt-portfolio')
				),
				'description' => sprintf( esc_html__( 'Number of columns you like to display your %1$s.','wdt-portfolio'), strtolower($listing_singular_label) ),
				'default'      => 4,
			) );

			$this->add_control( 'class', array(
				'label'   => esc_html__( 'Class','wdt-portfolio'),
				'type'    => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you wish you can add additional class name here.','wdt-portfolio'),
				'default' => ''
			) );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		$attributes = wdtportfolio_elementor_instance()->wdt_parse_shortcode_attrs( $settings );
		echo do_shortcode('[wdt_sp_features '.$attributes.' /]');
	}

}