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
function post_history_test(){
	if(Input::has('your_name')){
		echo 'yes it has the variable name';
		$my_name = 'Rios';
	}else
		$my_name = Input::get('firstnametext');
	$e_mail = Request::input('lastnametext');
	$arr = Request::all();
	$my_arr = array('my_name' => $my_name, 'email' => $e_mail);
	$csvText = "";
	$csvText .= implode(',',array_values($arr))."\n";
	return Response($arr,200);
}
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
function postHistory($tag=null, Request $request){

	if($tag == null || trim($tag) == '')
		return new Response(null,400);
	if(!Request::isMethod('post'))
		return Response(null,404);
	
	try{
		if(Input::has('room_id')){
			$array_of_elem = Input::all();
			$room_id = $array_of_elem['room_id'];
			$checked_in_data = CheckIn::all();
		
			try{
				$item_type_table = ItemType::where('name','=',Input::get('item_type'))->get();
			}catch(ModelNotFoundException $e){
				return Response("Need proper device name",404);
			}
			
			if(count($item_type_table) == 0){
				
				return Response("Need proper device name",404);
			}
			$device_id = $item_type_table[0];
			
			srand(1);
			$setting = 0;
			$check_each_items = $this->check_each_item($array_of_elem);
			if($check_each_items == 0){
				//dd($check_each_items);
				return Response("Missing Item",404);
			}
			foreach($checked_in_data as $data){
			
				$data = $data->toArray();
				if($data['room_id'] == $room_id){
					$element_exist = Item::where('asset_tag','=',$array_of_elem['asset_tag'])->get();
					if(count($element_exist)!=0){
					  return Response("Data with similar tag name already exist",404);
					}
			
					if($array_of_elem['item_type'] == 'Computer'){
						try{
						       Item::create([
										'asset_tag'          => $array_of_elem['asset_tag'],
										'name'               => $array_of_elem['item_name'],
										'funding_source'     => $array_of_elem['funding_source'],
										'item_type_id'       => (int)$device_id->id,
										'model'              => $array_of_elem['model'],
										'cpu'                => $array_of_elem['cpu'],
										'ram'                => $array_of_elem['ram'],
										'hard_disk'          => $array_of_elem['hard_disk'],
										'os'                 => $array_of_elem['os'],
										'administrator_flag' => (bool)$array_of_elem['administrator_flag'],
										'teacher_flag'       => (bool)$array_of_elem['teacher_flag'],
										'student_flag'       => (bool)$array_of_elem['student_flag'],
										'institution_flag'   => (bool)$array_of_elem['institution_flag']
								]);
						}catch ( Illuminate\Database\QueryException $e) {
								var_dump($e->errorInfo );
						}catch(Exception $ex){
							return Response("duplicate asset tag",404 );
						}
					}else{
						Item::create(['asset_tag'          => $array_of_elem['asset_tag'],
								 'name'               => $array_of_elem['item_name'],
								 'funding_source'     => $array_of_elem['funding_source'],
								 'item_type_id'       => $device_id,
								 'model'              => $array_of_elem['model'],
								 'administrator_flag' => $array_of_elem['administrator_flag'],
								 'teacher_flag'       => $array_of_elem['teacher_flag'],
								 'student_flag'       => $array_of_elem['student_flag'],
								 'institution_flag'   => $array_of_elem['institution_flag']
								 ]);
					
					}
					CheckIn::create(['room_id' => (int)$data['room_id'],
									'item_id' => (int)Item::where('asset_tag','=',$array_of_elem['asset_tag'])
									->get()[0]['id'],
									'created_at' => rand(time()/2,time())]);
					$setting = 1;
					break;
				}
				//dd($data['room_id']);
			}
			if($setting == 1)
				return Response("Successfully Created Checked In Item",200);
			else
				return Response("Invalid Room ID",404);
		}else{
			dd("return a view");
			return View::make('response_view', array('name' => 'Provide necessary Room ID essential for performing Checked in'));
		}
	}catch(ModelNotFoundException $excep){
	  $response = new Response(null,400);
	}
		return $response;
	}
}
   
