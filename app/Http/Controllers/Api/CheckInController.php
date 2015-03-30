<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Room;
use App\Building;
use App\CheckIn;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
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
		
		if($request->has('format'))
			$format = $request->get('format');
		
		// if inadequate input, set response code to 400 bad request
		if($tag == null || trim($tag) == '' || !in_array($format,array('json','csv'))) 
			return new Response(null,400);
	
		try {
			// get the item with the specified asset tag
			$item = Item::where('asset_tag',$tag)->firstOrFail();
			
			// get the checkin history for the item and set response
			$checkins = CheckIn::where('item_id',$item->id)->get();
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

}
