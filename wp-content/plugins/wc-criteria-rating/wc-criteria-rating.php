<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WCCR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WCCR_URL', plugin_dir_url( __FILE__ ) );

require_once WCCR_PATH . 'includes/helper.php';
require_once WCCR_PATH . 'includes/admin.php';
require_once WCCR_PATH . 'includes/frontend.php';
require_once WCCR_PATH . 'includes/shortcode.php';
require_once WCCR_PATH . 'includes/ajax.php';