<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportsController2 extends Controller {

        /**
         * Display a listing of the resource.
         *
         * @return Response
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


                //get the type and format fro the url   
                $type=Input::get('type');
                $format=Input::get('format');


                //user wants items of all types to be returned
                if(strtoupper($type)=='ALL') {
                    $items=Item::groupby('name')->get();

                }  else { //user only wants items of specific type to be returned

                    $items=Item::groupby('name')
                                ->having('name','=',$type)
                                ->get();

                }
                                      
 
                //User wants the output to be json format
                if($format=='json'){
                        return $items;

                } else {//user wants the output to be csv format

                        $hasHeader = false;
                        $csvOutput = "";

                        foreach($items as $item) {


                                //build the header
                                if(!$hasHeader) {
                                    $csvOutput .= implode(',',  array_keys($item))."\n";
                                    $hasHeader = true;

                                }


                                //build the body
                                $csvOutput .= implode(',', array_values($item))."\n";

                        } 
                        return $csvOutput;

                }


        }
  

}