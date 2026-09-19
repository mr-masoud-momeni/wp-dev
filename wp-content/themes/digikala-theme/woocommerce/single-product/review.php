<?php
defined('ABSPATH') || exit;

global $comment;

if ('0' === $comment->comment_approved) : ?>

    <div class="review-awaiting">
        <?php esc_html_e('Your review is awaiting approval.', 'digikala-theme'); ?>
    </div>

<?php return; endif; ?>

<li <?php comment_class('product-review-item'); ?> id="comment-<?php comment_ID(); ?>">

    <div class="product-review-card">

        <div class="review-header">

            <div class="review-author">

                <?php echo get_avatar($comment, 48); ?>

                <div>

                    <h5><?php comment_author(); ?></h5>

                    <span class="review-date">
                        <?php echo esc_html(get_comment_date()); ?>
                    </span>

                </div>

            </div>

            <div class="review-rating">

                <?php
                $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));

                if ($rating) {
                    echo wc_get_rating_html($rating);
                }
                ?>

            </div>

        </div>

        <div class="review-content">

            <?php comment_text(); ?>

        </div>

    </div>

</li>