<?php

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Meta Box
 */
function octo_banner_meta_box()
{

    add_meta_box(

        'octo_banner_meta',

        'Banner Settings',

        'octo_banner_meta_callback',

        'banner',

        'normal',

        'high'

    );

}

add_action('add_meta_boxes', 'octo_banner_meta_box');


function octo_banner_meta_callback($post)
{

    wp_nonce_field('octo_banner_meta', 'octo_banner_nonce');

    ?>

    <table class="form-table">
        
        <tr>

            <th>Banner Width</th>
        
            <td>
        
                <select name="octo_banner_width">
        
                    <?php
        
                    $width = get_post_meta(
                        $post->ID,
                        '_octo_banner_width',
                        true
                    );
        
                    ?>
        
                    <option value="12" <?php selected($width, '12'); ?>>
                        Full Width
                    </option>
        
                    <option value="6" <?php selected($width, '6'); ?>>
                        Half
                    </option>
        
                    <option value="4" <?php selected($width, '4'); ?>>
                        Third
                    </option>
        
                    <option value="3" <?php selected($width, '3'); ?>>
                        Quarter
                    </option>
        
                </select>
        
            </td>
        
        </tr>

        <tr>

            <th>Desktop Link</th>

            <td>

                <input
                    type="url"
                    name="octo_desktop_link"
                    class="regular-text"
                    value="<?php echo esc_attr(get_post_meta($post->ID, '_octo_desktop_link', true)); ?>">

            </td>

        </tr>

        <tr>
        
            <th scope="row">
                <label for="octo_mobile_image">
                    Mobile Image
                </label>
            </th>
        
            <td>
        
                <?php
                $image = get_post_meta($post->ID, '_octo_mobile_image', true);
                $image_url = $image
                    ? wp_get_attachment_image_url($image, 'medium')
                    : '';
                ?>
        
                <input
                    type="hidden"
                    id="octo_mobile_image"
                    name="octo_mobile_image"
                    value="<?php echo esc_attr($image); ?>">
        
                <button
                    type="button"
                    id="octo_upload_mobile_image"
                    class="button button-primary">
        
                    Select Image
        
                </button>
        
                <button
                    type="button"
                    id="octo_remove_mobile_image"
                    class="button button-secondary">
        
                    Remove Image
        
                </button>
        
                <div style="margin-top:15px;">
        
                    <img
                        id="octo_mobile_image_preview"
                        src="<?php echo esc_url($image_url); ?>"
                        style="max-width:220px;height:auto;<?php echo $image ? '' : 'display:none;'; ?>">
        
                </div>
        
            </td>
        
        </tr>

        <tr>

            <th>Mobile Link</th>

            <td>

                <input
                    type="url"
                    name="octo_mobile_link"
                    class="regular-text"
                    value="<?php echo esc_attr(get_post_meta($post->ID, '_octo_mobile_link', true)); ?>">

            </td>

        </tr>

        <tr>

            <th>Priority</th>

            <td>

                <input
                    type="number"
                    name="octo_priority"
                    min="0"
                    step="1"
                    value="<?php echo esc_attr(get_post_meta($post->ID, '_octo_priority', true)); ?>">

            </td>

        </tr>

        <tr>

            <th>Open In New Tab</th>

            <td>

                <label>

                    <input
                        type="checkbox"
                        name="octo_new_tab"
                        value="1"
                        <?php checked(get_post_meta($post->ID, '_octo_new_tab', true), 1); ?>>

                    Enable

                </label>

            </td>

        </tr>

    </table>

    <?php
}


/**
 * Save Meta
 */
function octo_save_banner_meta($post_id)
{

    if (!isset($_POST['octo_banner_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['octo_banner_nonce'], 'octo_banner_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    update_post_meta(
        $post_id,
        '_octo_desktop_link',
        esc_url_raw($_POST['octo_desktop_link'] ?? '')
    );

    update_post_meta(
        $post_id,
        '_octo_mobile_link',
        esc_url_raw($_POST['octo_mobile_link'] ?? '')
    );

    update_post_meta(
        $post_id,
        '_octo_mobile_image',
        absint($_POST['octo_mobile_image'] ?? 0)
    );

    update_post_meta(
        $post_id,
        '_octo_priority',
        absint($_POST['octo_priority'] ?? 0)
    );

    update_post_meta(
        $post_id,
        '_octo_new_tab',
        isset($_POST['octo_new_tab']) ? 1 : 0
    );
    update_post_meta(
        $post_id,
        '_octo_banner_width',
        absint($_POST['octo_banner_width'] ?? 12)
    );

}

add_action('save_post_banner', 'octo_save_banner_meta');