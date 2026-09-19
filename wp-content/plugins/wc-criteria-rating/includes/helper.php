<?php

if (!defined('ABSPATH')) {
    exit;
}
/* ------------------------------
   helper: پارس کردن رشته معیارها (پشتیبانی از ، , ; newline)
--------------------------------*/
function wccr_parse_criteria_string( $val ) {
    if ( ! $val ) return [];
    // پشتیبانی از کاما لاتین، ویرگول فارسی، سمی‌کالن و ردیف‌های جدید
    $parts = preg_split('/\s*[,،;\r\n]+\s*/u', $val);
    $parts = array_filter( array_map( 'trim', $parts ), function($v){ return $v !== ''; } );
    return $parts;
}

/* ------------------------------
   helper: تبدیل لیبل به کلید امن
--------------------------------*/
function wccr_label_to_key( $label ) {
    // lowercase ثابت + md5 تا همیشه یکدست و بدون کاراکتر عجیب باشه
    $label = mb_strtolower( trim( $label ), 'UTF-8' );
    return 'criteria_' . md5( $label );
}

/* ------------------------------
   helper: خواندن مقدار یک معیار از کامنت‌ها
   (اول کلید md5 را بررسی می‌کنیم، سپس fallback به sanitize_title قدیمی)
--------------------------------*/

function wccr_get_product_criteria_average( $product_id, $label ) {

    $key = wccr_label_to_key( $label );

    $comments = get_approved_comments( $product_id );

    $total = 0;
    $count = 0;

    foreach ( $comments as $comment ) {

        $value = get_comment_meta(
            $comment->comment_ID,
            $key,
            true
        );

        if ( $value !== '' ) {
            $total += (float) $value;
            $count++;
        }
    }

    return $count ? $total / $count : 0;
}