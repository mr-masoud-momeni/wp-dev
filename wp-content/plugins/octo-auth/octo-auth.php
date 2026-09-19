<?php 
/** * Plugin Name: Octo Auth * Description: Mobile Authentication System For WordPress * Version: 1.0.0 * Author: Octo */ 
if (!defined('ABSPATH')) { exit; } 
/* 
|-------------------------------------------------------------------------- 
| Constants 
|-------------------------------------------------------------------------- 
*/ 
define('OCTO_AUTH_PATH', plugin_dir_path(__FILE__)); define('OCTO_AUTH_URL', plugin_dir_url(__FILE__)); 

/**
 * OTP
 */
define('OCTO_OTP_TTL', 120);

/**
 * Authentication session
 */
define('OCTO_AUTH_STATE_TTL', OCTO_OTP_TTL * 5);

/**
 * Rate limit
 */
define('OCTO_RATE_LIMIT_WINDOW', 60);
define('OCTO_RATE_LIMIT_MAX_REQUESTS', 3);

define('OCTO_LOGIN_RATE_WINDOW', 300);      // 5 min
define('OCTO_LOGIN_MAX_ATTEMPTS', 5);

/**
 * IP block
 */
define('OCTO_IP_BLOCK_TTL', 30);

/**
 * Search mobile field
 */
define('OCTO_PHONE_META_KEYS', [
    'octo_auth_phone',
    'billing_phone',
    'phone',
    'mobile'
]);

// redirect path after login
define(
    'OCTO_AUTH_REDIRECT',
    home_url('/my-account/')
);

//rules for submit password
define('OCTO_PASSWORD_MIN_LENGTH', 8);
define('OCTO_PASSWORD_REQUIRE_UPPERCASE', true);
define('OCTO_PASSWORD_REQUIRE_LOWERCASE', true);
define('OCTO_PASSWORD_REQUIRE_NUMBER', true);
define('OCTO_PASSWORD_REQUIRE_SPECIAL', true);

/* 
|-------------------------------------------------------------------------- | Includes 
|-------------------------------------------------------------------------- 
*/
require_once OCTO_AUTH_PATH . 'inc/security/validation.php';
require_once OCTO_AUTH_PATH . 'inc/security/user_lookup.php';
require_once OCTO_AUTH_PATH . 'inc/security/ip_guard.php';
require_once OCTO_AUTH_PATH . 'inc/security/rate_limit.php';
require_once OCTO_AUTH_PATH . 'inc/security/otp_guard.php';
require_once OCTO_AUTH_PATH . 'inc/security/auth_state.php';

require_once OCTO_AUTH_PATH . 'inc/sms/sms-ir.php';

require_once OCTO_AUTH_PATH . 'inc/auth/phone.php';
require_once OCTO_AUTH_PATH . 'inc/auth/otp.php';
require_once OCTO_AUTH_PATH . 'inc/auth/password.php';
require_once OCTO_AUTH_PATH . 'inc/auth/login.php';
require_once OCTO_AUTH_PATH . 'inc/auth/logout.php';
require_once OCTO_AUTH_PATH . 'inc/auth/register.php';

require_once OCTO_AUTH_PATH . 'inc/ajax/ajax.php';
require_once OCTO_AUTH_PATH . 'inc/admin/settings-page.php';



