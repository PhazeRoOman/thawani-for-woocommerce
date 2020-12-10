<?php

namespace Thawani;

use Thawani\RestAPI;
use Thawani\ThawaniAjax;

/**
 * Thwawni gateWay 
 *
 * Integration of Thawani API class 
 *
 * @class       WC_Gateway_ThawaniGateway
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @author      Muhannad Alrisi
 * @package     WooCommerce\Thawani
 */
class WC_Gateway_ThawaniGateway extends \WC_Payment_Gateway
{

    /**
     *@var string secret key of Thwawni API
     */
    protected $secret_key;
    /**
     * @var string publishable key of Thawani API
     **/
    protected $publishable_key;
    /**
     * @var string The environment holder of the plugin
     */
    protected $environment;
    /**
     * @var RestAPI object
     */
    private $api = null;
    /**
     * @var string Thawani prefix field  
     */
    const PREFIX_NAME = 'thawani';
    /**
     * @var string HTTP GET name for wc-api callback 
     */
    const GET_MER_REF = 'ref';

    public function __construct()
    {
        $this->init();

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->secret_key = $this->get_option('secret_key');
        $this->publishable_key = $this->get_option('publishable_key');
        $this->environment = $this->get_option('environment');

        $this->api = new RestAPI($this->secret_key, $this->publishable_key, $this->environment);
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_api_' . $this->id, [$this, 'process_callback']);
        add_action('woocommerce_admin_order_data_after_order_details', [$this, 'thawani_payment_fields']);
    }

    /**
     *
     * Setup the general require
     * options of {WC_Payment_Gateway}
     *
     * @return void
     * @see https://docs.woocommerce.com/document/payment-gateway-api/#section-2
     */
    public function init()
    {
        $this->id = 'thawani_gw'; // payment gateway plugin ID
        $this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
        $this->has_fields = true; // in case you need a custom credit card form
        $this->method_title = 'Thawani Gateway';
        $this->method_description = 'Pay with Thawani'; // will be displayed on the options page
    }

    /**
     * get callback url to woocommerce hook
     * 
     * @param int $order_id WooCommerce order id
     *  
     * @return string woocommerce wc-api callback uri 
     */
    public function get_callback_url($order_id)
    {
        return sprintf(
            "%s/wc-api/%s?%s=%s",
            site_url(),
            $this->id,
            self::GET_MER_REF,
            $order_id
        );
    }
    /**
     * callback API after the client is redirected form
     * Tawani gateway 
     * 
     */
    public function process_callback()
    {

        $id =  $_GET[self::GET_MER_REF];

        echo $id;
        // exit;
        if (($id && intval($id)) ?? false) {
            //get the session token
            $session_token = $this->get_session_token($id);
            $response = $this->api->get_session($session_token);
            //parse the response 
            $data  = json_decode($response['body']);
            if ($data->success) {
                //check the state of the pay,emt 
                if (strtolower($data->data->payment_status) == 'unpaid')
                    $this->redirect_to_order_page($id); // this mean unpaid
                elseif (strtolower($data->data->payment_status) == 'cancelled')
                    $this->update_order_status_cancelled($id); //cancelled in status
                else
                    $this->update_order_status_success($id); // this means success 
            } else {
                $this->update_order_status_cancelled($id);
            }
        }
        wp_redirect(site_url(), 301);
        exit;
    }

    /**
     * redirect the user to the order page 
     * 
     * @param int $order_id
     * 
     */
    public function redirect_to_order_page($order_id)
    {
        $order = wc_get_order($order_id);
        $order_thanks_page =  $this->get_return_url($order);
        wp_redirect($order_thanks_page);
        exit;
    }
    /**
     * update the status of the transaction 
     * after getting the response of Thawani
     * in the Callback to failed
     * 
     * @param int $order_id  
     * 
     * 
     * @since 1.0.0
     */
    protected function update_order_status_failed($order_id)
    {
        $order = wc_get_order($order_id);
        $order_thanks_page =  $this->get_return_url($order);

        $order->update_status('wc-failed', __('payment failed ', 'woocommerce'));
        wc_add_notice(__('Payment failed', 'woocommerce'), 'error');
        wp_redirect($order_thanks_page);
        exit;
    }
    /**
     * update the status of the transaction 
     * after getting the response of Thawani
     * in the Callback to cancelled
     * 
     * @param int $order_id  
     * 
     * 
     * @since 1.0.0
     */
    protected function update_order_status_cancelled($order_id)
    {
        $order = wc_get_order($order_id);
        $order_thanks_page =  $this->get_return_url($order);

        $order->update_status('wc-cancelled', __('payment cancelled by the client', 'woocommerce'));
        wc_add_notice(__(' You have cancelled the payment ', 'woocommerce'), 'error');
        wp_redirect($order_thanks_page);
        exit;
    }

