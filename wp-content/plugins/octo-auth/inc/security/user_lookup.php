<?php

if (!defined('ABSPATH')) exit;

/**
 * Find user by phone number
 */
function octo_find_user_by_phone($phone)
{
    $phone = trim($phone);

    // 1. lookup via meta (primary)
    foreach (OCTO_PHONE_META_KEYS as $metaKey) {

        $users = get_users([
            'meta_key'   => $metaKey,
            'meta_value' => $phone,
            'number'     => 1,
            'fields'     => 'all'
        ]);

        if (!empty($users)) {
            return [
                'user'     => $users[0],
                'meta_key' => $metaKey
            ];
        }
    }

    // 2. fallback: check user_login
    $user = get_user_by('login', $phone);
    if ($user) {
        return [
            'user'     => $user,
            'meta_key' => 'user_login'
        ];
    }

    return false;
}
