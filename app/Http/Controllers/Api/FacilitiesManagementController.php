<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Room;
use App\Building;
use App\CheckIn;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacilitiesManagementController extends Controller {

	//get collection of items from building id and room id
	function getRoomsItems($Bid = null, $RoomId = null, Request $Request) {
			
			
			if($Bid == null || trim($Bid) == '' || $RoomId =='' || trim($RoomId) =='')
				return new Response(null,400);
			try{

				//$result = Room::select('id','building_id')->get();
				//$response = new Response($result,200);
				//return $response;
				//testing with bid = 13, item = 11
				$result = Room::where('id',$RoomId)->where('building_id',$Bid)->get();
				if($result->count() > 0){
					$items = CheckIn::where('room_id',$RoomId)->get();
					$response = new Response($items,200);
				}
				else{
					$response = new Response("No result",200);
				}
			} catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
				$response = new Response(null,404);
			}
			return $response;
	}


	function getRoomsInventoryItems($id=null,$RoomId,Request $Request){
		echo"implemenet this ";
	}


}