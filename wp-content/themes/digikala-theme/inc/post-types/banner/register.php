<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Banner Post Type
 */
function octo_register_banner_post_type()
{
    $labels = [
        'name'               => 'Banners',
        'singular_name'      => 'Banner',
        'menu_name'          => 'Banners',
        'add_new'            => 'Add Banner',
        'add_new_item'       => 'Add New Banner',
        'edit_item'          => 'Edit Banner',
        'new_item'           => 'New Banner',
        'view_item'          => 'View Banner',
        'search_items'       => 'Search Banner',
        'not_found'          => 'No banners found',
        'not_found_in_trash' => 'No banners found in trash',
    ];

    register_post_type('banner', [
    
        'labels' => $labels,
    
        'public' => false,
    
        'publicly_queryable' => false,
    
        'exclude_from_search' => true,
    
        'show_ui' => true,
    
        'show_in_menu' => true,
    
        'menu_position' => 25,
    
        'menu_icon' => 'dashicons-images-alt2',
    
        'supports' => [
            'title',
            'thumbnail',
        ],
    
        'has_archive' => false,
    
        'rewrite' => false,
    
        'show_in_rest' => true,
    
    ]);
}

add_action('init', 'octo_register_banner_post_type');


/**
 * Banner Category
 */
function octo_register_banner_taxonomy()
{

    register_taxonomy(
        'banner_category',
        'banner',
        [

            'label' => 'Banner Groups',

            'public' => false,

            'show_ui' => true,

            'hierarchical' => true,

            'show_admin_column' => true,

            'rewrite' => false,

            'show_in_rest' => true,

        ]
    );

}

add_action('init', 'octo_register_banner_taxonomy');