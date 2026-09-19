<?php

get_header();



get_template_part(
    'template-parts/components/banner-slider',
    null,
    [
        'id'    => 'home-slider',

        'class' => 'mb-4 rounded-4',

        'data' => [

            'category' => 'hero-main',

            'navigation' => true,

            'pagination' => true,

        ]

    ]
);



get_template_part(
    'template-parts/components/product-carousel/product-carousel',
    null,
    [
        'id'    => 'amazing-products',

        'class' => 'mb-4',

        'data'  => [

            'title'      => __('Amazing Deals', 'octo'),

            'category'   => '',

            'limit'      => 20,

            'on_sale'    => true,

            'navigation' => true,

            'view_all'   => true,

            'variant'    => 'amazing',

            'color'      => '#ef394e',

        ]

    ]
);

get_template_part(
    'template-parts/components/product-carousel/product-carousel',
    null,
    [
        'id'    => 'amazing-products',

        'class' => 'mb-4',

        'data'  => [

            'title'      => __('Amazing Deals', 'octo'),

            'category'   => 'Music',

            'limit'      => 20,

            'on_sale'    => true,

            'navigation' => true,

            'view_all'   => true,

            'variant'    => 'amazing',

            'color'      => '#ef394e',

        ]

    ]
);

get_template_part(
    'template-parts/components/banner-grid',
    null,
    [
        'id'    => 'grid-index',

        'class' => 'mb-4 rounded-4',

        'data' => [

            'category' => 'grid-index',

        ]

    ]
);
get_template_part(
    'template-parts/components/product-carousel/product-carousel',
    null,
    [
        'id'    => 'music-products',

        'class' => 'mb-4',

        'data'  => [

            'title'      => __('Music', 'octo'),

            'category'   => 'Music',

            'limit'      => 20,

            'on_sale'    => false,

            'navigation' => true,

            'view_all'   => true,

            'variant'    => 'default',

        ]

    ]
);

get_footer();