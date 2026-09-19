<?php

defined('ABSPATH') || exit;

/**
 * Get WooCommerce products.
 *
 * @param array $args
 * @return array
 */
function octo_get_products(array $args = []): array
{
    $defaults = [

        'category'   => '',

        'limit'      => 18,

        'status'     => 'publish',

        'on_sale'    => false,

        'featured'   => false,

        'orderby'    => 'date',

        'order'      => 'DESC',

    ];

    $args = wp_parse_args($args, $defaults);

    $query = [

        'status'  => $args['status'],

        'limit'   => $args['limit'],

        'orderby' => $args['orderby'],

        'order'   => $args['order'],

    ];

    /**
     * Category
     */
    if (!empty($args['category'])) {

        $query['category'] = (array) $args['category'];

    }

    /**
     * Featured products
     */
    if ($args['featured']) {

        $query['featured'] = true;

    }

    /**
     * Sale products
     */
    if ($args['on_sale']) {

        $sale_ids = wc_get_product_ids_on_sale();

        if (empty($sale_ids)) {
            return [];
        }

        $query['include'] = $sale_ids;

    }

    return wc_get_products($query);
}


/**
 * Build product archive url based on query.
 */
function octo_get_products_url(array $args = []): string
{
    $defaults = [
        'category' => '',
        'on_sale'  => false,
    ];

    $args = wp_parse_args($args, $defaults);

    // دسته‌بندی
    if (!empty($args['category'])) {

        $link = get_term_link($args['category'], 'product_cat');

        if (is_wp_error($link)) {
            return '#';
        }

        // اگر فقط دسته است
        if (!$args['on_sale']) {
            return $link;
        }

        // اگر دسته + تخفیف است
        return add_query_arg('sale', '1', $link);
    }

    // همه محصولات تخفیف‌دار
    if ($args['on_sale']) {
        return add_query_arg(
            'sale',
            '1',
            get_post_type_archive_link('product')
        );
    }

    return get_post_type_archive_link('product');
}