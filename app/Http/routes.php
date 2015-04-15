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

//Route::get('/', 'HomeController@index');

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

    #add by peng@team4
    Route::get('inventory','InventoryCollectionController@index');
    Route::post('inventory','InventoryCollectionController@store');
    Route::get('inventory/{tag}','InventoryController2@show');
    Route::put('inventory/{tag}','InventoryController2@update');
    Route::delete('inventory/{tag}','InventoryController2@destroy');

    Route::get('report/reconciliation','ReportController@getUnchecked');
    Route::get('report/by-type','ReportController2@getItemByType');

    Route::get('inventory-types','ItemTypeController@index');

    });

Event::listen('illuminate.query', function($sql)
{
    var_dump($sql);
});

