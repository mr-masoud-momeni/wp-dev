<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Slider Settings
 */
function octo_get_banner_settings($category_slug)
{
    $term = get_term_by(
        'slug',
        $category_slug,
        'banner_category'
    );

    if (!$term) {
        return [];
    }

    $settings = get_term_meta(
        $term->term_id,
        '_octo_slider_settings',
        true
    );

    return wp_parse_args(
        is_array($settings) ? $settings : [],
        octo_banner_slider_default_settings()
    );
}

/**
 * Format Banner
 */
function octo_format_banner($post)
{
    if (!$post) {
        return [];
    }

    $post_id = is_object($post) ? $post->ID : (int) $post;

    return [

        'id' => $post_id,

        'title' => get_the_title($post_id),

        'desktop_image' => get_the_post_thumbnail_url(
            $post_id,
            'full'
        ),

        'mobile_image' => wp_get_attachment_image_url(
            get_post_meta(
                $post_id,
                '_octo_mobile_image',
                true
            ),
            'full'
        ),

        'desktop_link' => get_post_meta(
            $post_id,
            '_octo_desktop_link',
            true
        ),

        'mobile_link' => get_post_meta(
            $post_id,
            '_octo_mobile_link',
            true
        ),

        'priority' => (int) get_post_meta(
            $post_id,
            '_octo_priority',
            true
        ),

        'target' => get_post_meta(
            $post_id,
            '_octo_new_tab',
            true
        ) ? '_blank' : '_self',

    ];
}

/**
 * Get Banner Items
 */
function octo_get_banner_items($category_slug)
{
    $query = new WP_Query([

        'post_type' => 'banner',

        'posts_per_page' => -1,

        'post_status' => 'publish',

        'orderby' => 'meta_value_num',

        'meta_key' => '_octo_priority',

        'order' => 'ASC',

        'tax_query' => [

            [

                'taxonomy' => 'banner_category',

                'field' => 'slug',

                'terms' => $category_slug,

            ]

        ]

    ]);

    if (!$query->have_posts()) {
        return [];
    }

    $items = [];

    foreach ($query->posts as $post) {

        $items[] = octo_format_banner($post);

    }

    wp_reset_postdata();

    return $items;
}

/**
 * Get Banner Slider
 */
function octo_get_banner_slider($category)
{

    $banner = octo_get_banners($category);


    if (
        ($banner['layout'] ?? 'slider') !== 'slider'
    ) {
        return [];
    }


    return $banner;

}


/*
|--------------------------------------------------------------------------
| Get Banners By Category
|--------------------------------------------------------------------------
*/

function octo_get_banners($category)
{

    if (empty($category)) {
        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | Get Category
    |--------------------------------------------------------------------------
    */

    $term = get_term_by(
        'slug',
        $category,
        'banner_category'
    );


    if (!$term || is_wp_error($term)) {
        return [];
    }



    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */

    $layout = get_term_meta(
        $term->term_id,
        '_octo_banner_layout',
        true
    );


    /*
    |--------------------------------------------------------------------------
    | Backward Compatibility
    |--------------------------------------------------------------------------
    */

    if (empty($layout)) {
        $layout = 'slider';
    }



    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    $settings = [];


    if ($layout === 'slider') {


        $settings = get_term_meta(

            $term->term_id,

            '_octo_slider_settings',

            true

        );


        $settings = wp_parse_args(

            $settings,

            octo_banner_slider_default_settings()

        );


    }



    /*
    |--------------------------------------------------------------------------
    | Query Banners
    |--------------------------------------------------------------------------
    */


    $query = new WP_Query([

        'post_type' => 'banner',

        'posts_per_page' => -1,

        'orderby' => 'meta_value_num',

        'meta_key' => '_octo_priority',

        'order' => 'ASC',

        'tax_query' => [

            [

                'taxonomy' => 'banner_category',

                'field' => 'term_id',

                'terms' => $term->term_id

            ]

        ]

    ]);



    if (!$query->have_posts()) {
        return [];
    }



    $items = [];



    foreach ($query->posts as $post) {



        $desktop_image = get_the_post_thumbnail_url(

            $post->ID,

            'full'

        );



        $mobile_image = get_post_meta(

            $post->ID,

            '_octo_mobile_image',

            true

        );



        if ($mobile_image) {

            $mobile_image = wp_get_attachment_image_url(

                $mobile_image,

                'full'

            );

        }



        $items[] = [

            'id' => $post->ID,

            'title' => get_the_title($post->ID),


            'desktop_image' => $desktop_image,


            'mobile_image' => $mobile_image,


            'desktop_link' => get_post_meta(

                $post->ID,

                '_octo_desktop_link',

                true

            ),


            'mobile_link' => get_post_meta(

                $post->ID,

                '_octo_mobile_link',

                true

            ),


            'target' => get_post_meta(

                $post->ID,

                '_octo_new_tab',

                true

            )

            ? '_blank'

            : '_self',


            'width' => get_post_meta(

                $post->ID,

                '_octo_banner_width',

                true

            ) ?: 12,


        ];


    }



    wp_reset_postdata();



    return [

        'layout' => $layout,

        'settings' => $settings,

        'items' => $items

    ];


}