<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;

class ExampleController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function get()
	{
		return [ 'a' => 1, 'b' => 2 ];
	}

    public function post()
    {
        return new Response('Item created', 201);
    }

}
