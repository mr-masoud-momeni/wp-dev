<?php

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;

if (!$product || !is_a($product, 'WC_Product')) {
    return;
}

$image_id      = $product->get_image_id();
$image         = wp_get_attachment_image($image_id, 'woocommerce_thumbnail', false, [
    'class' => 'img-fluid'
]);

$title         = $product->get_name();
$permalink     = $product->get_permalink();

$regular_price = (float) $product->get_regular_price();
$sale_price    = (float) $product->get_sale_price();

$discount = 0;

if ($regular_price > 0 && $sale_price > 0) {
    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
}
?>

<div class="product-card">

    <a href="<?= esc_url($permalink); ?>" class="product-card-link">

        <div class="product-card-image">

            <?= $image; ?>

        </div>

        <div class="product-card-body">

            <h3 class="product-card-title">

                <?= esc_html($title); ?>

            </h3>

            <div class="product-card-price">

                <?php if ($discount) : ?>

                    <span class="product-discount">

                        <?= $discount; ?>%

                    </span>

                <?php endif; ?>

                <div class="product-price-wrapper">

                    <span class="product-price">

                        <?= wc_price($product->get_price()); ?>

                    </span>

                    <?php if ($discount) : ?>

                        <del class="product-old-price">

                            <?= wc_price($regular_price); ?>

                        </del>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </a>

</div>