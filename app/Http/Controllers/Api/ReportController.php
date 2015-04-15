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



class ReportController extends Controller {



	/**
	 * Display the specified resource.
	 *
	 * @param  int  $tag
	 * @return Json found $item
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
						->lists('item_id');

		//load report info from all models
		$buildings=Building::with(['rooms.checkIns' => function($query)use($unchecked_ids)
		{
				$query->whereIn('item_id',$unchecked_ids);
				$query->with('item');

		}])->get();

		if ($format=='json')
			return $buildings;
		else
			#return $this->to_csv($buildings);


	}


}