    /**
     * update the status of the transaction 
     * after getting the response of Thawani
     * in the Callback to success
     * 
     * @param int $order_id 
     * 
     * 
     * @since 1.0.0
     */
    protected function update_order_status_success($order_id)
    {
        $order = wc_get_order($order_id);
        $order_thanks_page =  $this->get_return_url($order);

        $order->update_status('wc-processing', __('payment Success', 'woocommerce'));

        wp_redirect($order_thanks_page);
        exit;
    }
    /**
     * Form fields thats shows up in
     * setup page of the payment gateway
     * 
     * @return void 
     */
    public function init_form_fields()
    {
        $this->form_fields = [
            'enabled' => array(
                'title' => __('Enable/Disable', 'thawani-gw'),
                'label' => __('Enable Thawani payment', 'thawani-gw'),
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', 'thawani-gw'),
                'type' => 'text',
                'description' => __('Payment with Thawani e-commerce ', 'thawani-gw'),
                'default' => __('Thawani E-commerce Payments', 'thawani-gw'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'thawani-gw'),
                'type' => 'text',
                'description' => __('shows in the checkout page', 'thawani-gw'),
                'desc_tip' => true,
                'default' => 'Pay with thawani'
            ),
            'secret_key' => array(
                'title' => __('Secret key', 'thawani-gw'),
                'type' => 'text',
                'description' => __('Add your secret key', 'thawani-gw'),
                'desc_tip' => true,
            ),
            'publishable_key' => array(
                'title' => __('publishable Key', 'thawani-gw'),
                'type' => 'text',
                'description' => __('publishable key provided by Thawani', 'thawani-gw'),
                'desc_tip' => true,
            ),
            'environment' => array(
                'title' => __('Select the environment', 'thawani-gw'),
                'type' => 'select',
                'options' => array(
                    'development' => 'Development',
                    'production' => 'production',
                ),
                'description' => __('USE THE DEVELOPMENT ENVIRONMENT FOR TESTING ONLY', 'thawani-gw'),
            ),
        ];
    }

    /**
     * 
     * create a products payload for the request payload 
     * 
     * @param int $order_id order id 
     * 
     * @return array products
     */
    protected function prepare_products($order_id)
    {

        $order_items = wc_get_order($order_id);
        $items = $order_items->get_data()['line_items'];

        $products  = [];

        foreach ($items as $item) {
            $unit_price  = $this->format_price($item->get_data()['total']);
            $products[] = [
                'name' => $item->get_data()['name'],
                'unit_amount' => ($unit_price / (int)$item->get_data()['quantity']),
                'quantity' => $item->get_data()['quantity'],
            ];
        }
        //do the shipping  
        $shipping_total = $order_items->get_data()['shipping_total'];
        if ((int) $this->format_price($shipping_total) > 0) {
            $products[] = [
                'name' => 'Shipping',
                'unit_amount' => $this->format_price($shipping_total),
                'quantity' => 1,
            ];
        }
        return $products;
    }

