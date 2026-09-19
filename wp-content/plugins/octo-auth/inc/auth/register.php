<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ثبت نام کاربر
 */
function octo_register()
{
    $token    = sanitize_text_field($_POST['token'] ?? '');
    $name     = sanitize_text_field($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['password_confirmation'] ?? '';

    // اعتبارسنجی توکن
    $state = octo_get_auth_state($token);

    if (!$state) {
        wp_send_json_error([
            'message' => 'Authentication session has expired.'
        ]);
    }

    // OTP باید قبلاً تأیید شده باشد
    if (empty($state['verified'])) {
        wp_send_json_error([
            'message' => 'Please verify your mobile number first.'
        ]);
    }

    // اعتبارسنجی نام
    if (strlen($name) < 3) {
        wp_send_json_error([
            'message' => 'Please enter your full name.'
        ]);
    }

    // اعتبارسنجی رمز
    if (strlen($password) < 8) {
        wp_send_json_error([
            'message' => 'Password must be at least 8 characters.'
        ]);
    }

    if ($password !== $confirm) {
        wp_send_json_error([
            'message' => 'Passwords do not match.'
        ]);
    }

    // ساخت کاربر
    $userId = wp_insert_user([
        'user_login'   => $state['phone'],
        'user_pass'    => $password,
        'display_name' => $name,
        'nickname'     => $name,
        'role'         => 'subscriber'
    ]);
    
    if (is_wp_error($userId)) {
    
        wp_send_json_error([
            'message' => $userId->get_error_message()
        ]);
    
    }
    
    // ذخیره شماره موبایل
    update_user_meta(
        $userId,
        'octo_auth_phone',
        $state['phone']
    );
    
    // ورود کاربر
    wp_set_current_user($userId);
    wp_set_auth_cookie($userId);
    
    // حذف نشست احراز هویت
    octo_clear_auth_state($token);
    
    // پاسخ
    wp_send_json_success([
        'message'  => 'Account created successfully.',
        'redirect' => home_url()
    ]);
}