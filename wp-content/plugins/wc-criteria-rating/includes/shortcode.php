<?php

if (!defined('ABSPATH')) {
    exit;
}


/* ------------------------------
   شورت‌کد جزئیات معیارها (breakdown)
--------------------------------*/
function wccr_shortcode_breakdown( $atts ) {
    $atts = shortcode_atts( ['id' => 0], $atts );
    $product_id = $atts['id'] ? intval($atts['id']) : get_the_ID();
    if ( ! $product_id ) return '';

    $cats = wp_get_post_terms( $product_id, 'product_cat', ['fields'=>'ids'] );
    $criteria = [];
    foreach ( $cats as $cat_id ) {
        $val = get_term_meta( $cat_id, 'product_cat_criteria', true );
        if ( $val ) $criteria = array_merge( $criteria, wccr_parse_criteria_string( $val ) );
    }
    $criteria = array_unique( $criteria );
    if ( empty( $criteria ) ) return '';

    ob_start();
    echo '<div class="wccr-criteria-breakdown">';
    foreach ( $criteria as $label ) {

        $avg = wccr_get_product_criteria_average( $product_id, $label );
        $percent = ( $avg * 20 ); // 0 - 100
        ?>
        <div class="wccr-row">
            <div class="digistylecriteriarow">
                <label><?php echo esc_html( $label ); ?></label>
                <span class="wccr-number"><?php echo number_format( $avg, 2 ); ?></span>
            </div>
            <progress value="<?php echo esc_attr( $avg ); ?>" max="5"></progress>
        </div>
        <?php
    }
    echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'product_criteria_breakdown', 'wccr_shortcode_breakdown' );

/* ------------------------------
   شورت‌کد میانگین کل
--------------------------------*/
function wccr_shortcode_overall( $atts ) {
    $atts = shortcode_atts( ['id' => 0], $atts );
    $product_id = $atts['id'] ? intval($atts['id']) : get_the_ID();
    if ( ! $product_id ) return '';

    $cats = wp_get_post_terms( $product_id, 'product_cat', ['fields'=>'ids'] );
    $criteria = [];
    foreach ( $cats as $cat_id ) {
        $val = get_term_meta( $cat_id, 'product_cat_criteria', true );
        if ( $val ) $criteria = array_merge( $criteria, wccr_parse_criteria_string( $val ) );
    }
    $criteria = array_unique( $criteria );
    if ( empty( $criteria ) ) return '';

    $sum = 0; $count = 0;
    foreach ( $criteria as $label ) {
        $avg = wccr_get_product_criteria_average( $product_id, $label );
        if ( $avg ) { $sum += $avg; $count++; }
    }
    return $count ? number_format( $sum / $count, 2 ) : '0';
}
add_shortcode( 'product_criteria_overall', 'wccr_shortcode_overall' );