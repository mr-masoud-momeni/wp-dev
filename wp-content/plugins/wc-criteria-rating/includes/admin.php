<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action( 'product_cat_add_form_fields', function() { ?>
    <div class="form-field">
        <label for="product_cat_criteria">معیارهای امتیاز (با کاما جدا کنید)</label>
        <input type="text" name="product_cat_criteria" id="product_cat_criteria" value="">
        <p class="description">مثال: کیفیت دوخت, جنس پارچه, راحتی</p>
    </div>
<?php });

add_action( 'product_cat_edit_form_fields', function( $term ) {
    $value = get_term_meta( $term->term_id, 'product_cat_criteria', true ); ?>
    <tr class="form-field">
        <th scope="row"><label for="product_cat_criteria">معیارهای امتیاز</label></th>
        <td>
            <input type="text" name="product_cat_criteria" id="product_cat_criteria" value="<?php echo esc_attr( $value ); ?>">
            <p class="description">مثال: کیفیت دوخت, جنس پارچه, راحتی</p>
        </td>
    </tr>
<?php }, 10, 1 );

add_action( 'edited_product_cat', 'wccr_save_product_cat_criteria' );
add_action( 'create_product_cat', 'wccr_save_product_cat_criteria' );
function wccr_save_product_cat_criteria( $term_id ) {
    if ( isset( $_POST['product_cat_criteria'] ) ) {
        update_term_meta( $term_id, 'product_cat_criteria', sanitize_text_field( wp_unslash( $_POST['product_cat_criteria'] ) ) );
    }
}