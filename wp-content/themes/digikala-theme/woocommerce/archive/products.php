<?php
defined('ABSPATH') || exit;

?>

<div class="row g-3">

    <?php while (have_posts()) : the_post(); ?>

        <div class="col-6 col-md-4 col-xl-3">

            <?php wc_get_template_part('content', 'product'); ?>

        </div>

    <?php endwhile; ?>

</div>

<div class="mt-4 d-flex justify-content-center">

    
<?php
do_action('woocommerce_after_shop_loop'); ?>

</div>
