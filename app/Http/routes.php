<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::group([
    'prefix'     => 'api',
    'namespace'  => 'Api',
    'middleware' => 'auth.api'
], function () {
    Route::get('example', 'ExampleController@get');
    Route::post('example', 'ExampleController@post');
	//route for team6	
	Route::get('/inventory/view','CheckInController@view_me');
	Route::post('/inventory/{tag}/history','CheckInController@postHistory');
	Route::get('inventory/{tag}/history','CheckInController@getHistory');
	Route::get('/inventory/{tag}/history/latest','CheckInController@getHistoryLatest');
	Route::get('/inventory/{tag}/history/{num}','CheckInController@getHistoryByNum');
	//for facility management
	Route::post('/buildings/','FacilityManagementController@facility_management_post_for_building');
	Route::put('/buildings/{tag}','FacilityManagementController@facility_management_put_for_building');
	Route::put('/buildings/{tag}/rooms/{num}','FacilityManagementController@facility_management_put_for_rooms');
	Route::post('/buildings/{tag}/rooms','FacilityManagementController@facility_management_post_for_rooms');
	//Route::delete('/buildings/{tag}','FacilityManagementController@facility_management_delete_for_buildings');
	//Route::delete('/buildings/{tag}/rooms/{num}','FacilityManagementController@facility_management_delete_for_rooms');
	//Route::delete('buildings/{tag}/');
	
});
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);





