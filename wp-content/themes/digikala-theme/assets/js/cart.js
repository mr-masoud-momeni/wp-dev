jQuery(function ($) {

    $(document).off(
        'submit.octoCart',
        'form.cart'
    );

    $(document).on(
        'submit.octoCart',
        'form.cart',
        function (e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            const form = $(this);
            const button = form.find('.single_add_to_cart_button');

            if (button.prop('disabled')) {
                return false;
            }

            const productId = button.val();
            const quantity = form.find('[name="quantity"]').val() || 1;

            button.prop('disabled', true);

            $.ajax({

                type: 'POST',

                url: wc_add_to_cart_params.wc_ajax_url
                    .toString()
                    .replace(
                        '%%endpoint%%',
                        'add_to_cart'
                    ),

                data: {
                    product_id: productId,
                    quantity: quantity
                },

                success: function (response) {

                    if (response.error) {

                        console.log(
                            'Add to cart error:',
                            response
                        );

                        return;
                    }

                    /*
                     * WooCommerce خودش محصول را اضافه کرده.
                     * حالا event استانداردش را اجرا می‌کنیم.
                     */

                    $(document.body).trigger(
                        'added_to_cart',
                        [
                            response.fragments,
                            response.cart_hash,
                            button
                        ]
                    );

                    /*
                     * تعداد واقعی سبد را از سرور می‌گیریم.
                     */

                    $.post(
                        octoCart.ajaxUrl,
                        {
                            action: 'octo_get_cart_count'
                        },
                        function (cartResponse) {

                            if (!cartResponse.success) {
                                return;
                            }

                            $('#octo-cart-count').text(
                                cartResponse.data.cart_count
                            );

                            showOctoToast(
                                'محصول به سبد خرید اضافه شد.'
                            );

                        }
                    );

                },

                error: function (xhr) {

                    console.log(
                        'WooCommerce AJAX error:',
                        xhr
                    );

                },

                complete: function () {

                    button.prop(
                        'disabled',
                        false
                    );

                }

            });

            return false;
        }
    );


    function showOctoToast(message) {

        let toast = $('#octo-cart-toast');

        if (!toast.length) {

            toast = $(`
                <div
                    id="octo-cart-toast"
                    class="toast position-fixed bottom-0 end-0 m-4"
                    role="alert"
                    aria-live="assertive"
                    aria-atomic="true"
                    style="z-index:9999;"
                >
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="toast-message"></span>
                    </div>
                </div>
            `);

            $('body').append(toast);
        }

        toast.find('.toast-message').text(message);

        bootstrap.Toast
            .getOrCreateInstance(toast[0], {
                delay: 3000
            })
            .show();
    }

});