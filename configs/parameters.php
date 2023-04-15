<?php

defined( 'ABSPATH' ) || exit;

$plugin_name = 'SCB Woocommerce';

$plugin_launcher_path = dirname(__DIR__) . '/';

return [
    'plugin_name' => $plugin_name,
    'plugin_slug' => sanitize_key( $plugin_name ),
    'plugin_version' => '1.0.0',
    'plugin_launcher_file' => $plugin_launcher_path . '/' . basename($plugin_launcher_path) . '.php',
    'plugin_launcher_path' => $plugin_launcher_path,
    'plugin_inc_path' => realpath( $plugin_launcher_path . 'inc/' ) . '/',
    'prefix' => 'scb_woocommerce_',
    'translation_key' => 'scbwoocommerce',
    'is_mu_plugin' => false,
    'template_path' => $plugin_launcher_path . 'templates/',
    'assets_path' => $plugin_launcher_path . 'assets/',
    'assets_url' => plugins_url('/assets', $plugin_launcher_path . '/' . basename($plugin_launcher_path) . '.php'),
];
