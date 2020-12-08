<?php

namespace Thawani\Endpoint;

use Thawani\RestAPI;

/**
 * Thwawni Customers API handler  
 *
 * Integration of Thawani API class 
 *
 * @class       Customer
 * @version     1.0.0
 * @author      Muhannad Alrisi
 * @package     WooCommerce\Thawani
 */
class Customer
{
    /**
     * @var object \Thawani\RestAPI
     */
    private $api = null;
    /**
     * @var const user meta field to store the customer session
     */
    const WP_USER_META = '_thawani_cus';
    public function __construct(RestAPI $api)
    {
        $this->api = $api;
    }

    /**
     * Set customer token/session in WP DB in user meta 
     * 
     * @param string $customer_key 
     * @param string  $customer_id 
     */
    public function set_customer_meta($customer_key, $customer_id = null)
    {
        if ($customer_id)
            add_user_meta($customer_id, self::WP_USER_META, $customer_key, true);
        else
            add_user_meta(wp_get_current_user()->ID, self::WP_USER_META, $customer_key, true);
    }
    /**
     * get customer token/session in WP DB in user meta
     * 
     * @return string customer session/token
     */
    public function get_customer_meta()
    {
        return get_user_meta(wp_get_current_user()->ID, self::WP_USER_META, true);
    }
    // check if the usermeta is existed 
    /**
     * create or add customer to get access to the card 
     * @param array $customer 
     * @param int $customer_id
     * @return array response 
     */
    public function create($customer_id = null)
    {
        if ($customer_id) {

            $user  = get_userdata($customer_id);
            return wp_remote_post(
                $this->api->get_endpoint('api/v1/customers'),
                [
                    'headers' => $this->api->get_headers(),
                    'body' => json_encode([
                        'client_customer_id' => $user->user_email
                    ])
                ]
            );
        }
        return wp_remote_post(
            $this->api->get_endpoint('api/v1/customers'),
            [
                'headers' => $this->api->get_headers(),
                'body' => json_encode([
                    'client_customer_id' => wp_get_current_user()->user_email
                ])
            ]
        );
    }

    /**
     * Get customer information from Thawani gatwway
     * 
     * @return array  response 
     */
    public function get()
    {
        return wp_remote_get(
            $this->api->get_endpoint('api/v1/customers/') . $this->get_customer_meta(),
            [
                'headers' => $this->api->get_headers()
            ]
        );
    }

    public function get_all($http_query = null)
    {
        if ($http_query) {
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/customers/'),
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        } else {
            $query = http_build_query($http_query);
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/customers/?') . $query,
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        }
    }
    /**
     * Delete customer token in Thawani Gateway API 
     * 
     * @return array response
     */
    public function delete()
    {
        return wp_remote_request(
            $this->api->get_endpoint('api/v1/customers/') . $this->get_customer_meta(),
            [
                'method' => 'DELETE',
                'headers' => $this->api->get_headers()
            ]
        );
    }
}
