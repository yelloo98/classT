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

Route::group(['namespace' => 'Api'], function () {
    Route::get('/schedule/{id}', 'ApiController@getScheduleData'); //# 강사 스케쥴
    Route::get('/recommendTeacherList/{requestId}/{status}', 'ApiController@getRecommendTeacherList'); //# 프리미엄 추천 강사 리스트 [ajax]
});
