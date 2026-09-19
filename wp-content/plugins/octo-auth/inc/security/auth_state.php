<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ذخیره وضعیت احراز هویت
 */
function octo_set_auth_state($phone, $flow)
{
    $token = wp_generate_password(32, false);

    set_transient(
        'octo_auth_' . $token,
        [
            'phone' => $phone,
            'flow'  => $flow,
            'time'  => time(),
            'verified' => false
        ],
        OCTO_AUTH_STATE_TTL // 5 minutes
    );

    return $token;
}

/**
 * دریافت وضعیت احراز هویت
 */
function octo_get_auth_state($token)
{
    return get_transient('octo_auth_' . $token);
}

/**
 * حذف وضعیت احراز هویت
 */
function octo_clear_auth_state($token)
{
    delete_transient('octo_auth_' . $token);
}

//submit true 
function octo_verify_auth_state($token)
{
    $state = octo_get_auth_state($token);

    if (!$state) {
        return false;
    }

    $state['verified'] = true;

    set_transient(
        'octo_auth_' . $token,
        $state,
        OCTO_AUTH_STATE_TTL
    );

    return true;
}