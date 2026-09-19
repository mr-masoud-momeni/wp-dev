<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * دریافت IP کاربر
 */
function octo_get_ip()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return sanitize_text_field($_SERVER['HTTP_CF_CONNECTING_IP']);
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

        return trim($ips[0]);
    }

    return sanitize_text_field($_SERVER['REMOTE_ADDR']);
}

/**
 * بررسی بلاک بودن IP
 */
function octo_is_ip_blocked()
{
    $ip = octo_get_ip();

    return (bool) get_transient('octo_block_ip_' . md5($ip));
}

/**
 * بلاک کردن IP
 */
function octo_block_ip($minutes = 30)
{
    $ip = octo_get_ip();

    set_transient(
        'octo_block_ip_' . md5($ip),
        true,
        $minutes * MINUTE_IN_SECONDS
    );
}