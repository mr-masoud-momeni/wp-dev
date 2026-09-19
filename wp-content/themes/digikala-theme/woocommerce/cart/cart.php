<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="octo-cart">

    <div class="octo-cart-main">

        <div class="octo-cart-header">
            <h1>سبد خرید</h1>
            <span>
                <?php echo WC()->cart->get_cart_contents_count(); ?> کالا
            </span>
        </div>

        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="octo-cart-items">

                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>

                    <?php
                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :

                        $product_permalink = apply_filters(
                            'woocommerce_cart_item_permalink',
                            $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                            $cart_item,
                            $cart_item_key
                        );
                    ?>

                        <div class="octo-cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'woocommerce-cart-form__cart-item', $cart_item, $cart_item_key)); ?>">

                            <div class="octo-cart-product">

                                <div class="octo-cart-product-image">

                                    <?php
                                    $thumbnail = apply_filters(
                                        'woocommerce_cart_item_thumbnail',
                                        $_product->get_image(),
                                        $cart_item,
                                        $cart_item_key
                                    );

                                    if (!$product_permalink) {
                                        echo $thumbnail;
                                    } else {
                                        printf(
                                            '<a href="%s">%s</a>',
                                            esc_url($product_permalink),
                                            $thumbnail
                                        );
                                    }
                                    ?>

                                </div>

                                <div class="octo-cart-product-info">

                                    <div class="octo-cart-product-title">

                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post($_product->get_name());
                                        } else {
                                            echo wp_kses_post(
                                                sprintf(
                                                    '<a href="%s">%s</a>',
                                                    esc_url($product_permalink),
                                                    $_product->get_name()
                                                )
                                            );
                                        }
                                        ?>

                                    </div>

                                    <?php
                                    echo wc_get_formatted_cart_item_data($cart_item);
                                    ?>

                                    <div class="octo-cart-product-price">
                                        <?php
                                        echo WC()->cart->get_product_price($_product);
                                        ?>
                                    </div>

                                </div>

                            </div>

                            <div class="octo-cart-item-actions">

                                <div class="octo-cart-quantity">

                                    <?php
                                    if ($_product->is_sold_individually()) {

                                        $min_quantity = 1;
                                        $max_quantity = 1;

                                    } else {

                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();

                                    }

                                    echo woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );
                                    ?>

                                </div>

                                <div class="octo-cart-item-total">

                                    <?php
                                    echo WC()->cart->get_product_subtotal(
                                        $_product,
                                        $cart_item['quantity']
                                    );
                                    ?>

                                </div>

                                <div class="octo-cart-remove">

                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
                                            esc_url(
                                                wc_get_cart_remove_url($cart_item_key)
                                            ),
                                            esc_attr(
                                                sprintf(
                                                    'حذف %s از سبد خرید',
                                                    wp_strip_all_tags($_product->get_name())
                                                )
                                            ),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                        ),
                                        $cart_item_key
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

            <?php do_action('woocommerce_cart_contents'); ?>

            <div class="octo-cart-update">

                <button type="submit" name="update_cart" class="button" value="به‌روزرسانی سبد">
                    به‌روزرسانی سبد
                </button>

                <?php do_action('woocommerce_cart_actions'); ?>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>

            </div>

            <?php do_action('woocommerce_after_cart_table'); ?>

        </form>

    </div>


    <div class="octo-cart-sidebar">

        <?php do_action('woocommerce_before_cart_collaterals'); ?>

        <div class="octo-cart-summary">

            <h2>خلاصه سفارش</h2>

            <div class="octo-cart-summary-row">
                <span>قیمت کالاها</span>
                <span>
                    <?php echo WC()->cart->get_cart_subtotal(); ?>
                </span>
            </div>

            <?php if (WC()->cart->get_discount_total() > 0) : ?>

                <div class="octo-cart-summary-row discount">
                    <span>تخفیف</span>
                    <span>
                        <?php
                        echo wc_price(WC()->cart->get_discount_total());
                        ?>
                    </span>
                </div>

            <?php endif; ?>

            <div class="octo-cart-summary-total">

                <span>مبلغ قابل پرداخت</span>

                <strong>
                    <?php echo WC()->cart->get_total(); ?>
                </strong>

            </div>

            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="octo-checkout-button">
                ادامه فرایند خرید
            </a>

            <div class="octo-cart-notice">
                هزینه ارسال در مرحله بعد محاسبه می‌شود.
            </div>

        </div>

        <?php do_action('woocommerce_after_cart'); ?>

    </div>

</div>