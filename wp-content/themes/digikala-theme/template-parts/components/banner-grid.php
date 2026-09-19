<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Component Arguments
|--------------------------------------------------------------------------
*/

$args = wp_parse_args($args ?? [], [

    'id'    => '',

    'class' => '',

    'data'  => []

]);


$data = wp_parse_args($args['data'], [

    'category' => '',

]);


if (empty($data['category'])) {
    return;
}



/*
|--------------------------------------------------------------------------
| Load Banners
|--------------------------------------------------------------------------
*/

$banner = octo_get_banners($data['category']);


if (empty($banner['items'])) {
    return;
}



if (($banner['layout'] ?? '') !== 'grid') {
    return;
}


$items = $banner['items'];



/*
|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------
*/

$class = 'banner-grid row g-3';


if (!empty($args['class'])) {

    $class .= ' ' . $args['class'];

}

?>


<section class="banner-grid-wrapper octo-container">


<div class="<?php echo esc_attr($class); ?>">



<?php foreach ($items as $item): ?>


<?php

$width = !empty($item['width'])
    ? absint($item['width'])
    : 12;


?>


<div class="col-12 col-lg-<?php echo esc_attr($width); ?>">



<a

href="<?php echo esc_url($item['desktop_link']); ?>"

target="<?php echo esc_attr($item['target']); ?>">



<img

src="<?php echo esc_url($item['desktop_image']); ?>"

alt="<?php echo esc_attr($item['title']); ?>"

class="w-100"

loading="lazy">



</a>



</div>



<?php endforeach; ?>



</div>


</section>