<?php

define('OCTO_THEME_VERSION', '1.0.0');

function octo_theme_assets()
{

    /*
    |--------------------------------------------------------------------------
    | Bootstrap CSS
    |--------------------------------------------------------------------------
    */

    wp_enqueue_style(
        'bootstrap',
        get_theme_file_uri('/assets/css/bootstrap.min.css'),
        [],
        '5.3.8'
    );

    /*
    |--------------------------------------------------------------------------
    | Bootstrap ICONS
    |--------------------------------------------------------------------------
    */

    wp_enqueue_style(
        'bootstrap-icons',
        get_theme_file_uri('/assets/css/bootstrap-icons.min.css'),
        [],
        '1.11.3'
    );

    /*
    |--------------------------------------------------------------------------
    | Main Theme CSS
    |--------------------------------------------------------------------------
    */

    wp_enqueue_style(
        'octo-app',
        get_theme_file_uri('/assets/css/app.css'),
        ['bootstrap'],
        '1.0.0'
    );

    /*
    |--------------------------------------------------------------------------
    | Bootstrap JS
    |--------------------------------------------------------------------------
    */

    wp_enqueue_script(
        'bootstrap',
        get_theme_file_uri('/assets/js/bootstrap.min.js'),
        [],
        '5.3.8',
        true
    );

    /*
    |--------------------------------------------------------------------------
    | Theme JS
    |--------------------------------------------------------------------------
    */

    wp_enqueue_script(
        'octo-app',
        get_theme_file_uri('/assets/js/app.js'),
        ['bootstrap'],
        '1.0.0',
        true
    );
    
    /*
    |--------------------------------------------------------------------------
    | Product Page
    |--------------------------------------------------------------------------
    */
    
    if (is_product()) {

        wp_enqueue_style(
            'octo-single-product',
            get_theme_file_uri('/assets/css/single-product.css'),
            ['octo-app'],
            '1.0.0'
        );
    }
    
    wp_enqueue_style(
        'octo-product-carousel',
        get_template_directory_uri() .
        '/template-parts/components/product-carousel/product-carousel.css',
        [],
        '1.0.0'
    );
    
    wp_enqueue_script(
        'octo-product-carousel',
        get_template_directory_uri().
        '/template-parts/components/product-carousel/product-carousel.js',
        [],
        '1.0.0',
        true
    );
/*
|--------------------------------------------------------------------------
| Cart AJAX
|--------------------------------------------------------------------------
*/

    wp_enqueue_script(
    'wc-add-to-cart'
    );
    
    wp_enqueue_script(
        'octo-cart',
        get_template_directory_uri() . '/assets/js/cart.js',
        ['jquery', 'wc-add-to-cart'],
        OCTO_THEME_VERSION,
        true
    );
    
    wp_localize_script(
        'octo-cart',
        'octoCart',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'cartUrl' => wc_get_cart_url(),
        ]
    );
}

add_action('wp_enqueue_scripts', 'octo_theme_assets');


function octo_auth_assets()
{
    if (is_page('auth')) {

        wp_enqueue_style(
            'octo-auth',
            get_template_directory_uri() . '/assets/css/auth.css',
            [],
            '1.0'
        );

        wp_enqueue_script(
            'octo-auth',
            get_template_directory_uri() . '/assets/js/auth.js',
            [],
            '1.0',
            true
        );
        wp_localize_script(
            'octo-auth',
            'octoAuth',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
            ]
        );
    }
}

add_action('wp_enqueue_scripts', 'octo_auth_assets');


add_action('wp_enqueue_scripts', function () {
    wp_dequeue_script('devicepx');
    wp_deregister_script('devicepx');

    wp_dequeue_script('stats-js');
    wp_deregister_script('stats-js');
}, 100);