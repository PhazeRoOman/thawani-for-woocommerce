<?php

namespace Thawani\Endpoint;

use Thawani\RestAPI;

/**
 * Thawani gateway Payment Methods endpoint handler
 *
 * Integration of Thawani API class
 *
 * @class       Payment
 * @version     1.3.0
 * @author      PhazeRo
 * @package     WooCommerce\Thawani
 */
class Payment
{

    /**
     * @var object \Thawani\RestAPI
     */
    private  $api = null;
    public function __construct(RestAPI $api)
    {
        $this->api = $api;
    }

    /**
     * Get single payment details 
     * 
     * @param string $payment_id
     * 
     * @return array  response 
     */
    public function get($payment_id)
    {
        return wp_remote_get(
            $this->api->get_endpoint('api/v1/payments/'. $payment_id),
            [
                'headers' => $this->api->get_headers()
            ]
        );
    }

    /**
     * Get payment id by user HTTP query.
     * 
     * @param array $http_query
     * 
     * @return array response
     */
    public function get_by_query($http_query){
        $query = http_build_query($http_query); 

        return wp_remote_get(
            $this->api->get_endpoint('api/v1/payments?') . $query,
            [
                'headers' => $this->api->get_headers()
            ]
        );
    }

    /**
     * Get a list of Payments.
     * 
     * @param array $http_query 
     * 
     * @return array  response 
     */
    public function get_all($http_query = null)
    {
        if($http_query == null) {
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/payments'),
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        } else {
            $query = http_build_query($http_query);
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/payments?') . $query,
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        }
    }

}
