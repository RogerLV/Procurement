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

Route::get('dummyEntry', function () {
    return view('dummyEntry');
});

Route::get('test', function () {
    $data = \App\Models\SystemRole::find(18);
    echo "<pre>"; var_dump($data);
});

Route::group(['middleware' => ['normal']], function () {
    Route::get('role/list', 'RoleController@listPage')->name(ROUTE_NAME_ROLE_LIST);
    Route::post('role/remove', 'RoleController@remove')->name(ROUTE_NAME_ROLE_REMOVE);
    Route::post('role/add', 'RoleController@add')->name(ROUTE_NAME_ROLE_ADD);
    Route::post('role/select', 'RoleController@select')->name(ROUTE_NAME_ROLE_SELECT);
});

Route::group(['middleware' => ['welcome']], function () {

    Route::match(['get', 'post'], 'welcome', 'WelcomeController@index')->name(ROUTE_NAME_WELCOME);
});

