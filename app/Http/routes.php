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


Route::group(['middleware' => ['normal']], function () {

    Route::get('test', function () {
        echo (86*20+90*20+81*30+95*30)/100;
//    \Illuminate\Support\Facades\DB::enableQueryLog();
//    echo "<pre>"; var_dump(\Illuminate\Support\Facades\DB::getQueryLog()); exit;
    })->name('test');

    Route::get('role/list', 'RoleController@listPage')->name(ROUTE_NAME_ROLE_LIST);
    Route::post('role/remove', 'RoleController@remove')->name(ROUTE_NAME_ROLE_REMOVE);
    Route::post('role/add', 'RoleController@add')->name(ROUTE_NAME_ROLE_ADD);
    Route::post('role/select', 'RoleController@select')->name(ROUTE_NAME_ROLE_SELECT);

    Route::get('project/apply', 'ProjectController@apply')->name(ROUTE_NAME_PROJECT_APPLY);
    Route::get('project/display/{id}', 'ProjectController@display')->name(ROUTE_NAME_PROJECT_DISPLAY);
    Route::get('project/list', 'ProjectController@listPage')->name(ROUTE_NAME_PROJECT_LIST);
    Route::post('project/create', 'ProjectController@create')->name(ROUTE_NAME_PROJECT_CREATE);

    Route::post('stage/invite', 'StageController@inviteDept')->name(ROUTE_NAME_STAGE_INVITE_DEPT);
    Route::post('stage/assignmaker', 'StageController@assignMaker')->name(ROUTE_NAME_STAGE_ASSIGN_MAKER);
    Route::post('stage/selectmode', 'StageController@selectMode')->name(ROUTE_NAME_STAGE_SELECT_MODE);
    Route::post('stage/pretrial', 'StageController@pretrial')->name(ROUTE_NAME_STAGE_PRETRIAL);
    Route::post('stage/passsign', 'StageController@passSign')->name(ROUTE_NAME_STAGE_PASS_SIGN);
    Route::post('stage/finishrecord', 'StageController@finishRecord')->name(ROUTE_NAME_STAGE_FINISH_RECORD);

    Route::post('assignmaker/add', 'AssignMakerController@add')->name(ROUTE_NAME_ASSIGN_MAKER_ADD);
    Route::post('assignmaker/remove', 'AssignMakerController@remove')->name(ROUTE_NAME_ASSIGN_MAKER_REMOVE);

    Route::get('score/edittemplate/{id}', 'ScoreController@editTemplate')->name(ROUTE_NAME_SCORE_EDIT_TEMPLATE);
    Route::post('score/selecttemplate', 'ScoreController@selectTemplate')->name(ROUTE_NAME_SCORE_SELECT_TEMPLATE);
    Route::post('score/commititems', 'ScoreController@commitItems')->name(ROUTE_NAME_SCORE_COMMIT_ITEMS);
    Route::get('score/page/{id}', 'ScoreController@scorePage')->name(ROUTE_NAME_SCORE_PAGE);
    Route::post('score/submit', 'ScoreController@submitScore')->name(ROUTE_NAME_SCORE_SUBMIT_SCORE);
    Route::get('score/overview/{id}', 'ScoreController@overview')->name(ROUTE_NAME_SCORE_SUBMIT_SCORE);

    Route::post('vendor/add', 'VendorController@add')->name(ROUTE_NAME_VENDOR_ADD);
    Route::post('vendor/remove', 'VendorController@remove')->name(ROUTE_NAME_VENDOR_REMOVE);

    Route::get('document/display/{id}/{name}', 'DocumentController@display')->name(ROUTE_NAME_DOCUMENT_DISPLAY);
    Route::post('document/upload', 'DocumentController@upload')->name(ROUTE_NAME_DOCUMENT_UPLOAD);

    Route::post('conversation/add', 'ConversationController@add')->name(ROUTE_NAME_CONVERSATION_ADD);
});

Route::group(['middleware' => ['welcome']], function () {

    Route::match(['get', 'post'], 'welcome', 'WelcomeController@index')->name(ROUTE_NAME_WELCOME);
});

