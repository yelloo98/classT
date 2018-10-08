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
        //# 제안서 관리
        Route::group(['prefix' => 'proposal'], function() {
            Route::get('/', 'ProposalController@index');                                    //# 목록
            Route::get('/detail/{id?}', 'ProposalController@detail');                       //# 상세
            Route::post('/save', 'ProposalController@saveDB');                              //# 제안서 update
            Route::get('/list', 'ProposalController@getList');                              //# 제안서 리스트 요청
            Route::get('/download/{id}', 'ProposalController@fileDownload');                //# 제안서 다운로드
            Route::get('/ajax/delProposalFile/{id}', 'ProposalController@delProposalFile'); //# 제안서 삭제
        });
        //# 협력사 관리
        Route::group(['prefix' => 'partner'], function() {
            Route::match(['get', 'post'], '/', 'PartnerController@index');                  //# 목록
            Route::get('/detail/{id?}', 'PartnerController@detail');                        //# 상세
            Route::group(['prefix' => 'ajax'], function(){
                Route::get('/delPartner/{id}', 'PartnerController@deleteDB');               //# delete
                Route::post('/savePartner', 'PartnerController@saveDB');                    //# save(insert or update)
                Route::get('/getPartner/{id}', 'PartnerController@selectPartnerOne');       //# select
            });
            Route::get('/list', 'PartnerController@getList');                               //# 협력사 명단 요청
        });
        //# 강의 분야
        Route::group(['prefix' => 'lecture'], function() {
            Route::get("/", 'LectureController@index');                                     //# 강의분야 목록
            Route::get("/list", "LectureController@getList");                               //# 강의분야 목록 요청
            Route::group(['prefix' => 'ajax'], function(){
                Route::get('/dellLecture/{id}', 'LectureController@deleteDB');              //# delete
                Route::post('/saveLecture', 'LectureController@saveDB');                    //# save(insert or update)
                Route::get('/getLecture/{id}', 'LectureController@selectLectureOne');       //# select one
                Route::get('/getLargeCate', 'LectureController@getLargeCateList');          //# 대분류 목록
                Route::get('/getMidCate/{largeCate}', 'LectureController@getMidCateList');  //# 중분류 목록
            });
        });
        //# 공지사항
        Route::group(['prefix' => 'notice'], function() {
            Route::match(['get', 'post'], '/', 'NoticeController@index');                   //# 목록
            Route::get('/detail/{id?}', 'NoticeController@detail');                         //# 상세
            Route::post('/save', 'NoticeController@saveDB');                                //# insert or update
            Route::get('/list', 'NoticeController@getList');                                //# 공지사항 목록 요청
        });
        //# 강의 후기
        Route::group(['prefix' => 'review'], function() {
            Route::group(['prefix' => 'student'], function() {                           //# 수강생평가
                Route::get('/', 'ReviewController@studentList');                         //# 리스트
                Route::get('/list', 'ReviewController@getEvaluateUserList');             //# 수강생평가 목록 요청
                Route::get('/detail/{id}', 'ReviewController@studentDetail');            //# 수강생 평가 상세 보기
            });
            Route::group(['prefix' => 'company'], function() {                           //# 기업평가
                Route::get('/', 'ReviewController@companyList');                         //# 리스트
                Route::get('/list', 'ReviewController@getEvaluateCompanyList');          //# 기업평가 목록 요청
                Route::get('/detail/{id}', 'ReviewController@companyDetail');            //# 상세보기
            });
        });
        //# 평가 항목
        Route::group(['prefix' => 'question'], function() {
            Route::get('/', 'QuestionController@index');                                 //# 관리 페이지로 이동
            Route::get('/basicList', 'QuestionController@getBasicList');                 //# 기본 평가 목록 요청
            Route::get('/oneList', 'QuestionController@getOneList');                     //# 일회성 평가 목록 요청
            Route::group(['prefix' => 'ajax'], function() {
                Route::get('/delQuestion/{id}', 'QuestionController@deleteDB');          //# 평가항목 삭제
                Route::post('/saveQuestion', 'QuestionController@saveDB');               //# 평가항목 insert or update
                Route::get('/getQuestion/{id}', 'QuestionController@selectQuestionOne'); //# 평가항목 1 row 요청
            });
        });
        //# 1:1 문의
        Route::group(['prefix' => 'qna'], function() {
            Route::match(['get', 'post'], '/', 'QnaController@index');                  //# 목록
            Route::get('/detail/{id}', 'QnaController@detail');                         //# 상세페이지
            Route::post('/save', 'QnaController@updateDB');                             //# 답변 저장
            Route::get('/list', 'QnaController@getList');                               //# datatable 목록 요청
        });
    });
});