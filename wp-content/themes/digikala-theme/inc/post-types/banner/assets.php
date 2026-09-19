<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Banner Post Type Assets
|--------------------------------------------------------------------------
*/

function octo_banner_admin_assets($hook)
{
    global $post_type;


    if ($post_type !== 'banner') {
        return;
    }


    wp_enqueue_media();


    wp_enqueue_script(
        'octo-media-uploader',
        get_theme_file_uri('/assets/admin/js/media-uploader.js'),
        ['jquery'],
        '1.0.0',
        true
    );

}

add_action(
    'admin_enqueue_scripts',
    'octo_banner_admin_assets'
);



/*
|--------------------------------------------------------------------------
| Banner Category Assets
|--------------------------------------------------------------------------
*/

function octo_banner_category_admin_assets($hook)
{


    if (
        !in_array(
            $hook,
            [
                'edit-tags.php',
                'term.php'
            ],
            true
        )
    ) {
        return;
    }



    if (
        !isset($_GET['taxonomy'])
        ||
        $_GET['taxonomy'] !== 'banner_category'
    ) {
        return;
    }



    wp_enqueue_script(

        'octo-banner-category',
        get_theme_file_uri('/assets/admin/js/banner-category.js'),
        ['jquery'],
        '1.0.0',
        true

    );


}


add_action(
    'admin_enqueue_scripts',
    'octo_banner_category_admin_assets'
);