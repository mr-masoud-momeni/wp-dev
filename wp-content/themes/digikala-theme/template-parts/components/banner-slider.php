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

    'category'   => '',
    'navigation' => null,
    'pagination' => null,

]);

if (empty($data['category'])) {
    return;
}

/*
|--------------------------------------------------------------------------
| Load Slider
|--------------------------------------------------------------------------
*/

$slider = octo_get_banner_slider($data['category']);

if (empty($slider['items'])) {
    return;
}

$settings = $slider['settings'];
$items    = $slider['items'];

/*
|--------------------------------------------------------------------------
| Override Settings
|--------------------------------------------------------------------------
*/

if ($data['navigation'] !== null) {
    $settings['navigation'] = (bool) $data['navigation'];
}

if ($data['pagination'] !== null) {
    $settings['pagination'] = (bool) $data['pagination'];
}

/*
|--------------------------------------------------------------------------
| HTML Options
|--------------------------------------------------------------------------
*/

$slider_id = !empty($args['id'])
    ? sanitize_html_class($args['id'])
    : 'banner-slider-' . wp_unique_id();

$section_class = 'banner-slider';

if (!empty($args['class'])) {
    $section_class .= ' ' . $args['class'];
}

$carousel_class = 'carousel slide';

if (($settings['effect'] ?? 'slide') === 'fade') {
    $carousel_class .= ' carousel-fade';
}

?>

<section class="<?php echo esc_attr($section_class); ?>">

    <div

        id="<?php echo esc_attr($slider_id); ?>"

        class="<?php echo esc_attr($carousel_class); ?>"

        data-bs-ride="<?php echo !empty($settings['autoplay']) ? 'carousel' : 'false'; ?>"

        data-bs-interval="<?php echo esc_attr($settings['delay']); ?>"

        data-bs-wrap="<?php echo !empty($settings['loop']) ? 'true' : 'false'; ?>"

        data-bs-pause="<?php echo !empty($settings['pause']) ? 'hover' : 'false'; ?>">

        <?php if (!empty($settings['pagination'])) : ?>

            <div class="carousel-indicators">

                <?php foreach ($items as $index => $item) : ?>

                    <button

                        type="button"

                        data-bs-target="#<?php echo esc_attr($slider_id); ?>"

                        data-bs-slide-to="<?php echo esc_attr($index); ?>"

                        class="<?php echo $index === 0 ? 'active' : ''; ?>"

                        <?php echo $index === 0 ? 'aria-current="true"' : ''; ?>

                        aria-label="Slide <?php echo esc_attr($index + 1); ?>">

                    </button>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

        <div class="carousel-inner">

            <?php foreach ($items as $index => $item) :

                $desktop_image = $item['desktop_image'];

                $mobile_image = !empty($item['mobile_image'])
                    ? $item['mobile_image']
                    : $desktop_image;

                $desktop_link = !empty($item['desktop_link'])
                    ? $item['desktop_link']
                    : '#';

                $mobile_link = !empty($item['mobile_link'])
                    ? $item['mobile_link']
                    : $desktop_link;

            ?>

                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">

                    <a

                        href="<?php echo esc_url($desktop_link); ?>"

                        target="<?php echo esc_attr($item['target']); ?>">

                        <picture>

                            <source

                                media="(max-width:991px)"

                                srcset="<?php echo esc_url($mobile_image); ?>">

                            <img

                                src="<?php echo esc_url($desktop_image); ?>"

                                class="d-block w-100"

                                alt="<?php echo esc_attr($item['title']); ?>"

                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">

                        </picture>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>

        <?php if (!empty($settings['navigation'])) : ?>

            <button

                class="carousel-control-prev"

                type="button"

                data-bs-target="#<?php echo esc_attr($slider_id); ?>"

                data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>

                <span class="visually-hidden">

                    Previous

                </span>

            </button>

            <button

                class="carousel-control-next"

                type="button"

                data-bs-target="#<?php echo esc_attr($slider_id); ?>"

                data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>

                <span class="visually-hidden">

                    Next

                </span>

            </button>

        <?php endif; ?>

    </div>

</section>