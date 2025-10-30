<div class="wdt-custom-box">

    <label><?php echo esc_html__('Email','wdt-portfolio'); ?></label>
    <?php $wdt_email = get_post_meta($list_id, 'wdt_email', true); ?>
    <input name="wdt_email" type="text" value="<?php echo esc_attr( $wdt_email );?>" />
    <div class="wdt-note"><?php echo sprintf( esc_html__('Add contact email for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?> </div>

</div>

<div class="wdt-custom-box">

    <label><?php echo esc_html__('Phone','wdt-portfolio'); ?></label>
    <?php $wdt_phone = get_post_meta($list_id, 'wdt_phone', true); ?>
    <input name="wdt_phone" type="text" value="<?php echo esc_attr( $wdt_phone );?>" />
    <div class="wdt-note"><?php echo sprintf( esc_html__('Add contact phone number for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?> </div>

</div>

<div class="wdt-custom-box">

    <label><?php echo esc_html__('Mobile','wdt-portfolio'); ?></label>
    <?php $wdt_mobile = get_post_meta($list_id, 'wdt_mobile', true); ?>
    <input name="wdt_mobile" type="text" value="<?php echo esc_attr( $wdt_mobile );?>" />
    <div class="wdt-note"><?php echo sprintf( esc_html__('Add contact mobile number for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?> </div>

</div>

<div class="wdt-custom-box">

    <label><?php echo esc_html__('Website','wdt-portfolio'); ?></label>
    <?php $wdt_website = get_post_meta($list_id, 'wdt_website', true); ?>
    <input name="wdt_website" type="text" value="<?php echo esc_attr( $wdt_website );?>" />
    <div class="wdt-note"><?php echo sprintf( esc_html__('Add website address for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?> </div>

</div>
<div class="wdt-custom-box">

    <label><?php echo esc_html__('Location','wdt-portfolio'); ?></label>
    <?php $wdt_location = get_post_meta($list_id, 'wdt_location', true); ?>
    <textarea name="wdt_location" rows="3"><?php echo esc_textarea( $wdt_location );?></textarea>
    <div class="wdt-note"><?php echo sprintf( esc_html__('Add location for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?> </div>

</div>

<div class="wdt-custom-box">

    <label><?php echo esc_html__('Social Details','wdt-portfolio'); ?></label>
    <?php echo wdt_social_details_field($list_id, 'list'); ?>

</div>