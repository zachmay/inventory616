<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;

class CheckInController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function get()
	{
//		echo $tag;
		return [ 'hahahah' => 1, 'b' => 2 ];
	}

    public function post()
    {
        return new Response('Item created', 201);
    }

}
