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

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group([
    'prefix'     => 'api',
    'namespace'  => 'Api',
    'middleware' => 'auth.api'
], function () {
    Route::get('example', 'ExampleController@get');
    Route::post('example', 'ExampleController@post');
	//route for team6
	Route::get('/inventory/{tag}/history','CheckInController@getHistory');
	Route::get('/inventory/{tag}/history/latest','CheckInController@getHistoryLatest');
	Route::get('/inventory/{tag}/history/{num}','CheckInController@getHistoryByNum');
	Route::post('/inventory/{tag}/history','CheckInController@postHistory');
	//Facilities Management
	Route::get('/buildings/{buildingid}/rooms/{roomId}','FacilitiesManagementController@getRoomsItems');
	Route::get('/buildings/{id}/rooms/{roomId}/inventory','FacilitiesManagementController@getRoomsInventoryItems');
	Route::post('/buildings/','FacilitiesManagementController@postBuildingResource');
	Route::post('/buildings/{tag}/rooms','FacilitiesManagementController@postRoomResource');
	Route::put('/buildings/{tag}','FacilitiesManagementController@putBuildingUpdate');
	Route::put('/buildings/{tag}/rooms/{num}','FacilitiesManagementController@putRoomUpdate');
	

});

