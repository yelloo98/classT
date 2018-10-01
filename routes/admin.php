<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['namespace' => 'Admin', 'middleware' => 'adminWeb'], function () {

    Route::group(['prefix' => 'login'] , function() { //로그인
        Route::get('/', 'LoginController@login'); //로그인 화면
        Route::post('/', 'LoginController@loginProc'); //로그인 처리
    });
    Route::get("/logout", 'LoginController@logout'); //로그아웃

    Route::group(['middleware' => 'admin.auth'], function () { //# 로그인 정보 체크
        Route::get("/main", "MainController@index");                    //메인 페이지
    });
});