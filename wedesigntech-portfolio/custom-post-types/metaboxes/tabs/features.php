<div class="wdt-custom-box">

    <label><?php echo esc_html__('Features','wdt-portfolio'); ?></label>
    <?php
    echo wdt_listing_features_field($list_id);
    ?>
    <div class="wdt-note">
        <?php
        echo '<strong>'.esc_html__('Note:','wdt-portfolio').'</strong>'."<br>";
        echo '<ul>';
            echo '<li>'.esc_html__('Use icon or image don\'t use both.','wdt-portfolio').'</li>';
            echo '<li>'.esc_html__('First field with numeric value is the tab id of this features item, which can be used in search form.','wdt-portfolio').'</li>';
            echo '<li>'.sprintf( esc_html__('Don\'t change the order of items for other %1$s.','wdt-portfolio'), strtolower($listing_plural_label) ).'</li>';
            echo '<li>'.sprintf( esc_html__('If anyone of the %1$s doesn\'t have any particular item add that with empty value, so that features search field will work correctly.','wdt-portfolio'), strtolower($listing_plural_label) ).'</li>';
        echo '</ul>';
        ?>
    </div>

</div>