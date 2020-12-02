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

error_reporting(E_ALL);
use \Thawani\ThawaniGateway;
if (!defined('ABSPATH'))
    exit;

    
/**
 * Check if the WooCommerce plugin is active
 */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;

require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

function add_thawani_gateway($gateways)
{
    $gateways[] = 'WC_Gateway_ThawaniGateway';
    return $gateways;
}

add_filter('woocommerce_payment_gateways', 'add_thawani_gateway');

add_action('plugins_loaded', 'init_WC_Gateway_ThawaniGateway', 11);

function init_WC_Gateway_ThawaniGateway()
{
    if (class_exists('WC_Payment_Gateway')) {
       $app = new ThawaniGateway(); 

    }
}
