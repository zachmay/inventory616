<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Item;

class ExampleController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function get($itemId)
	{
        $item = Item::find($itemId);

		return $item;
	}

    public function post()
    {
        return new Response('Item created', 201);
    }

}
