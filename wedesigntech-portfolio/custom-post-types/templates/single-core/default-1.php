<?php
$listing_id = get_the_ID();
$listing_taxonomies = wp_get_post_terms($listing_id, 'wdt_listings_category', array ('orderby' => 'parent'));
$term_ids = array_column($listing_taxonomies, 'term_id');
$term_ids_str =  implode(',', $term_ids);
?>

<div class="wdt-portfolio-single-default ">
    <div class="wdt-portfolio-single-image-area">
            <?php echo do_shortcode('[wdt_sp_media_images_list columns="3" include_featured_image="true" with_space="true"  listing_id="'.$listing_id.'"]'); ?>
    </div>

    <div class="wdt-portfolio-single-content">
               <?php the_content(); ?>
    </div> 
</div>