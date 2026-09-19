<?php

/**
 * Get current cart count via AJAX
 */
add_action(
    'wp_ajax_octo_get_cart_count',
    'octo_get_cart_count'
);

add_action(
    'wp_ajax_nopriv_octo_get_cart_count',
    'octo_get_cart_count'
);

function octo_get_cart_count()
{
    wp_send_json_success([
        'cart_count' => WC()->cart->get_cart_contents_count(),
    ]);
}