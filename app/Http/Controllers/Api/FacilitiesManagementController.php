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

	/* GET /buildings/:id/rooms/:roomid/inventory
	 * 
	 * Returns a collection of items for the given building and room id.
	 * 
	 * Returns 200 OK on success.
	 * Returns 400 Bad Request on bad input.
	 * Returns 404 Not Found if building id or room id not found.
	 */ 
	function getRoomsInventoryItems($Bid = null, $RoomId = null) {
			
		if($Bid == null || trim($Bid) == '' || $RoomId =='' || trim($RoomId) =='') //check if Building Id Or RoomId is null/empty, if so return 400 error
			return new Response(null,400);
		try{

			$result = Room::where('id',$RoomId)	//get rooms where building id = $bid
				->where('building_id',$Bid)->firstOrFail();

			//if($result->count() > 0){ //if result is not zero
				$itemIdArray = checkIn::select()->where('room_id',$RoomId)->get(); //get rooms from check in table
				$items = array(); //convert to array
				foreach($itemIdArray as $value){ //get each item info
					$tempId = $value["item_id"]; //get item id
					$items[] = Item::where('id',$tempId)->firstOrFail(); //get item info
				}

				$response = new Response($items,200);
			//}

		} catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
			$response = new Response(null,404);
		}
		return $response;
	}

	/* GET /buildings/:buildingid/rooms/:roomid
	 * 
	 * An individual room resource associated with the specified 
	 * building resource.
	 * 
	 * Returns 200 OK on success.
	 * Returns 400 Bad Request on bad input.
	 * Returns 404 Not Found if building id or room id not found.
	 */
	function getRoomsResource($Bid=null, $RoomId=null){
		

			if($Bid == null || trim($Bid) == '' || $RoomId =='' || trim($RoomId) =='') //rneturn 400 errors if $Bid or $RoomId is empty/null
				return new Response(null,400);

			try{
				//testing with bid = 13, item = 11
				$RoomResource = Room::where('id',$RoomId)->where('building_id',$Bid)->firstOrFail(); //get rooms info from building id and room id
				$response = new Response($RoomResource,200); //return successful

			}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404); 
			}
			return $response;

	}

	
	/* GET /buildings
	 * 
	 * Retrieve a listing of all existing building resources. Should 
	 * support a query parameter "search" that accepts a string by 
	 * which results can be filtered.
	 * 
	 * Returns 200 OK on success.
	 * 
	 * TODO: Remove not found 404. Should just be empty list.
	 * 
	 */ 
	function getBuildingResource(Request $request){

			$SearchString = $request->input('query'); //get query string

			try{
				if(is_null($SearchString)){ //if query is empty

					$BuildingResource = Building::select()->get();
				}
				else{ //else filter the result

					$BuildingResource = Building::where('name','LIKE','%'.$SearchString.'%') //get building based on building name or description
											    ->orWhere('description','LIKE','%'.$SearchString.'%')->get();
				}

				$response = new Response($BuildingResource,200); //return successful

			}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
			}
			return $response;

	}

	/* GET /buildings/:id
	 * 
	 * Retrieve a representation of the specified resource.
	 * 
	 * Returns 200 OK on success.
	 * Returns 400 Bad Request if bad input..
	 * Returns 404 Not Found if building id not found.
	 */ 
	function getBuildingResourceOnId($Bid = null){

		if($Bid == null || trim($Bid) == '')  //return 400 error if $Bid is null empty
				return new Response(null,400);

		try{

				$BuildingResource = Building::where('id',$Bid)->firstOrFail(); //get build id and info
				$response = new Response($BuildingResource,200);

		}catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
		}
		return $response;
	}


	/* GET /buildings/:id/rooms
	 * Retrieve a listing of all existing room resources associated 
	 * with the specified building. Should support a query parameter 
	 * "search" that accepts a string by which results can be filtered.
	 * 
	 * Returns 200 OK 
	 * 
	 */ 
	function getBuildingRoomResource($Bid=null,Request $request){

		if($Bid == null || trim($Bid) == '') //return 400 error if $Bid is empty/null 
				return new Response(null,400);

		try{	
				$SearchString = $request->input('query'); //get query for filtering

				if(!is_null($SearchString)){ //if it's not empty
					$Building = Room::where('building_id',$Bid); //filter the result based on name and description
					$RoomResource = $Building->where('name','LIKE','%'.$SearchString.'%')
									         ->orWhere('description','LIKE','%'.$SearchString.'%')->get();
				}
				else
					$RoomResource = Room::where('building_id',$Bid)->get(); //return all
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
	 * 
	 * Returns 200 OK on success.
	 * Returns 304 Not Modified if no changes made.
	 * Returns 400 Bad Request on bad input.
	 * Returns 404 Not Found if building id not found.
	 * Returns 500 Internal Server Error on commit error.
	 */
	function putBuildingUpdate($bid = null, Request $request){
	    if($bid == null || trim($bid) == '')
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
		if($rid == null || trim($rid) == '')
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
			//if($resource->save()) {
				return new Response(null,200);
		//	} else {
			//	return new Response(null,500);
			//}
		} else {
			return new Response(null,304);
		} 
	}
	
	/* DELETE /buildings/:id
     * 
     * Deletes the building resource with the given building id.
     * 
     * Returns 200 OK on success.
     * Returns 400 Bad Request on bad input.
     * Returns 404 Not Found if buildingid or roomid does not exist.
     * Returns 500 Internal Server Error.
     */ 
	function deleteBuildings($bid = null){
		if($bid == null || trim($bid) =='')
			return new Response(null,400);
		$building_r = Building::find($bid);
		if(is_null($building_r))
			return new Response(null,404);
		$room_r = Room::where('building_id','=',$bid)->get();
		if(count($room_r) != 0){
			return new Response(null,400);
		}
		$affected_rows = Building::where('id','=',$bid)->delete();
		if($affected_rows)
			return new Response(null,200);
		else
			return new Response(null,500);
		
	}
	
	/* DELETE /buildings/:buildingid/rooms/:roomid
     * 
     * Deletes the room resource with the given roomid and is 
     * associated with the given buildingid.
     * 
     * Returns 200 OK on success.
     * Returns 400 Bad Request on bad input.
     * Returns 404 Not Found if buildingid or roomid does not exist.
     * Returns 500 Internal Server Error.
     */ 
	function deleteRooms($bid = null, $rid = null){
		if($rid == null || trim($rid) == '')
			return new Response(null,400);
		if($bid == null || trim($bid) == null)
			return new Response(null,400);
		$building_r = Building::find($bid);
		if(is_null($building_r))
			return new Response(null,404);
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
