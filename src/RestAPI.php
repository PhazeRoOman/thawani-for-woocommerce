<?php

namespace Thawani;

use Thawani\Endpoint\Customer;
use Thawani\Endpoint\Session;

/**
 * Thwawni REST API Handler 
 *
 *  the skeleton of the REST API 
 *
 * @class       RestAPI
 * @version     1.0.0
 * @author      Muhannad Alrisi
 * @package     WooCommerce\Thawani
 */
class RestAPI
{

    private $secret_key = null;
    private $publishable_key = null;
    private $env = null;

    private const DEV_ENDPOINT = 'https://uatcheckout.thawani.om';
    private const PROD_ENDPOINT = 'https://checkout.thawani.om';

    private $session = null;
    private $customer = null;
    public function __construct($secret_key, $publishable_key, $env)
    {
        $this->secret_key = $secret_key;
        $this->publishable_key = $publishable_key;
        $this->env  = $env;

        $this->session = new Session($this);
        $this->customer = new Customer($this);
    }

    /**
     * Get the API endpoint depend on the environmen
     * 
     * @return string endpoint  
     */
    public function get_endpoint_env()
    {
        return (strtolower($this->env) == 'development')
            ? self::DEV_ENDPOINT
            : self::PROD_ENDPOINT;
    }

    /**
     * prepare HTTP header token in the request 
     * 
     * @return array HTTP header 
     */
    public function get_headers()
    {
        return [
            'Content-Type' => 'Application/json',
            'Thawani-Api-Key' => $this->secret_key
        ];
    }
    /**
     * get the publishable key
     * 
     * @return string publishable_key 
     */
    public function get_publishable_key()
    {
        return $this->publishable_key;
    }
    /**
     * prepare the endpoint for the remote request HTTP client
     * Do not add / in the suffix e.g api/get/detail not /api/get/detail
     * 
     * @param string $suffix e.g api/get/detail not /api/get/detail
     * 
     * @return string full endpoint url
     */
    public function get_endpoint($suffix)
    {
        return sprintf('%s/%s', $this->get_endpoint_env(), $suffix);
    }

    /**
     * Create Thawani session 
     * 
     * @param array $payload request body
     * 
     * @return array response 
     */
    public function create_session($payload)
    {
        return  $this->session->create($payload);
    }
    /**
     * Get  full redirect path to thawani payment 
     * 
     * @param string $sesion_key
     * 
     * @return string uri path 
     */
    public function get_redirect_uri($sesion_key)
    {
        return $this->session->redirect($sesion_key);
    }
    /**
     * create customer token 
     * 
     * @return string\boolean if success it returns customer token otherwise false 
     */
    public function create_customer()
    {
        $response  = $this->customer->create();
        $data  = json_decode($response['body']);
        if (isset($data->success) && $data->success) {
            $this->customer->set_customer_meta($data->data->id);
            return $data->data->id;
        }
        return false;
    }
    /**
     * Get the customer token or id
     * 
     * @return string customer token or id
     */
    public function get_customer()
    {
        if ($this->customer->get_customer_meta() == '')
            return $this->create_customer();

        return $this->customer->get_customer_meta();
    }
    /**
     * get the session from Thawani gateway
     * 
     * @param string $session_token 
     * 
     * @return array response
     */
    public function get_session($session_token)
    {
        return $this->session->get($session_token);
    }
}
