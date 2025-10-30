<?php
// Plugin default settings
if(!function_exists('wdt_plugins_default_settings')) {
	function wdt_plugins_default_settings() {

		$general_settings = array (
			'container-width'                => 1230,
			'single-page-template'           => 'default-template',
			'backend-postperpage'            => 10,
			'frontend-postperpage'           => 10,
			'restrict-counter-overuserip'    => 'true',
			'should-admin-approve-listings'  => 'true'
        );

		$label_settings = array (
			'listing-singular-label'      => esc_html__('Portfolio','wdt-portfolio'),
			'listing-plural-label'        => esc_html__('Portfolios','wdt-portfolio'),
			'amenity-singular-label'      => esc_html__('Tag','wdt-portfolio'),
			'amenity-plural-label'        => esc_html__('Tags','wdt-portfolio')
        );

		$permalink_settings = array (
			'listing-slug'              => 'listings',
			'listing-category-slug'     => 'listing-category',
			'listing-amenity-slug'      => 'listing-tag'
        );

		$archives_settings = array (
			'archive-page-type'                   => 'type1',
			'archive-page-gallery'                => 'featured_image',
			'archive-page-column'                 => 3,
			'archive-page-apply-isotope'          => 'true',
			'archive-page-excerpt-length'         => 20,
			'archive-page-features-image-or-icon' => 'image',
			'archive-page-features-include'       => '0,1,2',
			'archive-page-noofcat-to-display'     => 2
		);

		$wdt_settings = array (
			'general'       => $general_settings,
			'label'         => $label_settings,
			'permalink'     => $permalink_settings,
			'archives'      => $archives_settings,
		);

		return $wdt_settings;
	}
}

// Retrieve Options
if(!function_exists('wdt_option')) {
	function wdt_option($key1, $key2 = '') {

		$options = get_option('wdt-settings');

		$output = '';

		if (is_array ( $options ) && ! empty ( $options )) {
			if (array_key_exists ( $key1, $options )) {
				$output = $options [$key1];
				if (is_array ( $output ) && ! empty ( $key2 )) {
					$output = (array_key_exists ( $key2, $output ) && (! empty ( $output [$key2] ))) ? $output [$key2] : '';
				}
			}
		} else {
			$options = array ();
		}

		if( empty ( $output ) ) {
			if(!array_key_exists ( 'plugin-status', $options ) || $options['plugin-status'] != 'activated') {

				$wdt_default_settings = wdt_plugins_default_settings();
				if (array_key_exists ( $key1, $wdt_default_settings )) {
					$output = $wdt_default_settings [$key1];
					if (is_array ( $output ) && ! empty ( $key2 )) {
						$output = (array_key_exists ( $key2, $output ) && (! empty ( $output [$key2] ))) ? $output [$key2] : '';
					}
				}

			} else if($options['plugin-status'] == 'activated' && ( $key1 == 'label' || $key1 == 'permalink' || $key1 == 'map' )) {

				$wdt_default_settings = wdt_plugins_default_settings();
				if (array_key_exists ( $key1, $wdt_default_settings )) {
					$output = $wdt_default_settings [$key1];
					if (is_array ( $output ) && ! empty ( $key2 )) {
						$output = (array_key_exists ( $key2, $output ) && (! empty ( $output [$key2] ))) ? $output [$key2] : '';
					}
				}

			}
		}

		return $output;

	}
}

// Site SSL Compatibility
if(!function_exists('wdt_ssl')) {
	function wdt_ssl( $echo = false ){
		$ssl = '';
		if( is_ssl() ) $ssl = 's';
		if( $echo ){
			echo ($ssl);
		}
		return $ssl;
	}
}


