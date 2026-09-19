<?php

if (!defined('ABSPATH')) exit;

/**
 * Validate phone number (Iran format)
 */
function octo_validate_phone($phone)
{
    return preg_match('/^09\d{9}$/', $phone);
}

/**
 * Validate OTP (6 digits)
 */
function octo_validate_otp($otp)
{
    return preg_match('/^\d{6}$/', $otp);
}

/**
 * Validate password (basic rule)
 */
function octo_validate_password($password)
{
    $errors = [];

    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if (strlen($password) < OCTO_PASSWORD_MIN_LENGTH) {
        $errors[] = sprintf(
            'Password must be at least %d characters.',
            OCTO_PASSWORD_MIN_LENGTH
        );
    }

    if (
        OCTO_PASSWORD_REQUIRE_UPPERCASE &&
        !preg_match('/[A-Z]/', $password)
    ) {
        $errors[] = 'Password must contain at least one uppercase letter.';
    }

    if (
        OCTO_PASSWORD_REQUIRE_LOWERCASE &&
        !preg_match('/[a-z]/', $password)
    ) {
        $errors[] = 'Password must contain at least one lowercase letter.';
    }

    if (
        OCTO_PASSWORD_REQUIRE_NUMBER &&
        !preg_match('/\d/', $password)
    ) {
        $errors[] = 'Password must contain at least one number.';
    }

    if (
        OCTO_PASSWORD_REQUIRE_SPECIAL &&
        !preg_match('/[^a-zA-Z0-9]/', $password)
    ) {
        $errors[] = 'Password must contain at least one special character.';
    }

    return [
        'valid'  => empty($errors),
        'errors' => $errors
    ];
}