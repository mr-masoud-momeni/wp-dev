<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * بررسی محدودیت تعداد درخواست‌ها
 */
function octo_is_rate_limited($key, $limit)
{
    $transient = 'octo_rate_' . md5($key);

    $data = get_transient($transient);

    if (!$data) {
        return [
            'limited' => false,
        ];
    }

    if ($data['count'] < $limit) {
        return [
            'limited' => false,
        ];
    }

    return [
        'limited' => true,
    ];
}

/**
 * افزایش شمارنده درخواست‌ها
 */
function octo_increment_rate_limit($key, $window = 60)
{
    $transient = 'octo_rate_' . md5($key);
    
    $data = get_transient($transient);
    

    if (!$data) {

        $data = [
            'count'      => 1,
            'expires_at' => time() + $window
        ];

    } else {

        $data['count']++;
    }

    $ttl = max(1, $data['expires_at'] - time());

    set_transient($transient, $data, $ttl);

    return $data;
}

/**
 * حذف محدودیت
 */
function octo_clear_rate_limit($key)
{
    $transient = 'octo_rate_' . md5($key);

    delete_transient($transient);
}