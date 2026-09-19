<?php

function octo_register_post_types()
{
    register_post_type('octo_block', [

        'labels' => [

            'name'          => 'Content Blocks',
            'singular_name' => 'Content Block',

        ],

        'public' => true,

        // این باید true باشه تا UI نامک بیاد
        'publicly_queryable' => true,

        'show_ui' => true,

        'show_in_menu' => true,

        'show_in_rest' => true,

        'menu_icon' => 'dashicons-screenoptions',

        'supports' => [
            'title',
            'editor',
            'revisions',
        ],

        'has_archive' => false,

        'exclude_from_search' => true,

        'rewrite' => [
            'slug' => 'octo-block',
            'with_front' => false,
        ],

    ]);
}

add_action('init', 'octo_register_post_types');
