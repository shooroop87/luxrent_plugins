<?php

namespace DTElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WDTPortfolioSpMediaImagesList extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-default-widgets' ];
	}

	public function get_name() {
		return 'wdt-widget-sp-media-images-list';
	}

	public function get_title() {
		return esc_html__( 'Media - Images List','wdt-portfolio');
	}

	public function get_style_depends() {
		return array ('wdt-media-images-frontend');
	}

	public function get_script_depends() {
		return array ('wdt-media-images-frontend');
	}

	protected function register_controls() {

		$listing_singular_label = apply_filters( 'listing_label', 'singular' );

		$this->start_controls_section( 'media_images_default_section', array(
			'label' => esc_html__( 'General','wdt-portfolio'),
		) );

			$this->add_control( 'listing_id', array(
				'label'       => sprintf( esc_html__('%1$s Id','wdt-portfolio'), $listing_singular_label ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( esc_html__('Provide %1$s id to display your item. No need to provide ID if it is used in %1$s single page.','wdt-portfolio'), strtolower($listing_singular_label) ),
				'default'     => ''
			) );

			$this->add_control( 'columns', array(
				'label'       => esc_html__( 'Columns','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					1 => esc_html__('One Column','wdt-portfolio'),
					2 => esc_html__('Two Column','wdt-portfolio'),
					3 => esc_html__('Three Column','wdt-portfolio'),
					4 => esc_html__('Four Column','wdt-portfolio'),
					5 => esc_html__('Five Column','wdt-portfolio')
				),
				'description' => esc_html__( 'Choose any of the available column sizes.','wdt-portfolio'),
				'default' => 4
			) );

			$this->add_control( 'show_image_description', array(
				'label'       => esc_html__( 'Show Image Description','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to show image description in carousel.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'with_space', array(
				'label'       => esc_html__( 'With Space','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to have space between images.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'include_featured_image', array(
				'label'       => esc_html__( 'Include Feature Image','wdt-portfolio'),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False','wdt-portfolio'),
					'true'  => esc_html__('True','wdt-portfolio'),
				),
				'description' => esc_html__('Choose "True" if you like to include featured image in this gallery.','wdt-portfolio'),
				'default'      => 'false'
			) );

			$this->add_control( 'image_ids', array(
				'label'       => esc_html__( 'Image Ids','wdt-portfolio'),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you wish to display particular images only than give image positions ids separated by commas.','wdt-portfolio'),
				'default'     => ''
			) );

			$this->add_control( 'class', array(
				'label'       => esc_html__( 'Class','wdt-portfolio'),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you wish you can add additional class name here.','wdt-portfolio'),
				'default'     => ''
			) );

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings();
		$attributes = wdtportfolio_elementor_instance()->wdt_parse_shortcode_attrs( $settings );
		$output = do_shortcode('[wdt_sp_media_images_list '.$attributes.' /]');

		echo $output;

	}

}