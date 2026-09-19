<?php
defined('ABSPATH') || exit;

global $product;

$image_id = $product->get_image_id();

if ($image_id) {

    echo wp_get_attachment_image(
        $image_id,
        'woocommerce_single',
        false,
        [
            'class' => 'product-main-image'
        ]
    );

} else {

    echo wc_placeholder_img(
        'woocommerce_single',
        [
            'class' => 'product-main-image'
        ]
    );

}