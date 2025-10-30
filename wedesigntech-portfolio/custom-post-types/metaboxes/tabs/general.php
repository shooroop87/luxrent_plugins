<div class="wdt-custom-box">

    <label><?php echo esc_html__('Page Template','wdt-portfolio'); ?></label>
    <?php echo wdt_listing_page_template_field($list_id, true); ?>

</div>

<?php
if((int)$author_id == (int)$user_id) {
    ?>
    <div class="wdt-custom-box">
        <label><?php echo esc_html__('Featured Item','wdt-portfolio'); ?></label>
        <?php
        $wdt_featured_item = get_post_meta($list_id, 'wdt_featured_item', true);
        $switchclass = ($wdt_featured_item == 'true') ? 'checkbox-switch-on' : 'checkbox-switch-off';
        $checked = ($wdt_featured_item == 'true') ? ' checked="checked"' : '';
        ?>
        <div data-for="wdt_featured_item" class="wdt-checkbox-switch <?php echo esc_attr( $switchclass );?>"></div>
        <input id="wdt_featured_item" class="hidden" type="checkbox" name="wdt_featured_item" value="true" <?php echo esc_attr( $checked );?> />
        <div class="wdt-note"> <?php echo esc_html__('If you like to set this item as featured, choose "Yes"','wdt-portfolio'); ?> </div>
    </div>
    <?php
}
?>

<div class="wdt-custom-box">

    <label><?php echo esc_html__('Excerpt Title','wdt-portfolio'); ?></label>
    <?php $wdt_excerpt_title = get_post_meta($list_id, 'wdt_excerpt_title', true); ?>
    <input name="wdt_excerpt_title" type="text" value="<?php echo esc_attr( $wdt_excerpt_title );?>" class="wdt-except-title" />
    <div class="wdt-note"><?php echo sprintf( esc_html__('Add Excerpt title for your %1$s here.','wdt-portfolio'), strtolower($listing_singular_label) ); ?> </div>

</div>