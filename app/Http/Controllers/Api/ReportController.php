<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use App\Building;
use App\Room;
use App\CheckIn;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller {

	/**
	 * Retrieve the item that havn't been checked in by specified date
	 *
	 * @return Json/CSV unchecked items 
	 */
	public function getUnchecked()
	{
		
		//validation
		$rules = array(
			'format' => 'required|in:csv,json',
			'last-checkin' => 'required|date',
		);

		$validator=Validator::make(Input::all(),$rules);
		if ($validator->fails())
			return  Response::json('Invalid Request',400);

		
		//get url parameters
		$last_checkin = Input::get('last-checkin');
		$format = Input::get('format');

		//get the id(s) of items that have not been checked in yet
		$unchecked_ids=DB::table('check_ins')
						->groupby('item_id')
						->havingRaw('max(updated_at)<date(?)')
						->setBindings([$last_checkin])
						->lists('id');

		if ($format=='json')
		{
			//load report info from all models
			$unchecked=Building::with(['rooms.checkIns' => function($query)use($unchecked_ids)
			{
					$query->whereIn('id',$unchecked_ids);
					$query->with('item');

			}])->get();

			return $unchecked;
		}else
		{
			//join tables to flatten the result set
			$unchecked=DB::table('buildings')
				->join('rooms','buildings.id','=', 'rooms.building_id')
				->join('check_ins','rooms.id','=','check_ins.room_id')
				->join('items', 'check_ins.item_id','=','items.id')
				->whereIn('check_ins.id',$unchecked_ids)
				->select('buildings.name as building_name',
					    'rooms.name as room_name',
					    'items.name as item_name',
					    'items.asset_tag',
					    'check_ins.updated_at')
				->get();


				//build csv 
				$csvText = "";

				// build the header 
				$csvText .= 'building,'.'room,'.'item,'.'tag,'.'date' . "\n";
				
				// build the body
				foreach($unchecked as $row) 
				{

					$row_array = array();
			
					foreach($row as $col)
						$row_array[] = '"'.strtr($col, '"', '\"').'"';
					
					$csvText .= implode(',', array_values($row_array)) . "\n";
				}			
				
				header('Content-type: text/csv');
				header('Content-disposition: attachment;filename=report.csv');

			return $csvText;

		}
	}


	/**
	 * Retrive a listing of the item by type.
	 *
	 *  @return Json/CSV items information
	 */
	public function getItemsByType()
	{

		//validation
		$rules = array(
			'format' => 'required|in:csv,json',
			'type' => 'required',
		);

		$validator=Validator::make(Input::all(),$rules);
		if ($validator->fails())
			return  Response::json('Invalid Request',400);

		$type=Input::get('type');
		$format=Input::get('format');

		//get all types
		if(strtoupper($type)=='ALL')
			$type_ids=DB::table('item_types')
				->lists('id');
		else
			$type_ids=DB::table('item_types')
				->Where('name',$type)
				->lists('id');


		//get the ids of items that are checked in most recently
		$latest_ids=DB::table('check_ins')
			->select(DB::raw('max(id) as id'))
			->groupby('item_id')
			->lists('id');


		$items=DB::table('item_types')
			->join('items','item_types.id','=','items.item_type_id')
			->join('check_ins','check_ins.item_id','=','items.id')
			->join('rooms','rooms.id','=','check_ins.room_id')
			->join('buildings','buildings.id','=', 'rooms.building_id')
			->whereIn('check_ins.id',$latest_ids)
			->whereIn('item_types.id',$type_ids)
			->orderBy('items.id')
			->select('item_types.name as type',
				'items.name as item_name',
				'items.asset_tag',
				'buildings.name as building_name',
				'rooms.name as room_name',
				'check_ins.updated_at')
			->get();

		if ($format=='json')
			return $items;
		else
		{

			//build csv 
			$csvText = '';

			// build the header 
			$csvText .= 'type,'.'item,'.'tag,'.'building,'.'room,'.'date' . "\n";
			
			// build the body
			foreach($items as $row) 
			{

				$row_array = array();
		
				foreach($row as $col)
					$row_array[] = '"'.strtr($col, '"', '\"').'"';
				
				$csvText .= implode(',', array_values($row_array)) . "\n";
			}			
			
			header('Content-type: text/csv');
			header('Content-disposition: attachment;filename=report.csv');

			return $csvText;


		}

	}


}
