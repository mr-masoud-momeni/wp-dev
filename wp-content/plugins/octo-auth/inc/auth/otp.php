<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ارسال OTP
 */
function octo_send_otp()
{
    $token = sanitize_text_field($_POST['token'] ?? '');

    if (!$token) {
        wp_send_json_error([
            'message' => 'توکن معتبر نیست'
        ]);
    }

    // 1. گرفتن state
    $state = octo_get_auth_state($token);

    if (!$state) {
        wp_send_json_error([
            'message' => 'نشست منقضی شده است'
        ]);
    }
    
    if (!empty($state['verified'])) {

        wp_send_json_error([
            'message' => 'Authentication already completed.'
        ]);
    
    }

    $phone = $state['phone'];

    // 2. IP Block check
    if (octo_is_ip_blocked()) {
        wp_send_json_error([
            'message' => 'دسترسی شما موقتاً مسدود شده است'
        ]);
    }

    // 3. Rate limit (بر اساس phone)
    $rateKey = 'otp_phone_' . $phone;

    $limit = octo_is_rate_limited($rateKey, OCTO_RATE_LIMIT_MAX_REQUESTS);

    if ($limit['limited']) {
        
        octo_block_ip(OCTO_IP_BLOCK_TTL);
        
        wp_send_json_error([
            'message' => 'تعداد درخواست بیش از حد مجاز است',
        ]);
    }

    // افزایش rate
    octo_increment_rate_limit($rateKey, OCTO_RATE_LIMIT_WINDOW);

    //4. تولید کد
    $code = random_int(100000, 999999);

    //5. ذخیره کد
    set_transient(
        'octo_otp_' . md5($phone),
        [
            'code'      => $code,
            'attempts'  => 0,
            'created_at'=> time()
        ],
        OCTO_OTP_TTL // 2 minutes
    );

    // 6. ارسال SMS
    try {

        octo_send_sms_ir($phone, $code);
    
    } catch (Throwable $e) {
    
        error_log('[Octo Auth] ' . $e->getMessage());
    
        wp_send_json_error([
            'message' => 'ارسال پیامک با خطا مواجه شد.'
        ]);
    }

    // 7. پاسخ به فرانت
    wp_send_json_success([
        'message' => 'کد ارسال شد',
        'expire'  => OCTO_OTP_TTL
    ]);
    
}

/**
 * بررسی کد تایید
 */
function octo_verify_otp()
{
    // 1. دریافت اطلاعات
    $token = sanitize_text_field($_POST['token'] ?? '');
    $code  = sanitize_text_field($_POST['code'] ?? '');

    if (!$token || !$code) {

        wp_send_json_error([
            'message' => 'Invalid request.'
        ]);

    }

    // 2. بررسی توکن
    $state = octo_get_auth_state($token);

    if (!$state) {

        wp_send_json_error([
            'message' => 'Invalid token.'
        ]);

    }

    // 3. بررسی OTP
    $savedCode = get_transient(
        'octo_otp_' . md5($state['phone'])
    );

    if (!$savedCode) {

        wp_send_json_error([
            'message' => 'Verification code has expired.'
        ]);

    }

    if ($savedCode['code'] != $code) {

        wp_send_json_error([
            'message' => 'Verification code is incorrect.'
        ]);

    }
    
    //4. submit true in verified parameter
    octo_verify_auth_state($token);
    
    
    // 5. حذف OTP
    delete_transient(
        'octo_otp_' . md5($state['phone'])
    );

    // 6. موفقیت
    wp_send_json_success([
        'message' => 'OTP verified successfully.',
        'step'   => $state['flow']
    ]);
}