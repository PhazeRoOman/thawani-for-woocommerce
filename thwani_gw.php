<?php

/**
 * Plugin Name: Thawani Gateway Woocommerce
 * Plugin URI: https://Alrisi.net
 * Author: Muhannad Alrisi
 * Author URI: https://alrisi.net
 * Description: Thawani Payments Gateway for Woocommerce.
 * Version: 1.0.0
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: thawani
 *
 *
 *@package WooCommerce\Thawani
 */

require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use \Thawani\WC_Gateway_ThawaniGateway;

if (!defined('ABSPATH'))
    exit;


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
        new WC_Gateway_ThawaniGateway();
    }
}
