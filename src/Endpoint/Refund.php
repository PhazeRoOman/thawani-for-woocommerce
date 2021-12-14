<?php

namespace Thawani\Endpoint;

use Thawani\RestAPI;

/**
 * Thwawni Customers API handler  
 *
 * Integration of Thawani API class 
 *
 * @class       Customer
 * @version     1.3.0
 * @author      PhazeRo
 * @package     WooCommerce\Thawani
 */
class Refund{

    /**
     * @var object \Thawani\RestAPI
     */
    private $api = null;

    public function __construct(RestAPI $api)
    { 
        $this->api = $api;
    }
    /**
     * Get refund details by passing the refund ID
     * 
     * @param string $refund_id
     * 
     * @return array Response
     */
    public function get($refund_id)
    {
        return wp_remote_get(
            $this->api->get_endpoint('api/v1/refunds/'. $refund_id),
            [
                'headers' => $this->api->get_headers(),
            ]
        );
    }
    /**
     * Get the list of the refunds 
     * 
     * @param array $http_query skip and limit
     * 
     * @return array Response 
     */
    public function get_all($http_query = null)
    {
        if($http_query == null) {
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/refunds'),
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        } else {
            $query = http_build_query($http_query);
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/refunds/?') . $query,
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        }
    }
    /**
     * Create new refund 
     * 
     * @param array $payload 
     * 
     * @return array Response
     */
    public function create($payload)
    {
        return wp_remote_post(
            $this->api->get_endpoint('api/v1/refunds'),
            [
                'headers' => $this->api->get_headers(),
                'body' => json_encode($payload)
            ]
        );
    }
}
