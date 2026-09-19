<?php
/**
 * Plugin Name: Random Brand Loop
 * Description: انتخاب تصادفی برندها و اتصال آن‌ها به Loop Grid المنتور + شورت‌کد برای نمایش تصویر برند.
 * Version: 1.0
 * Author: Octo 🐙
 */

// جلوگیری از اجرای مستقیم فایل
if ( !defined('ABSPATH') ) exit;

/**
 * اجرای تابع ساخت کش با اجرای رویداد کرون
 */
add_action('rbl_refresh_brand_cache', 'rbl_build_brand_cache');

/**
 * ساخت کش برندها
 */
function rbl_build_brand_cache() {

    $brands = get_terms([
        'taxonomy'   => 'product_brand',
        'hide_empty' => false,
    ]);

    if (empty($brands) || is_wp_error($brands)) {
        return false;
    }

    $data = [];

    foreach ($brands as $brand) {

        $image_id  = get_term_meta($brand->term_id, 'thumbnail_id', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';

        $data[] = (object)[
            'term_id'   => $brand->term_id,
            'name'      => $brand->name,
            'slug'      => $brand->slug,
            'image_url' => $image_url,
            'link'      => get_term_link($brand),
        ];
    }

    set_transient('rbl_brand_cache', $data, DAY_IN_SECONDS);

    return $data;
}

/**
 * 🔹 مرحله init:
 * برندهای تصادفی را زودتر بساز تا همه‌جا در دسترس باشند
 */
add_action('init', function () {

    $brands = get_transient('rbl_brand_cache');
    
    if ($brands === false) {
        $brands = rbl_build_brand_cache();
    }
    
    if (empty($brands) || is_wp_error($brands)) {
        return;
    }

    // انتخاب برند اول
    $key1 = array_rand($brands);
    $brand1 = $brands[$key1];

    // حذف از لیست
    unset($brands[$key1]);
    $brands = array_values($brands);

    // انتخاب برند دوم
    $brand2 = !empty($brands)
        ? $brands[array_rand($brands)]
        : null;

    $GLOBALS['rbl_current_brand_1'] = $brand1;
    $GLOBALS['rbl_current_brand_2'] = $brand2 ? $brand2 : null;

});

/**
 * 🔹 اتصال برندها به لوپ المنتور
 */
add_action('elementor/query/brand_loop_1', function($query) {
    $brand = $GLOBALS['rbl_current_brand_1'] ?? null;
    if ( !$brand ) return;

    $query->set('tax_query', [[
        'taxonomy' => 'product_brand',
        'field'    => 'term_id',
        'terms'    => [$brand->term_id],
    ]]);
});

add_action('elementor/query/brand_loop_2', function($query) {
    $brand = $GLOBALS['rbl_current_brand_2'] ?? null;
    if ( !$brand ) return;

    $query->set('tax_query', [[
        'taxonomy' => 'product_brand',
        'field'    => 'term_id',
        'terms'    => [$brand->term_id],
    ]]);
});

/**
 * 🔹 شورت‌کد برای نمایش اطلاعات برند
 * [rbl_brand_info id=1 field=image]
 * field: name | image | link | slug
 */
add_shortcode('rbl_brand_info', function($atts) {
    $atts = shortcode_atts([
        'id'    => 1,
        'field' => 'name',
    ], $atts);

    $key = 'rbl_current_brand_' . intval($atts['id']);
    $brand = $GLOBALS[$key] ?? null;
    if (!$brand) return '';

    switch ($atts['field']) {
        case 'image':
            return $brand->image_url ? '<img src="'.esc_url($brand->image_url).'" alt="'.esc_attr($brand->name).'" loading="lazy">' : '';
        case 'link':
            return esc_url($brand->link);
        case 'slug':
            return esc_html($brand->slug);
        case 'image_tag':
            return $brand->image_url
                ? '<img src="' . esc_url($brand->image_url) . '" alt="' . esc_attr($brand->name) . '" loading="lazy">'
                : '';
        case 'name':
        default:
            return esc_html($brand->name);
    }
});


/**
 * زمان‌بندی اختصاصی: هر ۱۲ ساعت
 */
add_filter('cron_schedules', function ($schedules) {

    $schedules['rbl_every_12_hours'] = [
        'interval' => 12 * HOUR_IN_SECONDS,
        'display'  => __('Every 12 Hours'),
    ];

    return $schedules;

});


/**
 *  اجرا در صورت فعال شدن پلاگین
 */
register_activation_hook(__FILE__, function () {

    // اولین بار کش را بساز
    rbl_build_brand_cache();

    // اگر کرون ثبت نشده، ثبتش کن
    if (!wp_next_scheduled('rbl_refresh_brand_cache')) {

        wp_schedule_event(
            time(),
            'rbl_every_12_hours',
            'rbl_refresh_brand_cache'
        );
    }
});


/**
 *  حذف کرون در صورت غیر فعال شدن پلاگین
 */
 register_deactivation_hook(__FILE__, function () {

    wp_clear_scheduled_hook('rbl_refresh_brand_cache');

});