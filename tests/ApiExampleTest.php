<?php

class ApiExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGet()
	{
		$response = $this->call('GET', 'api/example');

        // Let's confirm some of the response headers.
        //
		$this->assertResponseStatus(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('content-type'));

        // Now let's inspect the content.
        //
        $body = json_decode($response->getContent());

        $this->assertEquals(1, $body->a);
        $this->assertEquals(2, $body->b);
	}

}
