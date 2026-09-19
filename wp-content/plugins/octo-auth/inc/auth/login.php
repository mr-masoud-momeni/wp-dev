<?php

if (!defined('ABSPATH')) {
    exit;
}

function octo_login()
{
    // 1. دریافت اطلاعات
    $token    = sanitize_text_field($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$token || !$password) {

        wp_send_json_error([
            'message' => 'Invalid request.'
        ]);

    }

    // 2. بررسی نشست
    $state = octo_get_auth_state($token);

    if (!$state) {

        wp_send_json_error([
            'message' => 'Authentication session has expired.'
        ]);

    }
    
//    $rateKey = 'login_' . $state['phone'];
    
//    octo_clear_rate_limit($rateKey);

    // 3. بررسی IP Block
    if (octo_is_ip_blocked()) {

        wp_send_json_error([
            'message' => 'Your access has been temporarily blocked.'
        ]);

    }

    // 4. بررسی Rate Limit
    $rateKey = 'login_' . $state['phone'];


    $limit = octo_is_rate_limited(
        $rateKey,
        OCTO_LOGIN_MAX_ATTEMPTS
    );

    if ($limit['limited']) {

        octo_block_ip(OCTO_IP_BLOCK_TTL);

        wp_send_json_error([
            'message'     => 'Too many failed login attempts.'
        ]);

    }

    // 5. پیدا کردن کاربر
    $result = octo_find_user_by_phone($state['phone']);

    if (!$result) {

        wp_send_json_error([
            'message' => 'User not found.'
        ]);

    }

    $user = $result['user'];

    // 6. بررسی رمز عبور
    if (!wp_check_password(
        $password,
        $user->user_pass,
        $user->ID
    )) {
        

        $data=octo_increment_rate_limit(
            $rateKey,
            OCTO_LOGIN_RATE_WINDOW
        );

        wp_send_json_error([
            'message' => 'Password is incorrect.'
        ]);

    }

    // 7. حذف Rate Limit بعد از ورود موفق
    octo_clear_rate_limit($rateKey);

    // 8. اگر شماره از متای دیگری پیدا شده بود، به متای افزونه منتقل کن
    if ($result['meta_key'] !== 'octo_auth_phone') {

        update_user_meta(
            $user->ID,
            'octo_auth_phone',
            $state['phone']
        );

    }

    // 9. ورود کاربر
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);

    // 10. حذف نشست احراز هویت
    octo_clear_auth_state($token);

    // 11. پاسخ
    wp_send_json_success([
        'message'  => 'Login successful.',
        'redirect' => octo_get_redirect_url()
    ]);
}


function octo_login_with_otp()
{
    // 1. دریافت Token
    $token = sanitize_text_field($_POST['token'] ?? '');

    if (!$token) {

        wp_send_json_error([
            'message' => 'Invalid request.'
        ]);

    }

    // 2. بررسی State
    $state = octo_get_auth_state($token);

    if (!$state) {

        wp_send_json_error([
            'message' => 'Authentication session has expired.'
        ]);

    }

    // 3. آیا OTP تأیید شده؟
    if (empty($state['verified'])) {

        wp_send_json_error([
            'message' => 'OTP verification required.'
        ]);

    }

    // 4. پیدا کردن کاربر
    $result = octo_find_user_by_phone($state['phone']);

    if (!$result) {

        wp_send_json_error([
            'message' => 'User not found.'
        ]);

    }

    $user = $result['user'];

    // 5. اگر شماره از متای دیگری پیدا شده بود، ذخیره کن
    if ($result['meta_key'] !== 'octo_auth_phone') {

        update_user_meta(
            $user->ID,
            'octo_auth_phone',
            $state['phone']
        );

    }

    // 6. ورود کاربر
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);

    // 7. حذف State
    octo_clear_auth_state($token);

    // 8. پاسخ
    wp_send_json_success([
        'message'  => 'Login successful.',
        'redirect' => octo_get_redirect_url()
    ]);
}

function octo_get_redirect_url()
{
    if (!empty(OCTO_AUTH_REDIRECT)) {
        return OCTO_AUTH_REDIRECT;
    }

    return home_url();
}