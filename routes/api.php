<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Users
Route::group(['middleware' => ['auth:api']], function () {

    //Route::post('session_capture', 'admin\SessionsController@session_capture')->middleware('permission:SESSIONS-capture|SESSIONS-capture-own-session');

});