global $wdt_allowed_html_tags;
$wdt_allowed_html_tags = array(
	'a' => array('class' => array(), 'href' => array(), 'title' => array(), 'target' => array()),
	'abbr' => array('title' => array()),
	'address' => array(),
	'area' => array('shape' => array(), 'coords' => array(), 'href' => array(), 'alt' => array()),
	'article' => array(),
	'aside' => array(),
	'audio' => array('autoplay' => array(), 'controls' => array(), 'loop' => array(), 'muted' => array(), 'preload' => array(), 'src' => array()),
	'b' => array(),
	'base' => array('href' => array(), 'target' => array()),
	'bdi' => array(),
	'bdo' => array('dir' => array()),
	'blockquote' => array('cite' => array()),
	'br' => array(),
	'button' => array('autofocus' => array(), 'disabled' => array(), 'form' => array(), 'formaction' => array(), 'formenctype' => array(), 'formmethod' => array(), 'formnovalidate' => array(), 'formtarget' => array(), 'name' => array(), 'type' => array(), 'value' => array()),
	'canvas' => array('height' => array(), 'width' => array()),
	'caption' => array('align' => array()),
	'cite' => array(),
	'code' => array(),
	'col' => array(),
	'colgroup' => array(),
	'datalist' => array('id' => array()),
	'dd' => array(),
	'del' => array('cite' => array(), 'datetime' => array()),
	'details' => array('open' => array()),
	'dfn' => array(),
	'dialog' => array('open' => array()),
	'div' => array('class' => array(), 'id' => array(), 'align' => array()),
	'dl' => array(),
	'dt' => array(),
	'em' => array(),
	'embed' => array('height' => array(), 'src' => array(), 'type' => array(), 'width' => array()),
	'fieldset' => array('disabled' => array(), 'form' => array(), 'name' => array()),
	'figcaption' => array(),
	'figure' => array(),
	'form' => array('accept' => array(), 'accept-charset' => array(), 'action' => array(), 'autocomplete' => array(), 'enctype' => array(), 'method' => array(), 'name' => array(), 'novalidate' => array(), 'target' => array(), 'id' => array(), 'class' => array()),
	'h1' => array('class' => array()), 'h2' => array('class' => array()), 'h3' => array('class' => array()), 'h4' => array('class' => array()), 'h5' => array('class' => array()), 'h6' => array('class' => array()),
	'hr' => array(),
	'i' => array('class' => array()),
	'iframe' => array('name' => array(), 'seamless' => array(), 'src' => array(), 'srcdoc' => array(), 'width' => array()),
	'img' => array('alt' => array(), 'crossorigin' => array(), 'height' => array(), 'ismap' => array(), 'src' => array(), 'usemap' => array(), 'width' => array()),
	'input' => array('align' => array(), 'alt' => array(), 'autocomplete' => array(), 'autofocus' => array(), 'checked' => array(), 'disabled' => array(), 'form' => array(), 'formaction' => array(), 'formenctype' => array(), 'formmethod' => array(), 'formnovalidate' => array(), 'formtarget' => array(), 'height' => array(), 'list' => array(), 'max' => array(), 'maxlength' => array(), 'min' => array(), 'multiple' => array(), 'name' => array(), 'pattern' => array(), 'placeholder' => array(), 'readonly' => array(), 'required' => array(), 'size' => array(), 'src' => array(), 'step' => array(), 'type' => array(), 'value' => array(), 'width' => array(), 'id' => array(), 'class' => array()),
	'ins' => array('cite' => array(), 'datetime' => array()),
	'label' => array('for' => array(), 'form' => array()),
	'legend' => array('align' => array()),
	'li' => array('type' => array(), 'value' => array(), 'class' => array()),
	'link' => array('crossorigin' => array(), 'href' => array(), 'hreflang' => array(), 'media' => array(), 'rel' => array(), 'sizes' => array(), 'type' => array()),
	'main' => array(),
	'map' => array('name' => array()),
	'mark' => array(),
	'menu' => array('label' => array(), 'type' => array()),
	'menuitem' => array('checked' => array(), 'command' => array(), 'default' => array(), 'disabled' => array(), 'icon' => array(), 'label' => array(), 'radiogroup' => array(), 'type' => array()),
	'meta' => array('charset' => array(), 'content' => array(), 'http-equiv' => array(), 'name' => array()),
	'object' => array('form' => array(), 'height' => array(), 'name' => array(), 'type' => array(), 'usemap' => array(), 'width' => array()),
	'ol' => array('class' => array(), 'reversed' => array(), 'start' => array(), 'type' => array()),
	'p' => array('class' => array()),
	'q' => array('cite' => array()),
	'section' => array(),
	'select' => array('autofocus' => array(), 'disabled' => array(), 'form' => array(), 'multiple' => array(), 'name' => array(), 'required' => array(), 'size' => array()),
	'small' => array(),
	'source' => array('media' => array(), 'src' => array(), 'type' => array()),
	'span' => array('class' => array()),
	'strong' => array(),
	'style' => array('media' => array(), 'scoped' => array(), 'type' => array()),
	'sub' => array(),
	'sup' => array(),
	'table' => array('sortable' => array()),
	'tbody' => array(),
	'td' => array('colspan' => array(), 'headers' => array()),
	'textarea' => array('autofocus' => array(), 'cols' => array(), 'disabled' => array(), 'form' => array(), 'maxlength' => array(), 'name' => array(), 'placeholder' => array(), 'readonly' => array(), 'required' => array(), 'rows' => array(), 'wrap' => array()),
	'tfoot' => array(),
	'th' => array('abbr' => array(), 'colspan' => array(), 'headers' => array(), 'rowspan' => array(), 'scope' => array(), 'sorted' => array()),
	'thead' => array(),
	'time' => array('datetime' => array()),
	'title' => array(),
	'tr' => array(),
	'track' => array('default' => array(), 'kind' => array(), 'label' => array(), 'src' => array(), 'srclang' => array()),
	'u' => array(),
	'ul' => array('class' => array()),
	'var' => array(),
	'video' => array('autoplay' => array(), 'controls' => array(), 'height' => array(), 'loop' => array(), 'muted' => array(), 'muted' => array(), 'poster' => array(), 'preload' => array(), 'src' => array(), 'width' => array()),
	'wbr' => array(),
);

