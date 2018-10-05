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

    Route::group(['prefix' => 'login'] , function() {                       //# 로그인
        Route::get('/', 'LoginController@login');                           //# 로그인 화면
        Route::post('/', 'LoginController@loginProc');                      //# 로그인 처리
    });
    Route::get("/logout", 'LoginController@logout');                        //# 로그아웃

    Route::group(['middleware' => 'admin.auth'], function () {              //# 로그인 정보 체크
        Route::get("/main", "MainController@index");                        //# 메인 페이지
        //# 교육프로그램 관리
        Route::group(['prefix' => 'program'], function() {
            Route::get('/', "ProgramController@index");                     //# 목록
            Route::get('/detail/{id?}', 'ProgramController@detail');        //# 상세
            Route::post('/save/{id?}', 'ProgramController@saveDB');         //# insert or update or delete
            Route::get('/list', "ProgramController@getList");               //# 교육프로그램 리스트 요청
        });
        //# 강의 관리
        Route::group(['prefix' => 'class'], function() {
            Route::get('/', 'ClassController@index');                       //# 목록
            Route::get('/detail/{id}', 'ClassController@detail');           //# 상세
            Route::get('/list', "ClassController@getList");                 //# 강의 리스트 요청
            Route::post('/update', "ClassController@updateDB");             //# 강의 수정
        });

    });
});