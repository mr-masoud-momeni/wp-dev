<?php
defined('ABSPATH') || exit;
?>

<div class="archive-toolbar mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <h1 class="h4 mb-1">
                <?php woocommerce_page_title(); ?>
            </h1>

            <small class="text-muted">

                <?php echo esc_html($GLOBALS['wp_query']->found_posts); ?>

                <h2><?php esc_html_e('Product', 'octoshop'); ?></h2>

            </small>

        </div>

        <div>

            <?php woocommerce_catalog_ordering(); ?>

        </div>

    </div>

</div>