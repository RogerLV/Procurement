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
    $doc = \App\Models\ProjectStageLog::with('operator')->find(1);
    echo "<pre>"; var_dump($doc->operator);
})->name('test');

Route::group(['middleware' => ['normal']], function () {
    Route::get('role/list', 'RoleController@listPage')->name(ROUTE_NAME_ROLE_LIST);
    Route::post('role/remove', 'RoleController@remove')->name(ROUTE_NAME_ROLE_REMOVE);
    Route::post('role/add', 'RoleController@add')->name(ROUTE_NAME_ROLE_ADD);
    Route::post('role/select', 'RoleController@select')->name(ROUTE_NAME_ROLE_SELECT);

    Route::get('project/apply', 'ProjectController@apply')->name(ROUTE_NAME_PROJECT_APPLY);
    Route::get('project/display/{id}', 'ProjectController@display')->name(ROUTE_NAME_PROJECT_DISPLAY);
    Route::get('project/list', 'ProjectController@listPage')->name(ROUTE_NAME_PROJECT_LIST);
    Route::post('project/create', 'ProjectController@create')->name(ROUTE_NAME_PROJECT_CREATE);

//    Route::post('stage/init', 'StageController@initiate')->name(ROUTE_NAME_STAGE_INITIATE);
    Route::post('stage/invite', 'StageController@inviteDept')->name(ROUTE_NAME_STAGE_INVITE_DEPT);

    Route::get('document/display/{id}/{name}', 'DocumentController@display')->name(ROUTE_NAME_DOCUMENT_DISPLAY);
    Route::post('document/upload', 'DocumentController@upload')->name(ROUTE_NAME_DOCUMENT_UPLOAD);

    Route::post('conversation/add', 'ConversationController@add')->name(ROUTE_NAME_CONVERSATION_ADD);
});

Route::group(['middleware' => ['welcome']], function () {

    Route::match(['get', 'post'], 'welcome', 'WelcomeController@index')->name(ROUTE_NAME_WELCOME);
});

