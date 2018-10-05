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
    //# 로그인 정보 체크
    Route::group(['middleware' => 'admin.auth'], function () {
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
        //# 프리미엄 의뢰
        Route::group(['prefix' => 'request'], function() {
            Route::match(['get', 'post'], '/', 'RequestController@index');  //# 목록
            Route::get('/detail/{id}', 'RequestController@detail');         //# 상세
            Route::post('/update', 'RequestController@updateDB');           //# 수정(강사 id update, 추천강사 insert)
            Route::get('/list', "RequestController@getList");               //# 프리미엄 의뢰 리스트 요청
            //# 강사 찾기
            Route::group(['prefix' => 'ajax'], function() {
                Route::get('/searchTeacher/{id}', 'RequestController@searchTeacher');                       //# 강사 찾기
                Route::get('/delRecommendTeacher/{recommendId}', 'RequestController@delRecommendTeacher');  //# 추천강사 삭제하기
                Route::post('/insertRecommendTeacher', 'RequestController@insertRecommendTeacher');         //# 추천강사 등록
            });
        });

    });
});