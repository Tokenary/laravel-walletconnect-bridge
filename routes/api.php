<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'session'], function () : void {
    Route::post('new', 'SessionController@store');
    Route::put('{session}', 'SessionController@update');
    Route::get('{session}', 'SessionController@show');
    Route::delete('{session}', 'SessionController@destroy');

    Route::group(['prefix' => '{session}'], function () : void {
        Route::get('/call/{call}', 'CallController@show');
        Route::post('/call/new', 'CallController@store');
        Route::get('/calls', 'CallController@index');
    });
});

Route::post('/call-status/{call}/new', 'CallController@update');
Route::post('/call-status/{call}', 'CallController@update');
Route::put('/call-status/{call}', 'CallController@update');
Route::get('/call-status/{call}', 'CallController@status');
