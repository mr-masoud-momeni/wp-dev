<?php

if (!defined('ABSPATH')) {
    exit;
}

function octo_check_phone()
{

    
    $phone = sanitize_text_field($_POST['phone'] ?? '');

    // Validate
    if (!octo_validate_phone($phone)) {

        wp_send_json_error([
            'message' => 'شماره موبایل معتبر نیست.'
        ]);
    }

    // Find User
    $result = octo_find_user_by_phone($phone);
    
/*    echo '<pre>';
var_dump($user);
echo '</pre>';
exit; */

    // تصمیم‌گیری
    if ($result) {

        $token = octo_set_auth_state($phone, 'login');

        wp_send_json_success([
            'step'  => 'password',
            'token' => $token
        ]);
    }

    $token = octo_set_auth_state($phone, 'register');

    wp_send_json_success([
        'step'  => 'otp',
        'token' => $token
    ]);
}