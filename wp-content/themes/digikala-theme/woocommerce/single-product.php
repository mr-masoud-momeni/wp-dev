<?php
defined('ABSPATH') || exit;

get_header('shop');
?>

<?php
    do_action('woocommerce_before_main_content');

    while (have_posts()) :
        the_post();

        wc_get_template_part('content', 'single-product');

    endwhile;
?>

<?php
    do_action('woocommerce_after_main_content');
?>

<?php
get_footer('shop');