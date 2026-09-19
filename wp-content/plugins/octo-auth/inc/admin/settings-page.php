<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Add Settings Page
|--------------------------------------------------------------------------
*/

function octo_auth_add_settings_page()
{

    add_options_page(

        'Octo Auth',

        'Octo Auth',

        'manage_options',

        'octo-auth',

        'octo_auth_render_settings_page'

    );
}

add_action('admin_menu', 'octo_auth_add_settings_page');


/*
|--------------------------------------------------------------------------
| Register Settings
|--------------------------------------------------------------------------
*/

function octo_auth_register_settings()
{

    register_setting(
        'octo_auth_settings_group',
        'octo_auth_sms_api_key'
    );

    register_setting(
        'octo_auth_settings_group',
        'octo_auth_sms_template_id'
    );

    register_setting(
        'octo_auth_settings_group',
        'octo_auth_sms_line_number'
    );
}

add_action('admin_init', 'octo_auth_register_settings');


/*
|--------------------------------------------------------------------------
| Render Settings Page
|--------------------------------------------------------------------------
*/

function octo_auth_render_settings_page()
{
    ?>

    <div class="wrap">

        <h1>Octo Auth Settings</h1>

        <form method="post" action="options.php">

            <?php
                settings_fields('octo_auth_settings_group');
            ?>

            <table class="form-table">

                <!-- API KEY -->
                <tr>

                    <th scope="row">
                        SMS.ir API Key
                    </th>

                    <td>

                        <input
                            type="password"
                            name="octo_auth_sms_api_key"
                            value="<?php echo esc_attr(get_option('octo_auth_sms_api_key')); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>


                <!-- TEMPLATE ID -->
                <tr>

                    <th scope="row">
                        Template ID
                    </th>

                    <td>

                        <input
                            type="text"
                            name="octo_auth_sms_template_id"
                            value="<?php echo esc_attr(get_option('octo_auth_sms_template_id')); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>


                <!-- LINE NUMBER -->
                <tr>

                    <th scope="row">
                        Line Number
                    </th>

                    <td>

                        <input
                            type="text"
                            name="octo_auth_sms_line_number"
                            value="<?php echo esc_attr(get_option('octo_auth_sms_line_number')); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>

            </table>

            <?php submit_button(); ?>

        </form>

    </div>

    <?php
}
