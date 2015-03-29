<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\CheckIn;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckInController extends Controller {
    
    /**
     * GET /inventory/:tag/history
     * Retrieve a list of representations of the entire check-in 
     * history of the specified item. Should support JSON or CSV 
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
			$item = Item::where('asset_tag','=',$tag)->firstOrFail();
			
			// get the checkin history for the item and set response
			$checkins = CheckIn::where('item_id','=',$item->id)->get();
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
     * GET /inventory/:tag/history/latest
     * Finds the sequence number of the most recent check-in record 
     * for the specified inventory item and does an HTTP redirect to 
     * that URL.
     * 
     * returns Response object
     */
    function getHistoryLatest($tag = null) {

	}

}
