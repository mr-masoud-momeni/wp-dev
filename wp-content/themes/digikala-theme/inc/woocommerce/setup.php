<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function myshop_woocommerce_setup() {

    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-zoom' );

    // حذف wrapper پیشفرض
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    // اضافه کردن wrapper بوت‌استرپ
    add_action( 'woocommerce_before_main_content', 'myshop_wc_wrapper_start', 10 );
    add_action( 'woocommerce_after_main_content', 'myshop_wc_wrapper_end', 10 );
}

function myshop_wc_wrapper_start() {
    echo '<div class="container my-5"><div class="row"><div class="col-12">';
}

function myshop_wc_wrapper_end() {
    echo '</div></div></div>';
}

add_action( 'after_setup_theme', 'myshop_woocommerce_setup' );
