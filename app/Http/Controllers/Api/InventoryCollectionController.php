<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
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
	public function index($query)
	{
		//$items = Item::all();

		if(!is_null($query)) {
			
			$items = Item::where('asset_tag', $query)->orWhere('name', $query)
			->orWhere('funding_source', $query)->orWhere('model', $query)
			->orWhere('cpu', $query)->orWhere('ram', $query)->orWhere('os', $query)
			->orWhere('administrator_flag', $query)->orWhere('teacher_flag', $query)
			->orWhere('student_flag', $query)->orWhere('institution_flag', $query)->get();

			return $items;

		} else {
			return Response::json("Bad Request", 400);
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
		$this->validate($request, ['asset_tag' => 'required', 'name'=>'required',
		'administrator_flag' =>'required', 'teacher_flag'=>'required',
		'institution_flag'=>'required']);

		Item::create($request->all());

		return Response::json("Created", 201);;

	}
	

}
