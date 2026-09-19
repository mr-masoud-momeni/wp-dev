<?php

if (!defined('ABSPATH')) {
    exit;
}

// frontend.php

add_action('wp_enqueue_scripts', 'wccr_enqueue_assets');

function wccr_enqueue_assets() {

    wp_enqueue_style(
        'wccr-style',
        WCCR_URL . 'assets/style.css'
    );

    wp_enqueue_script(
        'wccr-script',
        WCCR_URL . 'assets/script.js',
        ['jquery'],
        '1.1',
        true
    );

    wp_localize_script(
        'wccr-script',
        'wccr',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wccr_nonce'),
        ]
    );
}

add_action('comment_form_logged_in_after', function() {
    if ( ! is_singular('product') ) return;
    
    global $product;
    
    $current_comment = null;
    if ( is_user_logged_in() ) {
    
        $comments = get_comments([
            'post_id' => $product->get_id(),
            'user_id' => get_current_user_id(),
            'status'  => 'all',
            'number'  => 1,
        ]);
    
        if ( ! empty( $comments ) ) {
            $current_comment = $comments[0];
        }
    
    }
    
    
    $cats = wp_get_post_terms( $product->get_id(), 'product_cat', ['fields'=>'ids'] );
    $criteria = [];

    foreach ( $cats as $cat_id ) {
        $val = get_term_meta( $cat_id, 'product_cat_criteria', true );
        if ( $val ) {
            $criteria = array_merge( $criteria, wccr_parse_criteria_string( $val ) );
        }
    }
    $criteria = array_unique( $criteria );

    if ( !empty( $criteria ) ) {
        echo '<div class="wccr-rating-fields"><h4>امتیازدهی جزئی</h4>';
        foreach ( $criteria as $label ) {
            // کلید امن
            $field = wccr_label_to_key( $label );
            
            
            //بازیابی مقدار امتیاز کاربری که قبلا نظرش را ثبت کرده است
            $value = 3;

            if ( $current_comment ) {
            
                $saved = get_comment_meta(
                    $current_comment->comment_ID,
                    $field,
                    true
                );
            
                if ( $saved !== '' ) {
                    $value = $saved;
                }
            
            }
            // پایان بازیابی مقدار امتیاز کاربر
            
            
            // id یکتا برای span (استفاده شده در oninput)
            $span_id = 'wccr_output_' . substr( $field, 9, 8 ); // بخشی از هش برای یکتا بودن
            echo '<div class="range-wrapper">';
            echo '<div class="digistylecriteriarow"><label>' . esc_html( $label ) . '</label><span id="' . esc_attr($span_id) . '">' . esc_html($value) . '</span></div>';
            // input بعد از span نیست ولی ما می‌شناسیم آیدی span رو پس از اون صدا می‌زنیم
            echo '<input type="range" name="'.esc_attr($field).'" min="1" max="5" step="1" value="' . esc_attr($value) . '" 
                   oninput="document.getElementById(\''.esc_js($span_id).'\').innerText=this.value" class="range-slider">';
            echo '</div>';
        }
        echo '</div>';
    }
});