    /**
     * Get request payload(body) for 
     * Thawani API 
     * 
     * @param WC_Order $order WooCommerce order object 
     * 
     * @return array payload 
     */
    protected function payload($order, $customer_key = null)
    {

        if ($order->get_user_ID() == 0) {
            $order_data  = $order->get_data();
            $parameters = [
                'client_reference_id' => (int) $order->get_id(),
                'products' => $this->prepare_products($order->get_id()),
                'success_url' => $this->get_callback_url($order->get_id()),
                'cancel_url' => $this->get_callback_url($order->get_id()),
                'metadata' => [
                    'order_id' => $order->get_id(),
                    'customer_name' => $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'],
                    'phone' => $order_data['billing']['phone']
                ]
            ];
        } else {
            if (!$customer_key)
                $customer_key = $this->api->get_customer();
            //get the order data 
            $order_data  = $order->get_data();
            $parameters = [
                'client_reference_id' => (int) $order->get_id(),
                'customer_id' => $customer_key,
                'products' => $this->prepare_products($order->get_id()),
                'success_url' => $this->get_callback_url($order->get_id()),
                'cancel_url' => $this->get_callback_url($order->get_id()),
                'metadata' => [
                    'order_id' => $order->get_id(),
                    'customer_name' => $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'],
                    'phone' => $order_data['billing']['phone']
                ]
            ];
        }
        return $parameters;
    }
    /**
     * format price 
     * @param mixed $price 
     * 
     * @return int formatted number for OMR 
     */
    public function format_price($price)
    {
        return number_format($price, 3, '', '.');
    }

    /**
     * Payment process function for the gateway
     * to checkout choosing Thawani as payment method 
     * 
     * @param int $order_id the id of the new order
     * 
     * @see https://docs.woocommerce.com/document/managing-orders/
     * 
     * @return array state[success|fail] & redirection 
     */
    public function process_payment($order_id)
    {
        global $woocommerce;
        //create the order 
        $order = new \WC_Order($order_id);

        $payload = $this->payload($order);
        $get_response = $this->api->create_session($payload);
        $response = json_decode($get_response['body']);

        if (isset($response->success) && $response->success) {

            $this->set_session_token($response->data->session_id, $order->get_id());
            $order->update_status('wc-pending', __('waiting to complete the payment by thawani', 'thawani-gw'));
            $woocommerce->cart->empty_cart();
            return array(
                'result' => 'success',
                'redirect' => $this->api->get_redirect_uri($response->data->session_id)
            );
        }

        $this->set_session_token('faild order', $order->get_id());
        $order->update_status('wc-failed', __('Failed', 'woocommerce'));
        $woocommerce->cart->empty_cart();
        return array(
            'result' => 'fail',
            'redirect' => $this->get_return_url($order)
        );
    }

    /**
     * Get the prefix name of the fielda
     * 
     * @param string $name field name/ id
     * 
     * @return string prefixt_name
     */
    protected function get_field_name($name)
    {
        return self::PREFIX_NAME . '_' . $name;
    }
    /**
     * Wocommerce bulit in metabox 
     * create fields to show up in the admin order page
     * 
     * @return void 
     */
    public function thawani_payment_fields()
    {
        woocommerce_wp_text_input(array(
            'id' => $this->get_field_name('session'),
            'label' => 'session reference:',
            'wrapper_class' => 'form-field-wide',
            'custom_attributes' => array(
                'disabled' => true
            )
        ));

        echo "<p class='form-field form-field-wide'>
        <button type='button' name='th_generate' id='thawani-gw-generate' class='button-secondary' style='margin-bottom:0.5rem' > generate checkout link</button>
        <button type='button' name='th_copy_btn' id='thawani-gw-copy' class='button-secondary' style='margin-bottom:0.5rem' disabled > copy </button>
        <input type='text' name='th_generated_link' id='thawani-gw-generated-link' name='link generated' placeholder='link will be generated' />
        </p>";
    }

    /**
     * Set session token in the order's meta
     * 
     * @param string $session_token session token
     * @param int  $order_id 
     * 
     * @return void 
     */
    public function set_session_token($session_token, $order_id)
    {
        update_post_meta($order_id, $this->get_field_name('session'), $session_token);
    }
    /**
     * Get the session token from the order id 
     * 
     * @param int $order_id  
     * 
     * @return string session token
     */
    public function get_session_token($order_id)
    {
        return get_post_meta($order_id, $this->get_field_name('session'));
    }
}
