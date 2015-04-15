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

	/* POST /buildings
	 * 
	 * Create a new resource based on the data included in the body
	 * 
	 * Body should include name and description.
	 *
	 * Returns 201 Created on success.
	 * Returns 500 Internal Server Error on failure.
	 */
	function postBuildingResource(){
			$name_ = Input::json()->get('name');
			$description_ = Input::json()->get('description');
			$building = new Building;
			$building->name = $name_;
			$building->description = $description_;
			if($building->save())
				return new Response(null,201);//redirect("api/buildings/{$building_item[0]->id}");
			else
				return new Response(null,500);
	}
	
	/* POST /buildings/:id/rooms
	 * 
	 * Create a new room resource, assoicated with the given building, based on the data 
	 * included in the body.
	 * 
	 * Body should include name and description.
	 
	 * Returns 201 Created on success.
	 * Returns 400 Bad request if invalid input.
	 * Returns 500 Internal Server Error on commit failure.
	 * 
	 */ 
	function postRoomResource($bid = null, Request $request){
			
			if($bid == null || trim($bid)=='')
				return new Response(null,400);
				
			$room_name = Input::json()->get('name');
			$room_des = Input::json()->get('description');
			
			$room = new Room;
			$room->name = $room_name;
			$room->description = $room_des;
			$room->building_id = $bid;
			if($room->save())
				return new Response(null,201);
			else
				return new Response(null,500);
	}
	
	/* PUT /buildings/:id
	 * 
	 * Update the specified building resource with the data included in the request body.
	 * The body should contain any of the following fields: name, description.
	 * 
	 * Body should include name and description.
	 
	 * Returns 200 OK on success.
	 * Returns 304 Not Modified if no changes made.
	 * Returns 400 Bad Request on bad input.
	 * Returns 404 Not Found if building id not found.
	 * Returns 500 Internal Server Error on commit error.
	 */
	function putBuildingUpdate($bid = null, Request $request){
	    if($tag == null || trim($bid) == '')
			return new Response(null,400);
		
		try {
			$building = Building::findOrFail($bid);
		} catch(ModelNotFoundException $e) {
			return new Response(null,404);
		}
			
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
		
		if($set_update) {
			if($building->save())
				return new Response(null,200);
			else
				return new Response(null,500);
		} else {
			return new Response(null,304);
		}
	}
	
	/* PUT /buildings/:buildingid/rooms/:roomid
	 * 
	 * Update the specified room resource with the data included in the request body.
	 * The body should contain any of the following fields: name, description.
	 * 
	 * Body should include name and description.
	 * 
	 * Returns 200 OK on success.
	 * Returns 304 Not Modified if no changes made.
	 * Returns 400 Bad Request on bad input.
	 * Returns 404 Not Found if building id not found.
	 * Returns 500 Internal Server Error on commit error.
	 */
	function putRoomUpdate($bid = null, $rid = null, Request $request){
		
		if($bid == null || trim($bid) == '')
			return new Response(null,400);
		if($num == null || trim($rid) == '')
			return new Response(null,400);
		
		try {
			$resource = Room::where('building_id','=',$bid)
				->where('id','=',$rid)->firstOrFail()->get();
		} catch(ModelNotFoundException $e) {
			return new Response(null,404);
		}
		
		$room_name = Input::json()->get('name');
		$room_des = Input::json()->get('description');
		
		$set_update = false;
		if(!is_null($room_name)){
			Room::where('building_id','=',$bid)
				->where('id','=',$rid)
				->update(array("name" => $room_name));
			$set_update = true;
		}
		
		if(!is_null($room_des)){
			Room::where('building_id','=',$bid)
				->where('id','=',$rid)
				->update(array("description" => $room_des));
			$set_update = true;
		}
		
		if($set_update) {
			if($resource->save()) {
				return new Response(null,200);
			} else {
				return new Response(null,500);
			}
		} else
			return new Response(null,304);
		} 
	}		
	///
	function deleteBuildings($rid = null){
		if($rid == null || trim($rid) =='')
			return new Response(null,404);
		$building_r = Building::find($rid);
		if(is_null($building_r))
			return new Response(null,400);
		$room_r = Room::where('building_id','=',$rid)->get();
		if(count($room_r) != 0){
			return new Response(null,400);
		}
		$affected_rows = Building::where('id','=',$rid)->delete();
		if($affected_rows)
			return new Response(null,200);
		else
			return new Response(null,500);
		
	}
	/* Delete methods for the room and building resources
		perform resource validation and deletion
	*/
	function deleteRooms($bid = null, $rid = null){
		if($rid == null || trim($rid) == '')
			return new Response(null,404);
		if($bid == null || trim($bid) == null)
			return new Response(null,404);
		$building_r = Building::find($bid);
		if(is_null($building_r))
			return new Response(null,400);
		$room_r = Room::where('id','=',$rid)
			->where('building_id','=',$bid)->get();
		if(count($room_r) == 0)
			return new Response(null,400);
		$check_in_item = CheckIn::where('room_id','=',$rid)->get();
		if(count($check_in_item) != 0)
			return new Response(null,400);
		$affected_rows = Room::where('id','=',$rid)->delete();
		if($affected_rows)
			return new Response(null,200);
		else
			return new Response(null,500);
	}

}
