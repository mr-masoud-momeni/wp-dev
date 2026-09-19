<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * فراموشی رمز
 */
function octo_forgot_password()
{
    // دریافت Token فعلی
    $token = sanitize_text_field(
        $_POST['token'] ?? ''
    );

    if (!$token) {

        wp_send_json_error([
            'message' => 'Invalid request.'
        ]);

    }

    // بررسی State
    $state = octo_get_auth_state($token);

    if (!$state) {

        wp_send_json_error([
            'message' => 'Authentication session expired.'
        ]);

    }

    // ساخت State جدید
    $newToken = octo_set_auth_state(
        $state['phone'],
        'reset_password'
    );

    // حذف State قبلی
    octo_clear_auth_state($token);

    wp_send_json_success([

        'token' => $newToken

    ]);

}

/**
 * تنظیم رمز جدید
 */
function octo_reset_password()
{
    // 1. دریافت اطلاعات
    $token = sanitize_text_field($_POST['token'] ?? '');

    $password = $_POST['password'] ?? '';

    $confirm = $_POST['password_confirm'] ?? '';

    if (!$token || !$password || !$confirm) {

        wp_send_json_error([
            'message' => 'Invalid request.'
        ]);

    }

    // 2. بررسی نشست
    $state = octo_get_auth_state($token);

    if (!$state) {

        wp_send_json_error([
            'message' => 'Authentication session expired.'
        ]);

    }

    // 3. بررسی Verify
    if (empty($state['verified'])) {

        wp_send_json_error([
            'message' => 'OTP verification required.'
        ]);

    }
    
    // 4. بررسی رمزها
    $validation = octo_validate_password($password);
    
    if (!$validation['valid']) {
    
        wp_send_json_error([
            'errors' => $validation['errors']
        ]);
    
    }

    if ($password !== $confirm) {

        wp_send_json_error([
            'message' => 'Passwords do not match.'
        ]);

    }

    // 5. پیدا کردن کاربر
    $result = octo_find_user_by_phone(
        $state['phone']
    );

    if (!$result) {

        wp_send_json_error([
            'message' => 'User not found.'
        ]);

    }

    $user = $result['user'];

    // 6. تغییر رمز
    wp_set_password(
        $password,
        $user->ID
    );

    // 7. ورود کاربر
    wp_set_current_user($user->ID);

    wp_set_auth_cookie($user->ID);

    // 8. حذف State
    octo_clear_auth_state($token);

    // 9. پاسخ
    wp_send_json_success([

        'redirect' => octo_get_redirect_url()

    ]);

}