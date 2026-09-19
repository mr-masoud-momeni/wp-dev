<?php
defined('ABSPATH') || exit;

global $product;

$attributes = $product->get_attributes();
?>

<section id="specifications" class="product-section">

    <h2><?php esc_html_e('Specifications', 'digikala-theme'); ?></h2>

    <?php if (!empty($attributes)) : ?>

        <table class="table">

            <tbody>

            <?php foreach ($attributes as $attribute) :

                if ($attribute->is_taxonomy()) {

                    $values = wc_get_product_terms(
                        $product->get_id(),
                        $attribute->get_name(),
                        ['fields' => 'names']
                    );

                    $label = wc_attribute_label($attribute->get_name());

                    $value = implode('، ', $values);

                } else {

                    $label = $attribute->get_name();

                    $value = implode('، ', $attribute->get_options());

                }

                ?>

                <tr>

                    <th><?= esc_html($label); ?></th>

                    <td><?= esc_html($value); ?></td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    <?php else : ?>

        <p>ویژگی‌ای برای این محصول ثبت نشده است.</p>

    <?php endif; ?>

</section>