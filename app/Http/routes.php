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
//	Route::get('/inventory/:tag/history','CheckInController@get');
	Route::get('/inventory/{tag?}/history',function($tag=null){
	echo $tag;


});
});



