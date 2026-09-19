<?php
defined('ABSPATH') || exit;

global $product;

$gallery_ids = $product->get_gallery_image_ids();

$image_ids = array_merge(
    [$product->get_image_id()],
    $gallery_ids
);

foreach ($image_ids as $image_id):

if (!$image_id) continue;

?>

<div class="gallery-thumb">

    <?php

    echo wp_get_attachment_image(
        $image_id,
        'thumbnail',
        false,
        [
            'class' => 'gallery-thumb-image'
        ]
    );

    ?>

</div>

<?php endforeach; ?>