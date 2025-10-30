<?php

require_once WDT_PLUGIN_PATH . 'settings/settings-utils.php';


function wdt_settings_options() {

	$tabs = array (
		'general'   => array (
			'label' => esc_html__('General','wdt-portfolio'),
			'path' => WDT_PLUGIN_PATH . 'settings/settings-general-utils.php'
		),
		'label'     =>  array (
			'label' => esc_html__('Labels','wdt-portfolio'),
			'path' => WDT_PLUGIN_PATH . 'settings/settings-label-utils.php'
		),
		'permalink' =>  array (
			'label' => esc_html__('Permalink','wdt-portfolio'),
			'path' => WDT_PLUGIN_PATH . 'settings/settings-permalink-utils.php'
		),
		'archives' =>  array (
			'label' => esc_html__('Archives','wdt-portfolio'),
			'path' => WDT_PLUGIN_PATH . 'settings/settings-archives-utils.php'
		),
	);

	$tabs = apply_filters( 'wdt_settings', $tabs );

	$current = isset( $_GET['parenttab'] ) ? wdt_sanitize_fields($_GET['parenttab']) : 'general';

	wdt_get_settings_submenus($current, $tabs);
	wdt_get_settings_tab($current, $tabs);

}

function wdt_get_settings_submenus($current, $tabs) {

    echo '<h2 class="wdt-custom-nav nav-tab-wrapper">';
		foreach( $tabs as $key => $tab ) {
			$class = ( $key == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.esc_attr( $class ).'" href="?post_type=wdt_listings&page=wdt-settings-options&parenttab='.esc_attr( $key ).'">'.esc_html( $tab['label'] ).'</a>';
		}
    echo '</h2>';

}

function wdt_get_settings_tab($current, $tabs) {
	require_once $tabs[$current]['path'];
}

?>