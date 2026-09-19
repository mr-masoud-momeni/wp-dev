<?php
/**
 * Plugin Name: Octo Scroll Scene
 * Description: Scroll-driven frame sequence widget for Elementor.
 * Version: 0.1.0
 * Author: Octoopus
 * Text Domain: octo-scroll-scene
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
|--------------------------------------------------------------------------
| Plugin Constants
|--------------------------------------------------------------------------
|
| مسیر و آدرس اصلی افزونه را یک‌بار تعریف می‌کنیم
| تا در فایل‌های دیگر مجبور نباشیم مسیرها را دستی بنویسیم.
|
*/

define(
	'OCTO_SCROLL_SCENE_VERSION',
	'0.1.0'
);

define(
	'OCTO_SCROLL_SCENE_PATH',
	plugin_dir_path( __FILE__ )
);

define(
	'OCTO_SCROLL_SCENE_URL',
	plugin_dir_url( __FILE__ )
);


/*
|--------------------------------------------------------------------------
| Register Assets
|--------------------------------------------------------------------------
|
| فعلاً GSAP و ScrollTrigger را از CDN می‌گیریم.
| بعداً اگر خواستیم می‌توانیم نسخه Local آنها را هم داخل افزونه قرار دهیم.
|
*/

function octo_scroll_scene_register_assets() {

	/*
	 * GSAP
	 */
	wp_register_script(
		'octo-gsap',
		'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js',
		array(),
		'3.13.0',
		true
	);


	/*
	 * GSAP ScrollTrigger
	 */
	wp_register_script(
		'octo-scrolltrigger',
		'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js',
		array( 'octo-gsap' ),
		'3.13.0',
		true
	);


	/*
	 * JavaScript خود ویجت
	 */
	wp_register_script(
		'octo-scroll-scene',
		OCTO_SCROLL_SCENE_URL . 'assets/js/scroll-scene.js',
		array(
			'octo-gsap',
			'octo-scrolltrigger',
		),
		OCTO_SCROLL_SCENE_VERSION,
		true
	);


	/*
	 * CSS خود ویجت
	 */
	wp_register_style(
		'octo-scroll-scene',
		OCTO_SCROLL_SCENE_URL . 'assets/css/scroll-scene.css',
		array(),
		OCTO_SCROLL_SCENE_VERSION
	);
}

add_action(
	'wp_enqueue_scripts',
	'octo_scroll_scene_register_assets'
);


/*
|--------------------------------------------------------------------------
| Register Elementor Widget
|--------------------------------------------------------------------------
|
| اینجا به Elementor می‌گوییم:
|
| «یک ویجت جدید به نام Octo Scroll Scene دارم.»
|
*/

function octo_scroll_scene_register_widget( $widgets_manager ) {

	/*
	 * فایل کلاس ویجت را وارد می‌کنیم.
	 */
	require_once OCTO_SCROLL_SCENE_PATH . 'widgets/class-octo-scroll-scene.php';


	/*
	 * ویجت را به Elementor معرفی می‌کنیم.
	 */
	$widgets_manager->register(
		new \Octo_Scroll_Scene()
	);
}

add_action(
	'elementor/widgets/register',
	'octo_scroll_scene_register_widget'
);