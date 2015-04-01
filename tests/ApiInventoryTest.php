<?php

class ApiInventoryTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGet()
	{
		$response = $this->call('GET', 'api/inventory');

        // Let's confirm some of the response headers.
        //
		$this->assertResponseStatus(200, $response->getStatusCode());
        	$this->assertEquals('text/html', $response->headers->get('content-type'));
	}
	public function testPost()
	{
		$response = $this->call('POST', 'api/inventory');

        // Let's confirm some of the response headers.
        //
		$this->assertResponseStatus(201, $response->getStatusCode());
        	$this->assertEquals('application/json', $response->headers->get('content-type'));
	}
}
