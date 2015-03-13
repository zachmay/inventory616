<?php

class IndexTest extends TestCase {

	/**
	 * The default route ('/') should redirect to the login page.
	 *
	 * @return void
	 */
	public function testIndexRedirectsToLogin()
	{
		$response = $this->call('GET', '/');

		$this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('auth/login');
	}

}
