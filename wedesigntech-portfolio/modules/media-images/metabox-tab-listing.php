<div class="wdt-custom-box">

    <label><?php echo esc_html__('Add Images','wdt-portfolio'); ?></label>
    <?php echo wdt_listing_upload_media_field($list_id); ?>

    <div class="wdt-note">
        <?php echo sprintf( esc_html__('Add images for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?>
    </div>

</div>