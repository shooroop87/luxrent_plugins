<?php

// Dashboard Media Field
if(!function_exists('wdt_listing_upload_media_field')) {
    function wdt_listing_upload_media_field($item_id) {

        $output = '';

        $wdt_media_images_ids = $wdt_media_images_titles = array ();
        $wdt_featured_image_id = -1;
        if($item_id > 0) {
            $wdt_media_images_ids    = get_post_meta($item_id, 'wdt_media_images_ids', true);
            $wdt_media_images_titles = get_post_meta($item_id, 'wdt_media_images_titles', true);
            $wdt_featured_image_id   = get_post_thumbnail_id($item_id);
        }

        $output .= '<div class="wdt-upload-media-items-container">';

            if(is_array($wdt_media_images_ids) && !empty($wdt_media_images_ids)) {

                $output .= '<div class="wdt-upload-media-items-holder">';
                    $output .= '<ul class="wdt-upload-media-items">';

                        $i = 0;
                        foreach($wdt_media_images_ids as $wdt_media_attachments_id) {
                            if($wdt_media_attachments_id != '') {
                                $wdt_media_title = '';
                                if(isset($wdt_media_images_titles[$i])) {
                                    $wdt_media_title = $wdt_media_images_titles[$i];
                                }
                                $thumbnail_url = wp_get_attachment_image_src($wdt_media_attachments_id, 'thumbnail');
                                $featured_item_class = 'far fa-user';
                                if($wdt_featured_image_id == $wdt_media_attachments_id) {
                                    $featured_item_class = 'fa fa-user';
                                }
                                $output .= '<li>
                                                <img src="'.esc_url($thumbnail_url[0]).'" title="'.esc_attr__('Media Title','wdt-portfolio').'" all="'.esc_attr__('Media Title','wdt-portfolio').'" />
                                                <input name="wdt_media_attachment_ids[]" type="hidden" class="uploadfieldid hidden" readonly value="'.esc_attr( $wdt_media_attachments_id ).'"/>
                                                <input name="wdt_media_attachment_titles[]" type="text" class="media-attachment-titles" value="'.esc_attr( $wdt_media_title ).'"/>
                                                <span class="wdt-remove-media-item"><span class="fas fa-times"></span></span>
                                                <span class="wdt-featured-media-item"><span class="'.esc_attr( $featured_item_class ).'"></span></span>
                                            </li>';
                                $i++;
                            }
                        }

                    $output .= '</ul>';
                $output .= '</div>';

            }

            $output .= '<input type="hidden" value="'.esc_attr($wdt_featured_image_id).'" name="wdt_featured_image_id" id="wdt_featured_image_id" />';

            $output .= '<input type="button" value="'.esc_html__('Upload Media','wdt-portfolio').'" class="wdt-upload-media-item-button multiple" />';
            $output .= '<input type="button" value="'.esc_html__('Remove Media','wdt-portfolio').'" class="wdt-upload-media-item-reset" />';

        $output .= '</div>';

        return $output;

    }
}

?>