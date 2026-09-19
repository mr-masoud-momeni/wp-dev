<?php

function octo_theme_setup()
{

    /*
    |--------------------------------------------------------------------------
    | Theme Supports
    |--------------------------------------------------------------------------
    */

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo');

    add_theme_support('woocommerce');

    add_theme_support('menus');

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    */

    register_nav_menus([

        'main-menu'   => 'Main Menu',

        'mobile-menu' => 'Mobile Menu',

        'footer-menu' => 'Footer Menu',

    ]);
}

add_action('after_setup_theme', 'octo_theme_setup');


/*
|--------------------------------------------------------------------------
| Create Auth Page
|--------------------------------------------------------------------------
*/

function octo_create_auth_page()
{

    $page = get_page_by_path('auth');

    if ($page) {
        return;
    }

    wp_insert_post([

        'post_title'   => 'Sign in | Sign up',

        'post_name'    => 'auth',

        'post_status'  => 'publish',

        'post_type'    => 'page',

        'post_content' => '',

    ]);
}

add_action('after_switch_theme', 'octo_create_auth_page');

remove_action(
    'woocommerce_before_main_content',
    'woocommerce_breadcrumb',
    20
);