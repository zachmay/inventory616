<?php

class ValidRoomTest_GET extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGet()
	{
		$response = $this->call('GET', 'http://192.168.33.99/api/buildings/101/rooms/3320/inventory');

        // Let's confirm some of the response headers.
        //
		$this->assertResponseStatus(200, $response->getStatusCode());
		$this->assertEquals('application/json', $response->headers->get('content-type'));
		$body = json_decode($response->getContent());
	}
}
