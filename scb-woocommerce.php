<?php
/**
 * Plugin Name: SCB Woocommerce
 * Version: 1.0.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: scbwoocommerce
 * Domain Path: /languages
 */
use function SCBWoocommerce\Dependencies\LaunchpadCore\boot;

defined( 'ABSPATH' ) || exit;


require __DIR__ . '/inc/Dependencies/LaunchpadCore/boot.php';

boot(__FILE__);
