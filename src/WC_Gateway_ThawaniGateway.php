<?php

namespace Thawani;

use Thawani\RestAPI;

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
     * @var string Success order status
     */
    protected $order_status;
    /**
     * @var string The environment holder of the plugin
     */
    protected $environment;
    /**
     * @var mixed true if allowing the plugin to enable saving cards
     */
    protected $is_save_cards = null;
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
    /**
     * @var WC_logger object
     */
    private static $log = false;
    /**
     * @var boolean true if logging is enabled
     */
    private static $log_enabled  = false;

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
        $this->order_status = $this->get_option('status');
        self::$log_enabled = ($this->get_option('debug') == "yes") ? true: false;
        // disabled for now -- may updated later to enable this feature 
        // $this->is_save_cards = $this->get_option('save_cards');
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

        $id = $_GET[self::GET_MER_REF];

        if (($id && intval($id)) ?? false) {
            //get the session token
            $session_token = $this->get_session_token($id);
            $response = $this->api->get_session($session_token[0]);
            //parse the response
            $data = json_decode($response['body']);
            if ($data->success) {
                $status = strtolower($data->data->payment_status);
                switch ($status) {
                    case 'unpaid':
                        $this->redirect_to_order_page($id);
                        break;
                    case 'paid':
                        $this->update_order_status_success($id);
                        break;
                    case 'cancelled':
                        $this->update_order_status_failed($id);
                        break;
                }
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
        $order_thanks_page = $this->get_return_url($order);
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
        $order->update_status('wc-failed', __('Customer has cancelled the payment or not completed it ', 'thawani'));
        wc_add_notice(__('You have cancelled the payment, maybe adding to cart or remove item', 'thawani'), 'notice');
        wp_redirect(wc_get_cart_url());
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
        $order_thanks_page = $this->get_return_url($order);

        $order->update_status('wc-cancelled', __('payment cancelled by the client', 'thawani'));
        wc_add_notice(__(' You have cancelled the payment ', 'thawani'), 'error');
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
        $order_thanks_page = $this->get_return_url($order);

        $order->update_status('wc-'.$this->order_status, __('payment Success', 'thawani'));

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
                'title' => __('Enable/Disable', 'thawani'),
                'label' => __('Enable Thawani payment', 'thawani'),
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', 'thawani'),
                'type' => 'text',
                'description' => __('Payment with Thawani e-commerce ', 'thawani'),
                'default' => __('Thawani E-commerce Payments', 'thawani'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'thawani'),
                'type' => 'text',
                'description' => __('shows in the checkout page', 'thawani'),
                'desc_tip' => true,
                'default' => 'Pay with thawani',
            ),
            'secret_key' => array(
                'title' => __('Secret key', 'thawani'),
                'type' => 'text',
                'description' => __('Add your secret key', 'thawani'),
                'desc_tip' => true,
            ),
            'publishable_key' => array(
                'title' => __('Publishable Key', 'thawani'),
                'type' => 'text',
                'description' => __('publishable key provided by Thawani', 'thawani'),
                'desc_tip' => true,
            ),
            // removed since version 1.1.0 and may later be included 
            // 'save_cards' => array(
            //     'title' => __('Save Customer cards', 'thawani'),
            //     'label' => __('Enable Thawani payment to save the customer cards', 'thawani'),
            //     'type' => 'checkbox',
            //     'description' => '',
            //     'default' => 'no',
            // ),
            'status' => array(
                'title' => __('Select complete order status', 'thawani'),
                'type' => 'select',
                'options' => array(
                    'processing' => __('processing' , 'thawani'),
                    'completed' => __('completed', 'thawani'),
                ),
                'description' => __('This is the status that your order will be in after payment is completed', 'thawani'),
                'desc_tip' => true,
            ),'environment' => array(
                'title' => __('Select the environment', 'thawani'),
                'type' => 'select',
                'options' => array(
                    'development' => 'Development',
                    'production' => 'Production',
                ),
                'description' => __('USE THE DEVELOPMENT ENVIRONMENT FOR TESTING ONLY', 'thawani'),
            ),
            'debug' => array(
                'title'       => __('Debug Log', 'thawani'),
                'type'        => 'checkbox',
                'label'       => __('Enable logging', 'thawani'),
                'default'     => 'no',
                'description' => sprintf(__('Log Thawani events', 'thawani'), wc_get_log_file_path('thawani'))
            ),
        ];
    }

    /**
     * get the product price with the tax 
     * 
     * @param WC_ORDER  $order
     * 
     * @return float tax percentage
     */
    protected function get_tax_precent($order)
    {
        $get_tax = 0;
        //need the first array 
        foreach ($order->get_items('tax') as $item) {
            $get_tax = $item->get_data();
            break;
        }

        return $get_tax['rate_percent'];
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
        // get the tax set of the order 
        $tax  = $this->get_tax_precent($order_items);

        $products = [];

        foreach ($items as $item) {
            $product_price  = (float) $item->get_data()['total'];

            if ((float)$tax > 0)
                $unit_price = $this->format_price($product_price + ($product_price * ($tax / 100)));
            else
                $unit_price = $this->format_price($product_price);

            $product_name = $item->get_data()['name'];
            if (strlen($product_name) > 40)
                $product_name = mb_substr($product_name, 0, 30, 'UTF-8') . '...';
            $products[] = [
                'name' => $product_name,
                'unit_amount' => ($unit_price / (int) $item->get_data()['quantity']),
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

        $order_data = $order->get_data();
        $parameters = [
            'client_reference_id' => (int) $order->get_id(),
            'products' => $this->prepare_products($order->get_id()),
            'success_url' => $this->get_callback_url($order->get_id()),
            'cancel_url' => $this->get_callback_url($order->get_id()),
            'metadata' => [
                'order_id' => $order->get_id(),
                'customer_name' => $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'],
                'phone' => $order_data['billing']['phone'],
                'email' => $order_data['billing']['email'],
            ],
        ];
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
        return number_format($price, 3, '', '');
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

        $this->logger('📝Started payment process for order ' . $order->get_order_number());
        $this->logger('📝Attempting to create Session');

        $payload = $this->payload($order);
        $get_response = $this->api->create_session($payload);
        $response = json_decode($get_response['body']);

        if (isset($response->success) && $response->success) {
            $this->logger('📝Session Created Succesfully');
            $this->logger('📝Session ID: ' . $response->data->session_id);
            $this->logger('📝Response Code: ' . $response->code);
            $this->logger('📝Response Description: ' . $response->description);
            $this->logger('📝Success URL: ' . $this->api->get_redirect_uri($response->data->session_id));
            $this->logger('📝Cancel URL: ' . $this->get_return_url($order));

            $this->set_session_token($response->data->session_id, $order->get_id());
            $order->update_status('wc-pending', __('waiting to complete the payment ', 'thawani'));
            return array(
                'result' => 'success',
                'redirect' => $this->api->get_redirect_uri($response->data->session_id),
            );
        }
        $this->logger('📝Session creation Failed');
        $this->logger('📝Response log');
        foreach($response as $key => $value) { 
            $this->logger("📝Response {$key} : "  . $value);
        }
        $this->logger('📝Success URL: ' . $this->api->get_redirect_uri($response->data->session_id));
        $this->logger('📝Cancel URL: ' . $this->get_return_url($order));

        $this->set_session_token('faild order', $order->get_id());
        $order->update_status('wc-failed', __('Failed to redirect to the payment gateway', 'thawani'));
        return array(
            'result' => 'fail',
            'redirect' => $this->get_return_url($order),
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
                'disabled' => true,
            ),
        ));

        echo "<p class='form-field form-field-wide'>
        <button type='button' name='th_generate' id='thawani-gw-generate' class='button-secondary' style='margin-bottom:0.5rem' > " . __("generate checkout link", "thawani") . "</button>
        <button type='button' name='th_copy_btn' id='thawani-gw-copy' class='button-secondary' style='margin-bottom:0.5rem' disabled > " . __('copy', 'woocommerce') . " </button>
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


    /**
     * Log a message in woocommerce plugin
     * Message can be found in woocommerce -> status -> log
     * 
     * @param string $message
     * 
     * @return void
     */
    public function logger($message)
    {
        if (self::$log_enabled){
            if (empty(self::$log)) {
                self::$log = new \WC_Logger();
            }
            self::$log->add('thawani', $message);
        }
    }
}
