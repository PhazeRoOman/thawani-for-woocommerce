<?php

namespace Thawani\Endpoint;

use Thawani\RestAPI;

/**
 * Thwawni Session Endpoint handler 
 *
 * Integration of Thawani API class 
 *
 * @class       Session
 * @version     1.0.0
 * @author      Muhannad Alrisi
 * @package     WooCommerce\Thawani
 */
class Session
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
     * return the response of creating a session by using the session API endpoint 
     * 
     * @param array $payload body data
     * 
     * @return array response 
     */
    public function create($payload)
    {
        $response  = wp_remote_post($this->api->get_endpoint('api/v1/checkout/session'), [
            'headers' => $this->api->get_headers(),
            'body' => json_encode($payload)
        ]);
        return $response;
    }

    /**
     * Get single session information 
     * 
     * @return array response 
     */
    public function get($session_key)
    {
        return wp_remote_get(
            $this->api->get_endpoint('api/v1/checkout/session/') . $session_key,
            [
                'headers' => $this->api->get_headers()
            ]
        );
    }
    /**
     * get all sessions 
     * 
     * @param array $http_query 
     * 
     * @return array  response 
     */
    public function get_all($http_query = null)
    {
        if ($http_query == null) {
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/checkout/session/'),
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        } else {
            $query = http_build_query($http_query);
            return wp_remote_get(
                $this->api->get_endpoint('api/v1/checkout/session?') . $query,
                [
                    'headers' => $this->api->get_headers()
                ]
            );
        }
    }
    /**
     * Hey we want to get the link of the endpoint 
     */
    public function redirect($session_key)
    {
        $path =  sprintf("pay/%s?key=%s", $session_key, $this->api->get_publishable_key());
        return $this->api->get_endpoint($path);
    }
}
