<?php get_header('wdt'); ?>

    <?php
    /**
    * wdt_before_main_content hook.
    */
    do_action( 'wdt_before_main_content' );
    ?>

        <?php
        /**
        * wdt_before_content hook.
        */
        do_action( 'wdt_before_content' );
        ?>


            <?php

            $queried_object_id = get_queried_object_id();
            $posts_per_page    = get_option('posts_per_page');

            $archive_page_type                   = !empty( wdt_option('archives','archive-page-type') ) ? wdt_option('archives','archive-page-type') : 'type1';
            $archive_page_gallery                = !empty( wdt_option('archives','archive-page-gallery') ) ? wdt_option('archives','archive-page-gallery') : 'featured_image';
            $archive_page_column                 = wdt_option('archives','archive-page-column');
            $archive_page_apply_isotope          = wdt_option('archives','archive-page-apply-isotope');
            $archive_page_excerpt_length         = wdt_option('archives','archive-page-excerpt-length');
            $archive_page_excerpt_length = (isset($archive_page_excerpt_length) && !empty($archive_page_excerpt_length)) ? $archive_page_excerpt_length : 20;
            $archive_page_features_image_or_icon = wdt_option('archives','archive-page-features-image-or-icon');
            $archive_page_features_include       = wdt_option('archives','archive-page-features-include');
            $archive_page_noofcat_to_display     = wdt_option('archives','archive-page-noofcat-to-display');

            echo do_shortcode('[wdt_listings_listing type="'.$archive_page_type.'" gallery="'.$archive_page_gallery.'" post_per_page="'.$posts_per_page.'" columns="'.$archive_page_column.'" apply_isotope="'.$archive_page_apply_isotope.'" excerpt_length="'.$archive_page_excerpt_length.'" features_image_or_icon="'.$archive_page_features_image_or_icon.'" features_include="'.$archive_page_features_include.'" no_of_cat_to_display="'.$archive_page_noofcat_to_display.'" category_ids="'.$queried_object_id.'" enable_carousel="false"]');

            ?>

        <?php
        /**
        * wdt_after_content hook.
        */
        do_action( 'wdt_after_content' );
        ?>

    <?php
    /**
    * wdt_after_main_content hook.
    */
    do_action( 'wdt_after_main_content' );
    ?>

<?php get_footer('wdt'); ?>