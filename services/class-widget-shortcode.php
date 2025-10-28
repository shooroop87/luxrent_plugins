<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Shortcode_Services {

    public  $widget_type;
	private $widget_path;
	private $widget_base;
	private $shortcode_slug;

	function __construct() {

		$this->widget_type = 'shortcode';
		$this->widget_path = dirname( __FILE__ );
        $this->widget_base = wedesigntech_widget_base_services();

		$this->set_shortcode_slug();
		add_action( 'wedesigntech_elementor_register_shortcodes', array( $this, 'register_shortcode' ) );
		add_action( 'wedesigntech_elementor_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wedesigntech_elementor_enqueue_styles', array( $this, 'enqueue_styles' ) );

	}

	public function set_shortcode_slug() {
		$widget_slug = $this->widget_base->name();
		$widget_slug = str_replace('-', '_', $widget_slug);
		return $this->shortcode_slug = $widget_slug;
	}

	public function register_shortcode() {
		add_shortcode ( $this->shortcode_slug, array ( $this, 'register_shortcode_html' ) );
	}

	public function register_shortcode_html($attrs, $content = null) {

        /* $attrs = shortcode_atts ( array (
			'class' => '',
		), $attrs, $this->shortcode_slug ); */

		$init_scripts = $this->widget_base->init_scripts();
		if(is_array($init_scripts) && !empty($init_scripts)) {
			foreach($init_scripts as $key => $script) {
				wp_enqueue_script($key);
			}
		}

		$init_styles = $this->widget_base->init_styles();
		if(is_array($init_styles) && !empty($init_styles)) {
			foreach($init_styles as $key => $style) {
				wp_enqueue_style($key);
			}
		}

		//return $this->widget_base->render_html($this, $attrs);

	}

	public function enqueue_scripts() {
		$init_scripts = $this->widget_base->init_scripts();
		if(is_array($init_scripts) && !empty($init_scripts)) {
			foreach($init_scripts as $key => $script) {
				if(!empty($script)) {
					wp_register_script( $key, $script, array( 'jquery' ), WEDESIGNTECH_ELEMENTOR_ADDON_VERSION, true );
				}
			}
		}
	}

	public function enqueue_styles() {
		$init_styles = $this->widget_base->init_styles();
		if(is_array($init_styles) && !empty($init_styles)) {
			foreach($init_styles as $key => $style) {
				if(!empty($style)) {
					wp_register_style( $key, $style, false, WEDESIGNTECH_ELEMENTOR_ADDON_VERSION, 'all' );
				}
			}
		}
	}
}


/* new WeDesignTech_Widget_Shortcode_Donations(); */