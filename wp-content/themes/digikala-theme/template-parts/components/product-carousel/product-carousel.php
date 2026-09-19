<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Component Arguments
|--------------------------------------------------------------------------
*/

$args = wp_parse_args($args, [

    'id'    => '',
    'class' => '',
    'data'  => []

]);

$data = wp_parse_args($args['data'], [

    'title'      => __('Products', 'octo'),

    'category'   => '',

    'limit'      => 18,

    'on_sale'    => false,

    'navigation' => true,

    'view_all'   => true,

    'variant'    => 'default',

    'color'      => '#ef394e',

]);

/*
|--------------------------------------------------------------------------
| Product Data
|--------------------------------------------------------------------------
*/

$products = octo_get_products($data);

$view_all_url = octo_get_products_url($data);

/*
|--------------------------------------------------------------------------
| Component Classes
|--------------------------------------------------------------------------
*/

$carousel_class = 'octo-product-carousel ' . $data['variant'];

if (!empty($args['class'])) {
    $carousel_class .= ' ' . $args['class'];
}

/*
|--------------------------------------------------------------------------
| Component Style
|--------------------------------------------------------------------------
*/

$carousel_style = '';

if ($data['variant'] === 'amazing' && !empty($data['color'])) {

    $carousel_style = '--carousel-color: ' . esc_attr($data['color']) . ';';

}

?>

<section
    id="<?= esc_attr($args['id']); ?>"
    class="<?= esc_attr($carousel_class); ?>"
    <?php if ($carousel_style) : ?>
        style="<?= esc_attr($carousel_style); ?>"
    <?php endif; ?>
>

    <?php if ($data['variant'] === 'amazing') : ?>

        <div class="carousel-banner">

            <div class="carousel-banner-content">

                <div class="carousel-icon">
                    ⚡
                </div>

                <h2>
                    <?= esc_html($data['title']); ?>
                </h2>

            </div>

        </div>

    <?php endif; ?>


    <?php if ($data['variant'] === 'default') : ?>

        <div class="carousel-header">

            <h2>
                <?= esc_html($data['title']); ?>
            </h2>

            <?php if ($data['view_all']) : ?>

                <a
                    href="<?= esc_url($view_all_url); ?>"
                    class="carousel-view-all"
                >
                    <?= esc_html__('View All', 'octo'); ?>
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>


    <div class="carousel-products-wrapper">

        <?php if ($data['navigation']) : ?>

            <button
                class="carousel-arrow prev"
                type="button"
                aria-label="<?= esc_attr__('Previous products', 'octo'); ?>"
            >
                ←
            </button>

        <?php endif; ?>


        <div class="carousel-products">

            <?php foreach ($products as $product) : ?>

                <?php

                get_template_part(
                    'template-parts/product/card/card',
                    null,
                    [
                        'product' => $product
                    ]
                );

                ?>

            <?php endforeach; ?>


            <?php if (
                $data['variant'] === 'amazing'
                && $data['view_all']
            ) : ?>

                <a
                    href="<?= esc_url($view_all_url); ?>"
                    class="view-all-card"
                >
                    <?= esc_html__('View All', 'octo'); ?>
                </a>

            <?php endif; ?>

        </div>


        <?php if ($data['navigation']) : ?>

            <button
                class="carousel-arrow next"
                type="button"
                aria-label="<?= esc_attr__('Next products', 'octo'); ?>"
            >
                →
            </button>

        <?php endif; ?>

    </div>

</section>