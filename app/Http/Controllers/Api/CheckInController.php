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

class CheckInController extends Controller {
    
    /**
     * GET /inventory/:tag/history
     * Retrieve a list of representations of the entire check-in 
     * history of the specified item. Supports JSON or CSV 
     * response format.
     * 
     * returns Response object
     */
    function getHistory($tag = null, Request $request) {
		// default to JSON format
		$format = 'json';
		
		//if($request->has('format'))
			//$format = $request->get('format');
		
		// if inadequate input, set response code to 400 bad request
		if($tag == null || trim($tag) == '' || !in_array($format,array('json','csv'))) 
			return new Response(null,400);
	
		try {
			// get the item with the specified asset tag
			$item = Item::where('asset_tag',$tag)->firstOrFail();
			
			// get the checkin history for the item and set response
			$checkins = CheckIn::where('item_id',$item->item_type_id)->get();
			if($format == 'json') {
				$response = new Response($checkins,200);
			} else {
				// build the CSV data and set response
				$csvText = "";
				$wroteHeader = false;
				foreach($checkins as $value) {
					$value = $value->toArray();
					
					// build the header first
					if(!$wroteHeader) {
						$csvText .= implode(',',array_keys($value)) . "\n";
						$wroteHeader = true;
					}
					
					$csvText .= implode(',',array_values($value)) . "\n";
				}
				
				$response = new Response($csvText,200);
				$response->header('Content-Type','text/csv');
			}
		} catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
			$response = new Response(null,404);
		}
		
		return $response;
	}
    
    /**
     * GET /inventory/:tag/history/:num
     * Retreives a representation of the specified checkin record.
     * 
     * returns Response object
     */
    function getHistoryByNum($tag = null, $num = null, Request $request) {
		// if inadequate input, set response code to 400 bad request
		if($tag == null || trim($tag) == '' || $num == null || !ctype_digit($num)) 
			return new Response(null,400);
		
		try {
			// get the item with the specified asset tag
			$item = Item::where('asset_tag',$tag)->firstOrFail();		
			// get the checkin entry for the item with given sequence number
			$checkin = CheckIn::where('id',$num)->firstOrFail();
			
			// get the building and room details
			$room = Room::where('id',$checkin->room_id)->firstOrFail();
			$building = Building::where('id',$room->building_id)->firstOrFail();
			
			// merge details with checkin object as part of response
			$checkin->room_name = $room->name;
			$checkin->room_description = $room->description;
			$checkin->building_id = $building->id;
			$checkin->building_name = $building->name;
			$checkin->building_description = $building->description;
			
			$response = new Response($checkin,200);
		} catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
			$response = new Response(null,404);
		}
		
		return $response;
	}
    
    /**
     * GET /inventory/:tag/history/latest
     * Finds the sequence number of the most recent check-in record 
     * for the specified inventory item and does an HTTP redirect to 
     * that URL.
     * 
     * returns Response object
     */
    function getHistoryLatest($tag = null, Request $request) {

		// if inadequate input, set response code to 400 bad request
		if($tag == null || trim($tag) == '') 
			return new Response(null,400);
	
		try {
			// get the item with the specified asset tag
			$item = Item::where('asset_tag',$tag)->firstOrFail();
			
			// get the checkin history for the item
			$checkins = CheckIn::where('item_id',$item->id);
			
			// get the latest checkin entry
			$latest = $checkins->orderBy('created_at','desc')->firstOrFail();
			
			// redirect to the latest checkin
			$dest = "/api/inventory/{$tag}/history/{$latest->id}";
			return redirect($dest);
			
		} catch(ModelNotFoundException $e) {
			// if not found, set response code to 404 not found
			$response = new Response(null,404);
		}
		
		return $response;
	}
	
	/**
	view_me  method for operation purpose
	**/
function view_me(){
	return view('post_method.show');
}
/**
     * POST /inventory/:tag/history
     * Create a new check-in resource for the specified option, 
	 based on the data in the request body (in particular, the room ID).
*/

public function check_each_item($elements){
	$item_comp = array('asset_tag','item_type','item_name','funding_source','model','cpu','ram','hard_disk','os','administrator_flag','teacher_flag','student_flag','institution_flag');
	$item_else = array('asset_tag','item_type','item_name','funding_source','model','administrator_flag','teacher_flag','student_flag','institution_flag');
	if($elements['item_type'] == 'Computer'){
		foreach($item_comp as $comp_item){
			if(in_array($comp_item,array_keys($elements))== false){
				//dd($elements['asset_tag']);
				return 0;
			}
		}
	}else{
		foreach($item_else as $else_item){
			if(in_array($else_item,array_keys($elements))==false){
				dd("returning 0");
				return 0;
			}
		}
	}
	
	return 1;
}
function room_id_in_table($arr,$room_id){
	foreach($arr as $items){
		if($items['id'] == $room_id)
			return 1; 	
	}
	return 0;
}
function postHistory($tag=null, Request $request){
	
	//if(!Request::isMethod('post'))
	//	return Response(null,404);
	if($tag == null || trim($tag) == '')
		return new Response(null,400);
	$room_ = Input::json()->get('room_id');
	$room_arr = Room::where('id','=',$room_)->get();
	$items = Item::where('id','=',$tag)->get();
	if(count($items) == 0)
		return new Response("Invalid Item",404);
	if(count($room_arr) == 0)
		return new Response("Bad Request",400);
	
	try{
		// create the check in history
		CheckIn::create([
						'room_id'	=> $room_,
						'item_id'	=> $items[0]['id']
						//'created_at' => rand(time()/2,time())
					]);
		 $arr = CheckIn::where('room_id','=',$room_)
				->where('item_id','=',$items[0]['id']);
		 $item_arr = Item::where('id','=',$items[0]['id'])->get();
		$dest = "/api/inventory/{$item_arr[0]['asset_tag']}/history/latest";
		return redirect($dest);
	}catch(ModelNotFoundException $excep){
		return new Response("something bad happened",400);
	}
	
  }
}
   
