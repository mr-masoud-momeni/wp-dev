<?php
defined('ABSPATH') || exit;
?>

<div class="container-product">

    <?php woocommerce_breadcrumb(); ?>

    <div class="row g-4 product-top">

        <!-- Gallery -->
        <div class="col-lg-4">

            <?php
            get_template_part('template-parts/product/gallery/gallery');
            ?>

        </div>

        <!-- Summary -->
        <div class="col-lg-5">

            <div class="product-summary">

                <?php
                woocommerce_template_single_title();

                woocommerce_template_single_excerpt();
                ?>

            </div>

        </div>

        <!-- Buy Box -->
        <div class="col-lg-3">

            <div class="product-buy-box">

                <?php
                woocommerce_template_single_price();

                woocommerce_template_single_add_to_cart();
                ?>

            </div>

        </div>

    </div>

    <div class="product-sections">

        <?php get_template_part('template-parts/product/tabs/navigation'); ?>
    
        <?php get_template_part('template-parts/product/tabs/description'); ?>
    
        <?php get_template_part('template-parts/product/tabs/specifications');?>
    
        <?php get_template_part('template-parts/product/tabs/reviews'); ?>

    </div>

</div>