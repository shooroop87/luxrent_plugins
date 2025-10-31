<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Elementor_Google_Map extends WeDesignTech_Elementor_Widget_Base {

	public $widget_type;
	private $widget_path;
	private $widget_base;

	function __construct( array $data = [], $args = null ) {

		$this->widget_type = 'elementor';
		$this->widget_path = dirname( __FILE__ );
        $this->widget_base = wedesigntech_widget_base_google_map();

		parent::__construct( $data, $args );

	}

	public function get_name() {
		return $this->widget_base->name();
	}

	public function get_title() {
		return $this->widget_base->title();
	}

	public function get_icon() {
		return $this->widget_base->icon();
	}

	public function get_script_depends() {

		$scripts_to_load = array ();
		$init_scripts = $this->widget_base->init_scripts();
		if(is_array($init_scripts) && !empty($init_scripts)) {
			foreach($init_scripts as $key => $script) {
				if(!empty($script)) {
					wp_register_script( $key, $script, array( 'jquery' ), false, false );
				}
			}
			$scripts_to_load = array_keys($init_scripts);
		}

		return $scripts_to_load;

	}

	public function get_style_depends() {

		$styless_to_load = array ();
		$init_styles = $this->widget_base->init_styles();
		if(is_array($init_styles) && !empty($init_styles)) {
			foreach($init_styles as $key => $style) {
				if(!empty($style)) {
					wp_register_style( $key, $style, false, WEDESIGNTECH_ELEMENTOR_ADDON_VERSION, 'all' );
				}
			}
			$styless_to_load = array_keys($init_styles);
		}

		$init_inline_styles = $this->widget_base->init_inline_styles();
		if(is_array($init_inline_styles) && !empty($init_inline_styles)) {
			foreach($init_inline_styles as $key => $css) {
				if(!empty($css)) {
					wp_add_inline_style( $key, $css );
				}
			}
		}

		return $styless_to_load;

	}

	protected function register_controls() {
		$this->widget_base->create_elementor_controls($this);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo $this->widget_base->render_html($this, $settings);
	}

}


wedesigntech_elementor_get_elementor_widgets_manager()->register( new WeDesignTech_Widget_Elementor_Google_Map() );