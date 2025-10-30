<?php

namespace DTElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WDTPortfolioSpTaxonomy extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-default-widgets' ];
	}

	public function get_name() {
		return 'wdt-widget-sp-taxonomy';
	}

	public function get_title() {
		return esc_html__( 'Taxonomy','wdt-portfolio');
	}

	public function get_style_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	public function get_script_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	protected function register_controls() {

		$listing_singular_label      = apply_filters( 'listing_label', 'singular' );

		$taxonomies = apply_filters( 'wdt_taxonomies', array () );

		$this->start_controls_section( 'taxonomy_default_section', array(
			'label' => esc_html__( 'General','wdt-portfolio'),
		) );

			$this->add_control( 'listing_id', array(
				'label'       => sprintf( esc_html__('%1$s Id','wdt-portfolio'), $listing_singular_label ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( esc_html__('Provide %1$s id to display your item. No need to provide ID if it is used in %1$s single page.','wdt-portfolio'), strtolower($listing_singular_label) ),
				'default'     => ''
			) );

			$this->add_control( 'taxonomy', array(
				'label'       => esc_html__( 'Taxonomy','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => $taxonomies,
				'description' => esc_html__( 'Choose type of taxonomy you would like to display.','wdt-portfolio'),
				'default'      => 'wdt_listings_category',
			) );

			$this->add_control( 'show_label', array(
				'label'       => esc_html__( 'Show Label','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true' => esc_html__('True','wdt-portfolio')
				),
				'description' => esc_html__( 'Choose "True" if you like to show label.','wdt-portfolio'),
				'default'      => 'false',
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
		$settings   = $this->get_settings();
		$attributes = wdtportfolio_elementor_instance()->wdt_parse_shortcode_attrs( $settings );

		echo do_shortcode('[wdt_sp_taxonomy '.$attributes.' /]');
	}

}