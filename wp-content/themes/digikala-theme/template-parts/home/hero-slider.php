<?php

$banners = octo_get_banners('hero-main');

if (empty($banners)) {
    return;
}

?>

<div
    id="heroSlider"
    class="carousel slide"
    data-bs-ride="carousel">

    <div class="carousel-indicators">

        <?php foreach ($banners as $index => $banner) : ?>

            <button
                type="button"
                data-bs-target="#heroSlider"
                data-bs-slide-to="<?php echo $index; ?>"
                class="<?php echo $index === 0 ? 'active' : ''; ?>"
                <?php echo $index === 0 ? 'aria-current="true"' : ''; ?>>
            </button>

        <?php endforeach; ?>

    </div>

    <div class="carousel-inner">

        <?php foreach ($banners as $index => $banner) : ?>

            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">

                <a
                    href="<?php echo esc_url($banner['desktop']['link']); ?>"
                    target="<?php echo esc_attr($banner['target']); ?>">

                    <img
                        src="<?php echo esc_url($banner['desktop']['image']); ?>"
                        class="d-block w-100"
                        alt="<?php echo esc_attr($banner['title']); ?>">

                </a>

            </div>

        <?php endforeach; ?>

    </div>

    <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#heroSlider"
        data-bs-slide="prev">

        <span class="carousel-control-prev-icon"></span>

    </button>

    <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#heroSlider"
        data-bs-slide="next">

        <span class="carousel-control-next-icon"></span>

    </button>

</div>