function wdt_wp_kses($content) {
	global $wdt_allowed_html_tags;
	$data = wp_kses($content, $wdt_allowed_html_tags);
	return $data;
}

// Filter HTML Output

if ( ! function_exists( 'wdt_html_output' ) ) {

	function wdt_html_output( $html ) {

		return apply_filters( 'wdt_html_output', $html );

	}

}

function wdt_ajax_pagination($max_num_pages, $current_page, $function_call, $output_div, $item_ids) {

	$output = '';

	if($max_num_pages > 1) {

		$user_id = $dashboard_page_id = $post_per_page = -1;
		$loader = $loader_parent = '';

		if(isset($item_ids['user_id']) && $item_ids['user_id'] != '') {
			$user_id = $item_ids['user_id'];
		}
		if(isset($item_ids['dashboard_page_id']) && $item_ids['dashboard_page_id'] != '') {
			$dashboard_page_id = $item_ids['dashboard_page_id'];
		}
		if(isset($item_ids['loader']) && $item_ids['loader'] != '') {
			$loader = $item_ids['loader'];
		}
		if(isset($item_ids['loader_parent']) && $item_ids['loader_parent'] != '') {
			$loader_parent = $item_ids['loader_parent'];
		}
		if(isset($item_ids['post_per_page']) && $item_ids['post_per_page'] != '') {
			$post_per_page = $item_ids['post_per_page'];
		}

		$output .= '<div class="wdt-pagination wdt-default-pagination wdt-ajax-pagination" data-postperpage="'.esc_attr( $post_per_page ).'" data-functioncall="'.esc_attr( $function_call ).'" data-outputdiv="'.esc_attr( $output_div ).'" data-userid="'.esc_attr( $user_id ).'" data-dashboardpageid="'.esc_attr( $dashboard_page_id ).'" data-loader="'.esc_attr( $loader ).'" data-loaderparent="'.esc_attr( $loader_parent ).'">';

			if($current_page > 1) {
				$output .= '<div class="prev-post"><a href="#" data-currentpage="'.esc_attr( $current_page ).'"><span class="fa fa-caret-left"></span>&nbsp;'.esc_html__('Prev','wdt-portfolio').'</a></div>';
			}

			$output .= paginate_links ( array (
				'base' 		 => '#',
				'format' 		 => '',
				'current' 	 => $current_page,
				'type'     	 => 'list',
				'end_size'     => 2,
				'mid_size'     => 3,
				'prev_next'    => false,
				'total' 		 => $max_num_pages
			) );

			if ($current_page < $max_num_pages) {
				$output .= '<div class="next-post"><a href="#" data-currentpage="'.esc_attr( $current_page ).'">'.esc_html__('Next','wdt-portfolio').'&nbsp;<span class="fa fa-caret-right"></span></a></div>';
			}

		$output .= '</div>';

    }

    return $output;
}

