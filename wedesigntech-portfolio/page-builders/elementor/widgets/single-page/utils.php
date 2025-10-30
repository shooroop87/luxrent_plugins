<?php

namespace DTElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WDTPortfolioSpUtils extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-default-widgets' ];
	}

	public function get_name() {
		return 'wdt-widget-sp-utils';
	}

	public function get_title() {
		return esc_html__( 'Utils','wdt-portfolio');
	}

	public function get_style_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	public function get_script_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	protected function register_controls() {

		$listing_singular_label      = apply_filters( 'listing_label', 'singular' );
		$amenity_singular_label      = apply_filters( 'amenity_label', 'singular' );

		$this->start_controls_section( 'utils_default_section', array(
			'label' => esc_html__( 'General','wdt-portfolio'),
		) );

			$this->add_control( 'listing_id', array(
				'label'       => sprintf( esc_html__('%1$s Id','wdt-portfolio'), $listing_singular_label ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( esc_html__('Provide %1$s id to display your item. No need to provide ID if it is used in %1$s single page.','wdt-portfolio'), strtolower($listing_singular_label) ),
				'default'     => ''
			) );

			$this->add_control( 'show_title', array(
				'label'       => esc_html__( 'Show Title','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to show title.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'show_favourite', array(
				'label'       => esc_html__( 'Show Favourite','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to show favourite option.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'show_excerpt', array(
				'label'       => esc_html__( 'Show Excerpt','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to show excerpt.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'show_categories', array(
				'label'       => esc_html__( 'Show Categories','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to show categories.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'show_amenity', array(
				'label'       => sprintf( esc_html__('Show %1$s','wdt-portfolio'), $amenity_singular_label ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => sprintf( esc_html__('Choose "True" if you like to show %1$s','wdt-portfolio'), strtolower($amenity_singular_label) ),
				'default'      => 'false'
			) );

			$this->add_control( 'show_socialshare', array(
				'label'       => esc_html__( 'Show Social links','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' =>  esc_html__('Choose "True" if you like to show social share option.','wdt-portfolio'),
				'default'      => 'false'
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
		echo do_shortcode('[wdt_sp_utils '.$attributes.' /]');
	}

}