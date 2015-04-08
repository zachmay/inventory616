<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Room;
use App\Building;
use App\CheckIn;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Input;
use Request;
class CheckInController extends Controller {
    
    /**
     * GET /inventory/:tag/history
     * Retrieve a list of representations of the entire check-in 
     * history of the specified item. Supports JSON or CSV 
     * response format.
     * 
     * returns Response object
     */
	 
    function getHistory_($tag = null, Request $request) {
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
	
/**
 view me function
**/
 function view_me(){
 
	return view('post_method.show');
 }
	/**
     * POST /inventory/:tag/history
     * Create a new check-in resource for the specified option, 
	 based on the data in the request body (in particular, the room ID).
*/

function postHistory($tag=null, Request $request){
	if($tag == null || trim($tag) == '')
		return new Response(null,400);
	//if(!Request::isMethod('post'))
		//return Response(null,404);

	try{
		if(Input::has('room_id')){
			$room_id = Input::get('room_id');
			$checked_in_data = CheckIn::all();
			$item_type_table = DB::table('item_types')->where('name','=',Input::get('item_name'))->get();
			$projector_id = $item_type_table[0]['id'];
			srand(1);
			$set = 0;
			foreach($checked_in_data as $data){
				if($data->room_id == $room_id){
					$array_of_elem = Input::all();
					Item::create([
								 'asset_tag'          => $array_of_elem['asset_tag'],
								 'name'               => $array_of_elem['name'],
								 'funding_source'     => $array_of_elem['funding_source'],
								 'item_type_id'       => $projector->id,
								 'model'              => $array_of_elem['model'],
								 'administrator_flag' => $array_of_elem['administrator_flag'],
								 'teacher_flag'       => $array_of_elem['teacher_flag'],
								 'student_flag'       => $array_of_elem['student_flag'],
								 'institution_flag'   => $array_of_elem['institution_flag']
								 ]);
					CheckIn::create(['room_id' => $data->room_id,
									'item_id' => DB::table('items')->where('asset_tag','=',$array_of_elem['asset_tag'])->get()[0]['id'],
									'created_at' => rand(time()/2,time())]);
					$set = 1;
					break;
				}
			}
			if($set == 1)
				return View::make('response_view', array('name' => 'Successfully Updated information'));
			else
				return View::make('response_view', array('name' => 'Invalid Room Id'));
		}else{
			return View::make('response_view', array('name' => 'Provide necessary Room ID essential for performing Checked in'));
		}
	}catch(ModelNotFoundException $excep){
	  $response = new Response(null,400);
	}
		return $response;
	}
}
   
