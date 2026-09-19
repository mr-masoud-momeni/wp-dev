<?php
/**
 * Plugin Name: Octo Fonts
 * Description: اضافه کردن فونت‌های دلخواه به المنتور و سایت‌های شبکه‌ای
 * Version: 1.0
 * Author: Octo
 */

// لود فایل css فونت‌ها
function octo_enqueue_custom_fonts() {
    wp_enqueue_style(
        'octo-fonts',
        plugin_dir_url(__FILE__) . 'fonts/fonts.css'
    );
}
add_action('wp_enqueue_scripts', 'octo_enqueue_custom_fonts');

// اضافه کردن فونت‌ها به المنتور
function octo_add_custom_fonts_group( $groups ) {
    $groups['octo'] = __( 'Octo Fonts', 'octo-fonts' );
    return $groups;
}
add_filter( 'elementor/fonts/groups', 'octo_add_custom_fonts_group' );

function octo_add_custom_fonts_list( $fonts ) {
    $fonts['IRANSans'] = 'octo'; 
    $fonts['Anjoman'] = 'octo';
    $fonts['iranyekan'] = 'octo';
    // اینجا هر فونتی بخوای اضافه کن
    return $fonts;
}
add_filter( 'elementor/fonts/additional_fonts', 'octo_add_custom_fonts_list' );
