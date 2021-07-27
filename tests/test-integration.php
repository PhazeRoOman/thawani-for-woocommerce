<?php
/**
 * Class SampleTest
 *
 * @package Thawani_For_Woocommerce
 */
// require 'vendor/autoload.php'; 
use \Thawani\RestAPI; 
/**
 * Integration tests.
 */
class IntegrationTest extends WP_UnitTestCase {

	protected  $api; 

	public function setUp(){
		parent::setUp(); 
		$this->api  = new  RestAPI('rRQ26GcsZzoEhbrP2HZvLYDbn9C9et', 'HGvTMLDssJghr9tlN9gr4DVYt0qyBy' ,'development'); 
	}
	/**
	 * A single example test.
	 */
	public function test_restAPIControllerClass() {
		$this->assertTrue( ($this->api instanceof RestAPI) );
	}

	public function test_endpointString(){
		//this must be a development 
		$endpoint = $this->api->get_endpoint_env(); 
		$this->assertEquals('https://uatcheckout.thawani.om', $endpoint);
	}

	public function test_getSessionList(){
		$session_list  = $this->api->get_all_sessions();
		$this->assertTrue(is_array($session_list));
	}
	
	public function tearDown(){
		parent::tearDown(); 
		$this->api = null;
	}

}
