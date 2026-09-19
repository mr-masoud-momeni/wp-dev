<?php
/**
 * Plugin Name: WP Mega Menu Elementor Lite
 * Description: Mega menu via Elementor templates using menu item class: mega-{TEMPLATE_ID} (position: fixed, full width)
 * Version: 1.5.8
 * Author: You
 */
if (!defined('ABSPATH')) exit;

/**
 * Inject Elementor template under menu item if it has class "mega-{id}"
 */
add_filter('walker_nav_menu_start_el', function($item_output, $item, $depth, $args) {

    if (!empty($item->classes) && is_array($item->classes)) {
        foreach ($item->classes as $class) {
            if (strpos($class, 'mega-') === 0) {
                $template_id = preg_replace('/^mega-/', '', $class);
                if (ctype_digit($template_id)) {

                    $template_id_int = intval($template_id);

                    // inject template مستقل برای این آیتم منو
                    $item_output .= '<div class="custom-mega-menu" data-octo-template-id="' . esc_attr($template_id_int) . '" aria-hidden="true">'
                                  . do_shortcode('[elementor-template id="' . esc_attr($template_id_int) . '"]')
                                  . '<span class="mega-arrow" aria-hidden="true"></span>'
                                  . '</div>';
                }
            }
        }
    }

    return $item_output;

}, 20, 4);

/**
 * Enqueue CSS & JS
 */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'wp-mega-menu-elementor-css',
        plugin_dir_url(__FILE__) . 'css/wp-mega-menu-elementor.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'wp-mega-menu-elementor-js',
        plugin_dir_url(__FILE__) . 'js/wp-mega-menu-elementor.js',
        ['jquery'],
        '1.0',
        true
    );
});
