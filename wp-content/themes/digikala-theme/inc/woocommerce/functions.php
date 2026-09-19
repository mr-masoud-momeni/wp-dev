<?php

defined( 'ABSPATH' ) || exit;

add_action( 'woocommerce_product_query', 'octo_filter_archive_products' );

function octo_filter_archive_products( $query ) {

    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // فقط آرشیو محصولات و دسته‌بندی‌ها
    if ( ! is_post_type_archive( 'product' ) && ! is_product_taxonomy() ) {
        return;
    }

    if ( empty( $_GET['sale'] ) ) {
        return;
    }

    $query->set( 'post__in', wc_get_product_ids_on_sale() );
}