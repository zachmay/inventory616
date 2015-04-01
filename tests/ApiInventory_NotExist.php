<?php

class ApiInventoryNot_ExistTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGet()
	{
		$response = $this->call('GET', 'api/inventory/NotExistXGAINELCAI');

        // Let's confirm some of the response headers.
        //
		$this->assertResponseStatus(404, $response->getStatusCode());
	}
}
