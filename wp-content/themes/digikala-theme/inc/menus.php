<?php

/*
|--------------------------------------------------------------------------
| Add Mega Menu Slug Field To Menu Items
|--------------------------------------------------------------------------
*/

function octo_menu_custom_fields($item_id, $item)
{

    $mega_menu_slug = get_post_meta(
        $item_id,
        '_octo_mega_menu_slug',
        true
    );

    ?>

    <p class="description description-wide">

        <label>

            Mega Menu Block Slug

            <br>

            <input
                type="text"
                name="octo_mega_menu_slug[<?php echo $item_id; ?>]"
                value="<?php echo esc_attr($mega_menu_slug); ?>"
                class="widefat"
            >

        </label>

    </p>

    <?php
}

add_action(
    'wp_nav_menu_item_custom_fields',
    'octo_menu_custom_fields',
    10,
    2
);


/*
|--------------------------------------------------------------------------
| Save Mega Menu Slug
|--------------------------------------------------------------------------
*/

function octo_save_menu_custom_fields($menu_id, $menu_item_db_id)
{

    if (isset($_POST['octo_mega_menu_slug'][$menu_item_db_id])) {

        update_post_meta(

            $menu_item_db_id,

            '_octo_mega_menu_slug',

            sanitize_text_field(
                $_POST['octo_mega_menu_slug'][$menu_item_db_id]
            )

        );
    }
}

add_action(
    'wp_update_nav_menu_item',
    'octo_save_menu_custom_fields',
    10,
    2
);

require_once get_theme_file_path(
    '/inc/walkers/class-mega-menu-walker.php'
);