<?php if (is_user_logged_in()) : ?>

    <?php comments_template(); ?>

<?php else : ?>

    <div class="review-login-box">

        <h3><?php esc_html_e('Login Required', 'digikala-theme'); ?></h3>

        <p><?php esc_html_e('Please login to submit your review.', 'digikala-theme'); ?></p>

        <a class="btn btn-danger" href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">

            <?php esc_html_e('Login', 'digikala-theme'); ?>

        </a>

    </div>

<?php endif; ?>