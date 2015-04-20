<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Room;
use App\Building;
use App\CheckIn;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckInController extends Controller {
    
    /**
     * GET /inventory/:tag/history
     * 
     * Retrieve a list of representations of the entire check-in 
     * history of the specified item. Supports JSON or CSV 
     * response format.
     * 
     * Returns 200 OK on success.
     * Returns 400 Bad Request on bad input.
     * Returns 404 if asset_tag not found.
     * 
     */
    function getHistory($tag = null, Request $request) {
		// default to JSON format
		$format = 'json';
		
		if($request->has('format'))
			$format = $request->get('format');
		
		// if inadequate input, set response code to 400 bad request
		if($tag == null || trim($tag) == '' || !in_array($format,array('json','csv'))) 
			return new Response(null,400);
	
		try {
			// get the item with the specified asset tag
			$item = Item::where('asset_tag',$tag)->firstOrFail();
			
			// get the checkin history for the item and set response
			$checkins = CheckIn::where('item_id',$item->id)->orderBy('created_at','desc')->get();
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
     * 
     * Retrieves a representation of the specified checkin record.
     * 
     * Returns 200 OK on sucess.
     * Returns 400 Bad Request on bad input.
     * Returns 404 Not Found if record not found.
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
     * 
     * Finds the sequence number of the most recent check-in record 
     * for the specified inventory item and does an HTTP redirect to 
     * that URL.
     * 
     * Redirects to correct endpoint if sucess.
     * Returns 400 Bad Request on bad input.
     * Returns 404 Not Found if record not found.
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
     * POST /inventory/:tag/history
     * 
     * Create a new check-in resource for the specified option, 
	 * based on the data in the request body (in particular, the room ID).
	 * 
	 * Returns 200 OK on sucess.
     * Returns 400 Bad Request on bad input.
     * Returns 404 Not Found if record not found.
     * Returns 500 Internal Server Error if fails to commit.
     **/
	function postHistory($tag=null, Request $request){
		if($tag == null || trim($tag) == '')
			return new Response(null,400);
			
		$room_id = Input::json()->get('room_id');
		try {
			//$item = Item::where('id','=',$tag)->firstOrFail();
			
			// create the check in history
			//CheckIn::create([
			//				'room_id'	=> $room_,
			//				'item_id'	=> $item['id']
			//			]);
			// $arr = CheckIn::where('room_id','=',$room_)
			//		->where('item_id','=',$item['id']);
			//Item::findOrFail($room_id);
			
			$items = Item::where('asset_tag','=',$tag)->firstOrFail();
			if(count($items) == 0)
				return new Response(null,404);
			$rooms = Room::find($room_id);
			
			if(count($rooms) == 0)
				return new Response(null,400);
			$checkin = new CheckIn();
			$checkin->room_id = $room_id;
			$checkin->item_id = $items->id;
			
			if($checkin->save()) {
				return new Response(null,200);
			} else {
				return new Response(null,500);
			}		
			//$item_arr = Item::where('id','=',$item['id'])->get();
		}catch(ModelNotFoundException $e){
			return new Response(null,400);
		}
		return new Response(null,201);
	}


}
    
