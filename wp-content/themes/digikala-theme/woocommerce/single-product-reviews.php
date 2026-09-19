<?php
defined('ABSPATH') || exit;

global $product;

if ( ! comments_open() ) {
    return;
}
?>

<section id="reviews" class="product-section">

    <div class="product-section-header">

        <h2><?php esc_html_e('Reviews', 'digikala-theme'); ?></h2>

        <span class="review-count">
            <?php
            printf(
                esc_html__('%s Reviews', 'digikala-theme'),
                $product->get_review_count()
            );
            ?>
        </span>

    </div>

    <?php if ( have_comments() ) : ?>

        <ol class="product-review-list">

            <?php
            wp_list_comments([
                'callback' => 'woocommerce_comments',
                'style'    => 'ol',
            ]);
            ?>

        </ol>

    <?php endif; ?>

    <div class="review-form-wrapper">

        <?php
        if ( is_user_logged_in() ) {

            comment_form();

        } else {
            ?>

            <div class="review-login-box">

                <p><?php esc_html_e('Please login to submit a review.', 'digikala-theme'); ?></p>

                <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="btn btn-danger">

                    <?php esc_html_e('Login', 'digikala-theme'); ?>

                </a>

            </div>

            <?php
        }
        ?>

    </div>

</section>