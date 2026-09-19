<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action( 'wp_ajax_wccr_submit_review', 'wccr_submit_review' );

function wccr_submit_review() {

    check_ajax_referer( 'wccr_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error([
            'message' => 'ابتدا وارد حساب کاربری شوید.'
        ]);
    }

    $product_id = intval( $_POST['comment_post_ID'] );
    $content    = sanitize_textarea_field( $_POST['comment'] );

    $current = get_comments([
    'post_id' => $product_id,
    'user_id' => get_current_user_id(),
    'number'  => 1,
    'status'  => 'all'
    ]);
    
    if ( ! empty( $current ) ) {

        $comment_id = $current[0]->comment_ID;
    
        wp_update_comment([
            'comment_ID'      => $comment_id,
            'comment_content' => $content,
        ]);
    
    }
    else {
    
        $comment_id = wp_insert_comment([
            'comment_post_ID' => $product_id,
            'comment_content' => $content,
            'user_id'         => get_current_user_id(),
            'comment_approved'=> 1
        ]);
    
    }
    
    if ( ! $comment_id ) {
        wp_send_json_error([
            'message' => 'خطا در ذخیره نظر.'
        ]);
    }
    
    foreach ( $_POST as $key => $value ) {

        if ( strpos( $key, 'criteria_' ) === 0 ) {
    
            update_comment_meta(
                $comment_id,
                sanitize_text_field( $key ),
                intval( $value )
            );
        }

    }
    
    wp_send_json_success([
        'message' => 'نظر شما با موفقیت ذخیره شد.'
    ]);
}
