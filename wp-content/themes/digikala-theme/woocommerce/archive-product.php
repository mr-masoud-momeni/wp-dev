<?php
defined('ABSPATH') || exit;

get_header('shop');
?>

<div class="container-fluid archive-page py-4">

    <?php if (woocommerce_product_loop()) : ?>

        <div class="row g-4">

            <!-- Sidebar -->
            <aside class="col-xl-3 d-none d-xl-block">
                <?php wc_get_template('archive/sidebar.php'); ?>
            </aside>

            <!-- Products -->
            <main class="col-xl-9">

                <?php wc_get_template('archive/toolbar.php'); ?>

                <?php wc_get_template('archive/products.php'); ?>

            </main>

        </div>

    <?php else : ?>

        <?php wc_get_template('archive/empty.php'); ?>

    <?php endif; ?>

</div>

<?php
get_footer('shop');