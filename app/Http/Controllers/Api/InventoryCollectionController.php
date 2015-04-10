<?php namespace App\Http\Controllers\Api;

//use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class InventoryCollectionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param  string $query
	 * @return all queried $items
	 */
	public function index(Request $request)
	{

		$query = $request->input('query');

		if(!is_null($query)) {
			
			//return only the first queried results
			$items = Item::where('asset_tag','LIKE', '%'.$query.'%')
						 ->orWhere('name','LIKE', '%'.$query.'%')
						 ->orWhere('funding_source', 'LIKE', '%'.$query.'%')
						 ->orWhere('model','LIKE', '%'.$query.'%')
						 ->orWhere('cpu', 'LIKE', '%'.$query.'%')
						 ->orWhere('ram','LIKE', '%'.$query.'%')
						 ->orWhere('os','LIKE', '%'.$query.'%')
						 ->paginate(20);
		

	

			return $items;

		} else {
			
			//return only the first 20 records
			$items = Item::paginate(20);
			return $items;
			
		}
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  none
	 * @return Json input $item
	 */
	public function store(Request $request)
	{
		$this->validate($request, ['asset_tag' => 'required']);

		$item = Item::create($request->all());
		return $item;

	}
	

}
