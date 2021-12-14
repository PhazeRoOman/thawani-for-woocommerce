<?php

/**
 * Plugin Name: Thawani for WooCommerce
 * Plugin URI: https://github.com/PhazeRoOman/thawani-for-woocommerce
 * Author: PhazeRo
 * Author URI: https://phaze.ro/
 * Description: Thawani Payments Gateway for Woocommerce.
 * Version: 1.3.0
 * License: MIT
License URL: https://mit-license.org/
 * text-domain: thawani
 * Domain Path : /languages
 *
 *
 *@package WooCommerce\Thawani
 */

require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use \Thawani\WC_Gateway_ThawaniGateway;
use \Thawani\AdminDashboard;
use Thawani\RestAPI;
use \Thawani\ThawaniAjax;

if (!defined('ABSPATH'))
    exit;

// define the path of the plugin 
define('THAWANI_GW_DIR', plugin_dir_path(__FILE__));

/**
 * Check if the WooCommerce plugin is active
 */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;

function add_thawani_gateway($gateways)
{
    $gateways[] = '\Thawani\WC_Gateway_ThawaniGateway';
    return $gateways;
}

add_filter('woocommerce_payment_gateways', 'add_thawani_gateway');

add_action('plugins_loaded', 'init_WC_Gateway_ThawaniGateway', 11);

function init_WC_Gateway_ThawaniGateway()
{
    if (class_exists('WC_Payment_Gateway')) {
        new AdminDashboard();
        new ThawaniAjax();
    }
}

/**
 * loading the translation files to support arabic version 
 * @since 1.1.0 
 */
add_action('init', 'thawani_gw_load_textDomain');

/**
 * Load plugin textdomain.
 */
function thawani_gw_load_textDomain()
{
    load_plugin_textdomain('thawani', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
/**
 * Enqueue a script in the WordPress admin on post.php.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function thawani_gw_enqueue_script($hook)
{
    if ('post.php' != $hook) {
        return;
    }
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/dist/thawani-generate.js', array('jquery'), '1.0');
}
add_action('admin_enqueue_scripts', 'thawani_gw_enqueue_script');
