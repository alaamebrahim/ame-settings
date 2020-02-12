<?php

Route::group([
    'prefix' => 'setting',
    'namespace' => '\Alaame\Setting\Http\Controllers',
    'as' => 'setting.'
], function (){
    Route::get('/',                   ['uses' => 'SettingController@index', 'as' => 'index']);
    Route::post('/',                  ['uses' => 'SettingController@store', 'as' => 'store']);
    Route::put('/',                   ['uses' => 'SettingController@update', 'as' => 'update']);
    Route::delete('{id}',             ['uses' => 'SettingController@destroy', 'as' => 'delete']);
    Route::get('{id}/move_up',        ['uses' => 'SettingController@move_up', 'as' => 'move_up']);
    Route::get('{id}/move_down',      ['uses' => 'SettingController@move_down', 'as' => 'move_down']);
    Route::get('{id}/delete_value',   ['uses' => 'SettingController@delete_value', 'as' => 'delete_value']);
});
