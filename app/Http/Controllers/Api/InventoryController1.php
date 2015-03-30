<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

class InventoryController1 extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param  string $query
	 * @return json all or queried $items
	 */
	public function index($query)
	{
		$items = Item::all();

		if(!is_null($query)) {
			$items = $items->find($query);
		}

		if(is_null($items)) {
			return Response::json("Not Found", 404);
		}
		return $items;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  none
	 * @return Json input $item
	 */
	public function store()
	{
		$input = Input::all();
		if(is_null($input)) {
			return Response::json("No Input", 406);
		}
		Item::create($input);
		return $input;
	}

	

}