function wdt_generate_loader_html($add_first_class = true) {

	$add_first_class_item = '';
	if($add_first_class) {
		$add_first_class_item .= 'first';
	}

	$output = '<div class="wdt-ajax-load-image '.esc_attr( $add_first_class_item ).'" style="display:none;">
		<div class="wdt-loader-inner">
		</div>
	</div>';

    return $output;

}

function wdt_email_configuration($to, $subject, $content) {

    $message = $content;

	$admin_email = get_option('admin_email');

	$headers = 'From: '.$admin_email."\r\n";
	$headers .= 'Reply-To: '.$admin_email."\r\n";
	$headers .= 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1'."\r\n";

    wp_mail($to, $subject, $message, $headers);

}


/* ---------------------------------------------------------------------------
 * Hexadecimal to RGB color conversion
 * --------------------------------------------------------------------------- */
if(!function_exists('wdt_hex2rgb')) {
	function wdt_hex2rgb($hex) {

		$pos = strpos($hex, '#');

		if( is_int($pos) ):
			$hex = str_replace ( "#", "", $hex );

			if (strlen ( $hex ) == 3) :
				$r = hexdec ( substr ( $hex, 0, 1 ) . substr ( $hex, 0, 1 ) );
				$g = hexdec ( substr ( $hex, 1, 1 ) . substr ( $hex, 1, 1 ) );
				$b = hexdec ( substr ( $hex, 2, 1 ) . substr ( $hex, 2, 1 ) );
			 else :
				$r = hexdec ( substr ( $hex, 0, 2 ) );
				$g = hexdec ( substr ( $hex, 2, 2 ) );
				$b = hexdec ( substr ( $hex, 4, 2 ) );
			endif;
		else:
			$spos = strpos($hex, '(');
			$epos = strripos($hex, ',');
			$spos += 1;
			$n = $epos - $spos;

			$c = substr($hex, $spos, $n);
			$c = explode(',', $c);

			$r = isset($c[0]) ? $c[0] : '';
			$g = isset($c[1]) ? $c[1] : '';
			$b = isset($c[2]) ? $c[2] : '';
		endif;

		$rgb = array($r, $g, $b);
		return $rgb;
	}
}


/* ---------------------------------------------------------------------------
 * Excerpt with Custom Excrept Length
 * --------------------------------------------------------------------------- */
if(!function_exists('wdt_custom_excerpt')) {
	function wdt_custom_excerpt( $count, $post_id ) {

		$excerpt = explode(' ', get_the_excerpt($post_id), $count);

		if (count($excerpt) >= $count && $count > 0) {
			array_pop($excerpt);
			$excerpt = implode(' ', $excerpt).'...';
		} else {
			$excerpt = implode(' ', $excerpt);
		}

		$excerpt = preg_replace('`[[^]]*]`', '', $excerpt);

		return $excerpt;

	}
}

// Add Capabilities for Administrator
if(!function_exists('wdt_add_admin_caps')) {

    function wdt_add_admin_caps() {

        $role = get_role('administrator');
        $role->add_cap('create_wdt_listings');
        $role->add_cap('publish_wdt_listings');
        $role->add_cap('read_wdt_listing');
        $role->add_cap('delete_wdt_listing');
        $role->add_cap('edit_wdt_listing');
        $role->add_cap('edit_wdt_listings');
        $role->add_cap('delete_wdt_listings');
        $role->add_cap('edit_published_wdt_listings');
        $role->add_cap('delete_published_wdt_listings');
        $role->add_cap('read_private_wdt_listings');
        $role->add_cap('delete_private_wdt_listings');
        $role->add_cap('edit_others_wdt_listings');
        $role->add_cap('delete_others_wdt_listings');
        $role->add_cap('edit_private_wdt_listings');
        $role->add_cap('delete_private_wdt_listings');
        $role->add_cap('edit_published_wdt_listings');

    }

    add_action('admin_init', 'wdt_add_admin_caps');
}

/**
 * Recursive sanitation for text or array
 */
function wdt_sanitize_fields($data) {
    if( is_string($data) ) {
        $data = sanitize_text_field($data);
    } elseif( is_array($data) ) {
        foreach ( $data as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = wdt_sanitize_fields($value);
            } else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $data;
}


if(!function_exists('wdt_remove_listing_featured_image')) {
    function wdt_remove_listing_featured_image() {
        remove_meta_box( 'postimagediv','wdt_listings','side' );
    }
    add_action('do_meta_boxes', 'wdt_remove_listing_featured_image');
}