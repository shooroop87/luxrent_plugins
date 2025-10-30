<?php

namespace DTElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WDTPortfolioSpFeaturedImage extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-default-widgets' ];
	}

	public function get_name() {
		return 'wdt-widget-sp-featured-image';
	}

	public function get_title() {
		return esc_html__( 'Featured Image','wdt-portfolio');
	}

	public function get_style_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	public function get_script_depends() {
		return array ( 'wdt-modules-singlepage' );
	}

	protected function register_controls() {

		$listing_singular_label = apply_filters( 'listing_label', 'singular' );

		$this->start_controls_section( 'featured_image_default_section', array(
			'label' => esc_html__( 'General','wdt-portfolio'),
		) );

			$this->add_control( 'listing_id', array(
				'label'       => sprintf( esc_html__('%1$s Id','wdt-portfolio'), $listing_singular_label ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( esc_html__('Provide %1$s id to display your item. No need to provide ID if it is used in %1$s single page.','wdt-portfolio'), strtolower($listing_singular_label) ),
				'default'     => ''
			) );

			$this->add_control( 'image_size', array(
				'label'       => esc_html__( 'Thumbnail Sizes','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'thumbnail'    => esc_html__('Thumbnail','wdt-portfolio'),
					'medium'       => esc_html__('Medium','wdt-portfolio'),
					'medium_large' => esc_html__('Medium Large','wdt-portfolio'),
					'large'        => esc_html__('Large','wdt-portfolio'),
					'full'         => esc_html__('Full','wdt-portfolio'),
				),
				'description' => esc_html__( 'Choose any of the above image sizes.','wdt-portfolio'),
				'default'      => 'full',
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
		echo do_shortcode('[wdt_sp_featured_image '.$attributes.' /]');
	}

}