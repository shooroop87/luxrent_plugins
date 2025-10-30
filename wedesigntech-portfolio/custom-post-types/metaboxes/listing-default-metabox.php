<?php
global $post;
$list_id = $post->ID;

$author_id = get_post_field( 'post_author', $list_id );
$user_id = get_current_user_id();


echo '<input type="hidden" name="wdt_classes_meta_nonce" value="'.wp_create_nonce('wdt_classes_nonce').'" />';

$listing_singular_label = apply_filters( 'listing_label', 'singular' );
$listing_plural_label = apply_filters( 'listing_label', 'plural' );

$tabs = array (
    'general'   => array (
        'label' => esc_html__('General','wdt-portfolio'),
        'icon' => 'far fa-eye',
        'path' => WDT_PLUGIN_PATH . 'custom-post-types/metaboxes/tabs/general.php'
    ),
    'features'   => array (
        'label' => esc_html__('Features','wdt-portfolio'),
        'icon' => 'fas fa-puzzle-piece',
        'path' => WDT_PLUGIN_PATH . 'custom-post-types/metaboxes/tabs/features.php'
    ),
    'contact-info'   => array (
        'label' => esc_html__('Contact Information','wdt-portfolio'),
        'icon' => 'fas fa-info-circle',
        'path' => WDT_PLUGIN_PATH . 'custom-post-types/metaboxes/tabs/contact-info.php'
    )
);

$tabs = apply_filters( 'wdt_metabox_tabs', $tabs );

?>

<div class="wdt-tabs-vertical-container" data-effect="fade">

    <ul class="wdt-tabs-vertical">
        <?php
        $i = 0;
        foreach($tabs as $tab) {

            $class = '';
            if($i == 0) { $class = 'current'; }

            echo '<li class="'.esc_attr( $class ).'"><a href="javascript:void(0);" class="'.esc_attr( $class ).'"><span class="'.esc_attr( $tab['icon'] ).'"></span>'.$tab['label'].'</a></li>';

            $i++;
        }
        ?>
    </ul>

    <?php
    $i = 0;
    foreach($tabs as $tab) {

        $style_attr = '';
        if($i == 0) { $style_attr = 'style="display: block;"'; }

        echo '<div class="wdt-tabs-vertical-content" '.$style_attr.'>';
            echo '<h3 class="wdt-tab-title">'.$tab['label'].'</h3>';

            ob_start();
            require $tab['path'];
            $tab_content = ob_get_contents();
            ob_end_clean();

            echo $tab_content;

        echo '</div>';

        $i++;

    }
    ?>

</div>