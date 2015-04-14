<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Room;
use App\Building;
use App\CheckIn;
use App\ItemType;
use Illuminate\Http\Response;
use Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;

   

class FacilityManagementController extends Controller {

	function facility_management_post_for_building($tag = null){
			$name_ = Input::json()->get('name');
			$description_ = Input::json()->get('description');
			$building_item = Building::where('name','=',$name_)
					->where('description','=',$description_)->get();
			if(count($building_item)!=0){
					return new Response("resource already exist",200);#redirect("api/buildings/{$building_item[0]->id}");
			}
			$building = new Building;
			$building->name = $name_;
			$building->description = $description_;
			$building->save();
			
			return new Response("Ok",200);//redirect("api/buildings/{$building_item[0]->id}");
	}
	function facility_management_post_for_rooms($tag = null, Request $request){
			
			if($tag == null || trim($tag)=='')
				return Response(null,404);
			$room_name = Input::json()->get('name');
			$room_des = Input::json()->get('description');
			$building_id = Building::find($tag);
			if(empty($building_id))
				return Response("building id bad request",404);
			$record = Room::where("name","=",$room_name)
				->where("description","=",$room_des)
				->where("building_id","=",$tag)->get();
			if(count($record) != 0){
				return redirect("api/buildings/{$tag}/rooms/{$record[0]->id}");
			}
			$room = new Room;
			$room->name = $room_name;
			$room->description = $room_des;
			$room->building_id = $tag;
			$room->save();
			return new Response("Ok",200);//redirect("api/buildings/{$tag}/rooms/{$room->id}");
	
	}
	function facility_management_put_for_building($tag = null, Request $request){
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
	function facility_management_put_for_rooms($tag = null, $num = null, Request $request){
		
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
		}
		if(!is_null($room_des)){
			Room::where('building_id','=',$tag)
			->where('id','=',$num)->update(array("description" => $room_des));
		}
		if($set_update == true)
			$resource[0]->save();
		return new Response("OK",200); 
	}		
	function facility_management_delete_for_rooms($tag = null, $num = null, Request $response){
	
	}
	function facility_management_delete_for_buildings($tag = null,Response $request){
		
	}
}