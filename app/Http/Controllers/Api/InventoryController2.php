<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

class InventoryController2 extends Controller {

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $tag
	 * @return Json found $item
	 */
	public function show($tag)
	{

		$item=Item::where('asset_tag',$tag)->first();
		return $item;

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $tag
	 * @return Json updated $item
	 */
	public function update($tag)
	{
		$item=Item::where('asset_tag',$tag)->first();

		if(is_null($item))
		{
			return  Response::json('Not Found',404);
		}

		$updateItem = Input::all();

		//input validation
		if(empty($updateItem) or ($updateItem['asset_tag']!=$tag))
		{
			return  Response::json('Invalid Request',400);
		}

		$item->fill($updateItem);
		$item->save();
		return $item;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $tag
	 * @return Json removed $item
	 */
	public function destroy($tag)
	{
		$item=Item::where('asset_tag',$tag)->first();

		if(is_null($item))
		{
			return  Response::json('Not Found',404);
		}

		$deleteItem=$item;
		$item->delete();

		return $deleteItem;
	}

}
