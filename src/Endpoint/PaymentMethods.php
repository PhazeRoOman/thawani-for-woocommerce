<?php

namespace Thawani\Endpoint;

use \Thawani\RestAPI;

/**
 * Thawani gateway Payment Methods endpoint handler
 *
 * Integration of Thawani API class
 *
 * @class       Payment
 * @version     1.0.0
 * @author      Muhannad Alrisi
 * @package     WooCommerce\Thawani
 */
class PaymentMethods
{

    private $api = null;

    public function __construct(RestAPI $api)
    {
        $this->api = $api;
    }

    /**
     * This endpoint will return the card token that has been stored against a specific customer
     * using the customer token which was created previously. Use this method to retrieve the card
     * token and include it in the “create session” endpoint to process payment.
     *
     * @param string $customer_token
     *
     * @return array response
     */
    public function get($customer_token)
    {
        return wp_remote_get(
            $this->api->get_endpoint('api/v1/payment_methods?customerId=') . $customer_token,
            [
                'headers' => $this->api->get_headers(),
            ]
        );
    }

    /**
     * This endpoint is used to remove specific payment method for the customer.
     *
     * @param string $card_token
     *
     * @return array response
     */
    public function delete($card_token)
    {
        return wp_remote_request(
            $this->api->get_endpoint('api/v1/payment_methods/') . $customer_token,
            [
                'method' => 'DELETE',
            ]
        );
    }
}
