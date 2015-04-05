<?php namespace App\Http\Controllers\Api; 

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemTypeController extends Controller {

	/**
	 * Return inventory types 
	 *
	 * @return Inventory item types
	 */
	public function index()
	{
		$httpContents = '';
		$httpStatus = 200;
		$itemTypes = null;

		$itemTypes = ItemType::all();
		
		if(is_null($itemTypes))
		{
			$httpContents = 'Object is null';
			$httpStatus = 404;
		}
		else
		{
			// Check if content is empty
			if(empty($itemTypes))
			{
				$httpContents = 'No item types available';
			}
			else
			{
				$httpContents = $itemTypes;
			}
		}

		return  Response::make($httpContents, $httpStatus); 

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
