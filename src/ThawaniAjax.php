<?php

namespace Thawani;

use Thawani\RestAPI;

class ThawaniAjax
{


    private $id  = 'thawani_gw';

    private $api = null;
    /**
     * initialize wordpress do_action hook 
     * 
     */
    public function __construct(RestAPI $api)
    {
        $this->api = $api;

        add_action('wp_ajax_' . $this->id . '_get_all_sessions', [$this, 'get_all_sessions']);
        add_action('wp_ajax_' . $this->id . '_get_all_customers', [$this, 'get_all_customers']);
        add_action('wp_ajax_' . $this->id . '_get_customer_payment', [$this, 'get_customer_payment']);
    }


    public function get_all_customers()
    {
        if (!isset($_POST['skip'])) {
            $response = $this->api->get_all_customers();

            wp_send_json($response['body'], 200);
            wp_die();
        } else {
            $response = $this->api->get_all_customers([
                'skip' => $_POST['skip'],
                'limit' => $_POST['limit']
            ]);

            wp_send_json($response['body'], 200);
            wp_die();
        }
    }

    public function check_sessions()
    {
    }
    public function get_all_sessions()
    {

        if (!isset($_POST['skip'])) {
            $response = $this->api->get_all_sessions();
            wp_send_json($response['body'], 200);
            wp_die();
        } else {
            $response = $this->api->get_all_sessions([
                'skip' => $_POST['skip'],
                'limit' => $_POST['limit']
            ]);

            wp_send_json($response['body'], 200);
            wp_die();
        }
        //
    }
    public function get_customer()
    {
    }
    public function get_customer_payment()
    {
        if (isset($_POST['customer_token'])) {
            $response  = $this->api->get_payment($_POST['customer_token']);

            wp_send_json($response['body'], 200);
            wp_die();
        }
        wp_send_json('hello', 200);
    }
    public function remove_customer_payment()
    {
    }
}
