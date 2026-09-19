<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_nopriv_octo_check_phone', 'octo_check_phone');
add_action('wp_ajax_nopriv_octo_send_otp', 'octo_send_otp');
add_action('wp_ajax_nopriv_octo_verify_otp', 'octo_verify_otp');
add_action('wp_ajax_nopriv_octo_login', 'octo_login');
add_action('wp_ajax_nopriv_octo_login_with_otp', 'octo_login_with_otp');
add_action('wp_ajax_nopriv_octo_register', 'octo_register');
add_action('wp_ajax_nopriv_octo_forgot_password', 'octo_forgot_password');
add_action('wp_ajax_nopriv_octo_reset_password', 'octo_reset_password');

add_action('wp_ajax_octo_logout', 'octo_logout');