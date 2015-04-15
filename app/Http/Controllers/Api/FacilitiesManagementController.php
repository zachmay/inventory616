<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Room;
use App\Building;
use App\CheckIn;
use App\ItemType;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacilitiesManagementController extends Controller {

	//get collection of items from id and room id in checkIn table
	function getRoomsInventoryItems($Bid = null, $RoomId = null) {
			
			
			if($Bid == null || trim($Bid) == '' || $RoomId =='' || trim($RoomId) =='')
				return new Response(null,400);
			try{

				//$result = Room::select('id','building_id')->get();
				//$response = new Response($result,200);
				//return $response;
				//testing with bid = 13, item = 11
				$result = Room::where('id',$RoomId)->where('building_id',$Bid)->get();
			
				if($result->count() > 0){
					$itemIdArray = checkIn::select()->where('room_id',$RoomId)->get();
					$items = "";
					foreach($itemIdArray as $value){
						$tempId = $value["item_id"];
						$items .= Item::where('id',$tempId)->get();
					}

					$response = new Response($items,200);
				}
	
			} catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
			}
			return $response;
	}

	//get info about a room resouce
	function getRoomsResource($Bid=null, $RoomId=null){
		

			if($Bid == null || trim($Bid) == '' || $RoomId =='' || trim($RoomId) =='')
				return new Response(null,400);

			try{
				//testing with bid = 13, item = 11
				$RoomResource = Room::where('id',$RoomId)->where('building_id',$Bid)->get();
				$response = new Response($RoomResource,200);

			}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
			}
			return $response;

	}

	function getBuildingResource($BuildingName = null){

			try{
				if(is_null($BuildingName)){

					$BuildingResource = Building::select()->get();
				}
				else{

					$BuildingResource = Building::where('name',$BuildingName)->get();
				}

				$response = new Response($BuildingResource,200);

			}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
			}
			return $response;

	}

	function getBuildingResourceOnId($Bid = null){

		if($Bid == null || trim($Bid) == '')
				return new Response(null,400);

		try{

				$BuildingResource = Building::where('id',$Bid)->get();
				$response = new Response($BuildingResource,200);

		}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
		}
		return $response;
	}

	function getBuildingRoomResource($Bid=null,$RoomName=null){

		if($Bid == null || trim($Bid) == '')
				return new Response(null,400);

		try{
				if(is_null($RoomName))
					$RoomResource = Room::where('building_id',$Bid)->get();
				else
					$RoomResource = Room::where('building_id',$Bid)->where('name',$RoomName)->get();
				$response = new Response($RoomResource,200);

		}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
		}

		return $response;

	}

	// post method for creating building resources 
	function postBuildingResource($tag = null){
			$name_ = Input::json()->get('name');
			$description_ = Input::json()->get('description');
			$building_item = Building::where('name','=',$name_)
					->where('description','=',$description_)->get();
			if(count($building_item)!=0){
					return new Response("resource already exist",400);#redirect("api/buildings/{$building_item[0]->id}");
			}
			$building = new Building;
			$building->name = $name_;
			$building->description = $description_;
			$building->save();
			
			return new Response("Ok",200);//redirect("api/buildings/{$building_item[0]->id}");
	}
	// post method for creating room resources 
	function postRoomResource($tag = null, Request $request){
			
			if($tag == null || trim($tag)=='')
				return Response(null,404);
			$room_name = Input::json()->get('name');
			$room_des = Input::json()->get('description');
			$building_id = Building::find($tag);
			if(empty($building_id))
				return Response("bad request",400);
			$record = Room::where("name","=",$room_name)
				->where("description","=",$room_des)
				->where("building_id","=",$tag)->get();
			if(count($record) != 0){
				return new Response("resource already exist",400);
			}
			$room = new Room;
			$room->name = $room_name;
			$room->description = $room_des;
			$room->building_id = $tag;
			$room->save();
			return new Response("Created New Record",201);//redirect("api/buildings/{$tag}/rooms/{$room->id}");
	
	}
	// put  method for updating building resource  
	function putBuildingUpdate($tag = null, Request $request){
	    if($tag == null || trim($tag) == '')
			return new Response(null,404);
		$building = Building::find($tag);
		if(is_null($building))
			return new Response(null,404);
		$build_name = Input::json()->get('name');
		$build_des = Input::json()->get('description');
		$set_update = false;
		if(!is_null($build_name)){
			$building->name = $build_name;
			$set_update = true;
		}
		if(!is_null($build_des)){
			$building->description = $build_des;
			$set_update = true;
		}
		if($set_update == true)
			$building->save();
		
		return new Response("OK",200);//redirect("api/buildings/{$building_item[0]->id}");
	}
	// put method for room update information
	function putRoomUpdate($tag = null, $num = null, Request $request){
		
		if($tag == null || trim($tag) == '')
			return new Response(null,404);
		if($num == null || trim($num ) == '')
			return new Response(null,404);
		$resource = Room::where('building_id','=',$tag)
			->where('id','=',$num)->get();
		if(count($resource) == 0)
			return new Response("Bad Requests",404);
		$room_name = Input::json()->get('name');
		$room_des = Input::json()->get('description');
		$set_update = false;
		if(!is_null($room_name)){
			Room::where('building_id','=',$tag)
			->where('id','=',$num)->update(array("name" => $room_name));
			$set_update = true;
		}
		if(!is_null($room_des)){
			Room::where('building_id','=',$tag)
			->where('id','=',$num)->update(array("description" => $room_des));
			$set_update = true;
		}
		if($set_update == true)
			$resource[0]->save();
		return new Response("OK",200); 
	}		


}