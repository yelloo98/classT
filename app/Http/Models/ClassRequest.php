<?php

namespace App\Http\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ClassRequest extends Model
{
    //프리미엄 요청 테이블

    use SoftDeletes;

    protected $table = 'class_request';

    //기업 회원
    function companyUser() {
        return $this->hasOne( 'App\Http\Models\UsersCompany', 'id', 'users_company_id');
    }

    //기업 회원
    function teacherUser() {
        return $this->hasOne( 'App\Http\Models\UsersTeacher', 'id', 'users_teacher_id');
    }

	//추천 강사 회원
	function recommendTeacherUser() {
		return $this->hasMany( 'App\Http\Models\ClassRecommend', 'class_request_id','id');
	}

    //##################################################################################################################
    //##
    //## >> Data Table List ==> 강의 관리 목록
    //##
    //##################################################################################################################
    public function classDataTableList($request) {
        //### 데이터 조회
        $data = $this->select('class_request.id',
            'class_request.class_title AS title',
            'class_request.created_at',
            'class_request.request_status',
            'users_company.name AS company_name',
            'users_teacher.name AS teacher_name',
            'users_teacher.email AS teacher_email',
            DB::raw('IF(class_request.class_pay_type = "time", class_request.class_time_pay, class_request.class_count_pay) AS class_pay')
            )->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');


        //### 검색조건
        //#전체 검색 정보
        if(($searchAll = $request->input('search')['value']) != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('class_title', 'like', "%".$searchAll."%")->orwhere('users_teacher.name', 'like', "%".$searchAll."%")->orwhere('users_company.name', 'like', "%".$searchAll."%");
            });
        }else { //형태 체크박스 하나라도 선택한 경우
            $searchTitle = $request->input('columns')[1]['search']['value'];
            $searchTeacher = $request->input('columns')[2]['search']['value'];
            $searchCompany = $request->input('columns')[3]['search']['value'];
            $data->where(function ($query) use ($searchTitle, $searchTeacher, $searchCompany) {
                if($searchTitle != "" && $searchTitle != null) { //# 강의명 검색 정보
                    $query = $query->orwhere('class_request.class_title', 'like', "%".$searchTitle."%");
                }
                if($searchTeacher != "" && $searchTeacher != null) { //#강사명 검색정보
                    $query = $query->orwhere('users_teacher.name', 'like', "%".$searchTeacher."%");
                }
                if($searchCompany != "" && $searchCompany != null) { //#기업명 검색 정보
                    $query = $query->orwhere('users_company.company_name', 'like', "%".$searchCompany."%");
                }
            });
        }

        //# 상태 검색 정보
        $searchStatus = $request->input('columns')[4]['search']['value'];
        if($searchStatus!=null && $searchStatus!="all") {
            $data->where(DB::raw("(select IFNULL(MAX(class_recommend.status), 1) from class_recommend where class_recommend.class_request_id = class_request.id)"), '=', $searchStatus);
        }
        
        //요청 날짜 비교
        if($request->input('columns')[5]['search']['value'] != "" && $request->input('columns')[6]['search']['value']!="") {
            $dataArr = array($request->input('columns')[5]['search']['value'].' 00:00:00', $request->input('columns')[6]['search']['value'].' 23:59:59');
            $data->whereBetween('class_request.created_at', $dataArr);
        }

        $data->where('class_request.request_type', '=', 'company'); //요청타입이 기업인 것만

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

	    //### 강의관리 모든 리스트 및 현황
	    $titalData = $this->where('request_type', '=', 'company')->get();
	    $score['totalCnt']       = $titalData->count();
	    $score['registerCnt']    = $titalData->where('request_status','register')->count();
	    $score['p_requestCnt']   = $titalData->where('request_status','p_request')->count();
	    $score['p_revirwCnt']    = $titalData->where('request_status','p_revirw')->count();
	    $score['p_sendCnt']      = $titalData->where('request_status','p_send')->count();
	    $score['c_requestCnt']   = $titalData->where('request_status','c_request')->count();
	    $score['c_callCnt']      = $titalData->where('request_status','c_call')->count();
	    $score['c_confirmCnt']   = $titalData->where('request_status','c_confirm')->count();
	    $score['c_payment']      = $titalData->where('order_id','!=','')->count();

	    $json['score'] = $score;

        //### 페이징 데이터 정보
        $json['draw']            = $request->input('draw');
        $json['recordsTotal']    = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();

	    //### 리스트 정보 추가
	    $requestData = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();
	    foreach ($requestData as $item) {
	    	//# 강의 상태값 추가
            $item->request_status_str = recommendStatus( (new ClassRecommend())::select(DB::raw("IFNULL(MAX(status),1) AS status"))->where('class_request_id', '=', $item->id)->whereNull('refuse_type')->first()->status); //의뢰 진행상태

        	$teacherlist = $item->recommendTeacherUser()->get();

	        foreach ($teacherlist  as $info) {
		        $teacherInfo = $info->teacherUser()->first();
		        $info['teacher_name'] = $teacherInfo->name;
		        $info['teacher_email'] = $teacherInfo->email;
		        $info['teacher_time_pay'] = $teacherInfo->time_pay?$teacherInfo->time_pay:0;
		        $info['teachercount_pay'] = $teacherInfo->time_count_pay?$teacherInfo->time_count_pay:0;
		        $info['status'] = ($info['refuse_type'] == null) ? recommendStatus($info['status']) : '거절'; //추천강사 의뢰상태
	        }
        	$item['teacher_list'] = $teacherlist;
        }

	    $json['data'] = $requestData;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data Table List ==> 관리자 프리미엄 의뢰 목록
    //##
    //@param $type : 가져오는 타입 - contact : 프리미엄 의뢰 연결 - 강사 회원 상세페이지에서 사용하는 경우 아직 강사와 연결되지 않은 요청 행들만 반환
    //##################################################################################################################
    public function requestDataTableList($request, $type = '', $teacherId=0) {
        //### 데이터 조회
        $data = $this->select('class_request.id',
            'class_request.class_title AS title',
            'class_request.class_deadline',
            'class_request.request_status',
            'class_request.created_at',
            'users_teacher.name AS teacher_name',
            'users_teacher.email AS teacher_email',
            'users_company.name AS company_name')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        //### 검색조건
        //#전체 검색 정보
        if(($searchAll = $request->input('search')['value']) != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('class_title', 'like', "%".$searchAll."%")->orwhere('users_teacher.name', 'like', "%".$searchAll."%")->orwhere('users_company.name', 'like', "%".$searchAll."%");
            });
        }else { //형태 체크박스 하나라도 선택한 경우
            $searchTitle = $request->input('columns')[1]['search']['value'];
            $searchTeacher = $request->input('columns')[2]['search']['value'];
            $searchCompany = $request->input('columns')[3]['search']['value'];
            $data->where(function ($query) use ($searchTitle, $searchTeacher, $searchCompany) {
                if($searchTitle != "" && $searchTitle != null) { //# 강의명 검색 정보
                    $query = $query->orwhere('class_request.class_title', 'like', "%".$searchTitle."%");
                }
                if($searchTeacher != "" && $searchTeacher != null) { //#강사명 검색정보
                    $query = $query->orwhere('users_teacher.name', 'like', "%".$searchTeacher."%");
                }
                if($searchCompany != "" && $searchCompany != null) { //#기업명 검색 정보
                    $query = $query->orwhere('users_company.name', 'like', "%".$searchCompany."%");
                }
            });
        }

        //# 요청상태 검색 정보
        $searchStatus = $request->input('columns')[4]['search']['value'];
        if($searchStatus!=null && $searchStatus!="all") {
            $data->where(DB::raw("(select IFNULL(MAX(class_recommend.status), 1) from class_recommend where class_recommend.class_request_id = class_request.id)"), '=', $searchStatus);
        }

        //요청 날짜 비교
        if($request->input('columns')[5]['search']['value'] != "" && $request->input('columns')[6]['search']['value']!="") {
            $dataArr = array($request->input('columns')[5]['search']['value'].' 00:00:00', $request->input('columns')[6]['search']['value'].' 23:59:59');
            $data->whereBetween('class_request.created_at', $dataArr);
        }

        $data->where('class_request.request_type', '=', 'premium'); //프리미엄 요청인 경우만

        if($type == 'contact') {
            $data->whereNull('class_request.users_teacher_id'); //아직 강사와 연결되지 않은 요청만 얻어올 것
//            $data->where(DB::raw('(select count(class_recommend.id) from class_recommend where class_recommend.class_request_id = class_request.id)'), '<', 3); //추천된 강사가 3명 미만인 요청인 경우에만
            $data->where(DB::raw('(SELECT IF(COUNT(class_recommend.id) = 0, 1, 0) 
                                     FROM class_recommend  
                                     where class_recommend.class_request_id = class_request.id and class_recommend.users_teacher_id = '.$teacherId.')'), '=', '1'); //해당 강사가 추천되어져 있지 않은 강의만 출력되도록

            $data->where(DB::raw('adddate( left(class_request.created_at,10), class_request.class_deadline)'), '>', DB::raw('left(now(),10)'));
            //마감일보다 현재날짜가 작으면 마감되지 않은 요청입니다.
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 강의관리 모든 리스트 및 현황
        $titalData = $this->where('request_type', '=', 'premium')->get();
        $score['totalCnt']       = $titalData->count();
        $score['registerCnt']    = $titalData->where('request_status','register')->count();
        $score['p_requestCnt']   = $titalData->where('request_status','p_request')->count();
        $score['p_revirwCnt']    = $titalData->where('request_status','p_revirw')->count();
        $score['p_sendCnt']      = $titalData->where('request_status','p_send')->count();
        $score['c_requestCnt']   = $titalData->where('request_status','c_request')->count();
        $score['c_callCnt']      = $titalData->where('request_status','c_call')->count();
        $score['c_confirmCnt']   = $titalData->where('request_status','c_confirm')->count();
	    $score['c_payment']      = $titalData->where('order_id','!=','')->count();

        $json['score'] = $score;

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        foreach ($data as $item) {
	        //# 강의 상태값 추가
	        $item->request_status_str = recommendStatus( (new ClassRecommend())::select(DB::raw("IFNULL(MAX(status),1) AS status"))->where('class_request_id', '=', $item->id)->whereNull('refuse_type')->first()->status); //의뢰 진행상태
            $item->request_deadline = getRequestDeadline($item->class_deadline, $item->created_at); //요청 마감기한
        }

        $json['data'] = $data;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data Table List ==> 사용자페이지 - 강의 관리 목록
    //##
    //##################################################################################################################
    public function userClassDataTableList($request, $usersCompanyId = 0) {
        //### 데이터 조회
        $data = $this->select('class_request.id',
            'class_request.class_title AS title',
            'class_request.created_at',
            'class_request.request_status',
            'users_company.name AS company_name',
            'users_teacher.name AS teacher_name',
            'users_teacher.email AS teacher_email',
            DB::raw('IF(class_request.class_pay_type = "time", class_request.class_time_pay, class_request.class_count_pay) AS class_pay')
        )->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        //### 검색조건
        if( ($searchType = $request->input('columns')[1]['search']['value'])!="" && ($searchWord = $request->input('columns')[2]['search']['value'])!="" ) {
            $data->where($searchType, 'like', '%'.$searchWord.'%');
        }

        //# 상태 검색 정보
        $searchStatus = $request->input('columns')[3]['search']['value'];
        if($searchStatus!=null && $searchStatus!="all") {
            $data->where(DB::raw("(select IFNULL(MAX(class_recommend.status), 1) from class_recommend where class_recommend.class_request_id = class_request.id)"), '=', $searchStatus);
        }

        if($usersCompanyId>0) {
            $data->where('class_request.users_company_id','=',$usersCompanyId);
        }

        $data->where('class_request.request_type', '=', 'company'); //요청타입이 기업인 것만

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw']            = $request->input('draw');
        $json['recordsTotal']    = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();

        //### 리스트 정보 추가
        $requestData = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();
        foreach ($requestData as $item) {
            //# 강의 상태값 추가
            $item->request_status_str = recommendStatus( (new ClassRecommend())::select(DB::raw("IFNULL(MAX(status),1) AS status"))->where('class_request_id', '=', $item->id)->whereNull('refuse_type')->first()->status); //의뢰 진행상태

            $teacherlist = $item->recommendTeacherUser()->get();

            foreach ($teacherlist  as $info) {
                $teacherInfo = $info->teacherUser()->first();
                $info['teacher_name'] = $teacherInfo->name;
                $info['teacher_email'] = $teacherInfo->email;
                $info['teacher_time_pay'] = $teacherInfo->time_pay?$teacherInfo->time_pay:0;
                $info['teachercount_pay'] = $teacherInfo->time_count_pay?$teacherInfo->time_count_pay:0;
                $info['status'] = ($info['refuse_type'] == null) ? recommendStatus($info['status']) : '거절'; //추천강사 의뢰상태
            }
            $item['teacher_list'] = $teacherlist;
        }

        $json['data'] = $requestData;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data Table List ==> 사용자페이지 프리미엄 의뢰 목록
    //##
    //##################################################################################################################
    public function userRequestDataTableList($request, $usersCompanyId=0) {
        //### 데이터 조회
        $data = $this->select('class_request.id',
            'class_request.class_title AS title',
            'class_request.class_deadline',
            'class_request.request_status',
            'class_request.created_at',
            DB::raw('concat(class_request.class_start_dt, " ~ ", class_request.class_end_dt) as class_dt'),
            'users_teacher.name AS teacher_name',
            'users_teacher.email AS teacher_email',
            'users_company.name AS company_name')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        //### 검색조건
        if( ($searchType = $request->input('columns')[1]['search']['value'])!="" && ($searchWord = $request->input('columns')[2]['search']['value'])!="" ) {
            $data->where($searchType, 'like', '%'.$searchWord.'%');
        }

        //# 상태 검색 정보
        $searchStatus = $request->input('columns')[3]['search']['value'];
        if($searchStatus!=null && $searchStatus!="all") {
            $data->where(DB::raw("(select IFNULL(MAX(class_recommend.status), 1) from class_recommend where class_recommend.class_request_id = class_request.id)"), '=', $searchStatus);
        }

        if($usersCompanyId > 0) {
            $data->where('users_company.id', '=', $usersCompanyId);
        }

        $data->where('class_request.request_type', '=', 'premium'); //프리미엄 요청인 경우만

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        foreach ($data as $item) {
            //# 강의 상태값 추가
            $item->request_status_str = recommendStatus( (new ClassRecommend())::select(DB::raw("IFNULL(MAX(status),1) AS status"))->where('class_request_id', '=', $item->id)->whereNull('refuse_type')->first()->status); //의뢰 진행상태
            $item->request_deadline = getRequestDeadline($item->class_deadline, $item->created_at); //요청 마감기한
            $item->title = $item->title . '<br/>강의분야 : '.(new CateLecture())->getRequestLectureStr($item->id)->title; //분야 문자열 code, title;
        }

        $json['data'] = $data;

        return response()->json($json);
    }

    /**
     * 강의 분야 select ,로 구분
     * @param $id : 강의 고유 아이디
     */
    public function getClassField($id) {
        $data = $this->select(DB::raw('GROUP_CONCAT(cate_lecture.title order by cate_lecture.title separator \', \') AS field'))
            ->leftJoin('class_request_lecture', 'class_request.id', '=', 'class_request_lecture.class_request_id')
            ->leftJoin('cate_lecture', 'class_request_lecture.cate_lecture_code', '=', 'cate_lecture.code');

        $data->where('class_request.id', '=', $id);
        $data = $data->first();

        return $data->field;

    }

    /**
     * 강의 상세 - /admin/request/detail에서 사용중
     * api - getRecentClassData에서 사용중
     * @param $id : 강의 고유 아이디
     */
    public function selectRequestOne($id) {
        $data = $this->select('class_request.*',
            'users_company.name AS company_name',
            'users_teacher.name AS teacher_name',
            'users_teacher.phone AS teacher_phone',
            'users_teacher.email AS teacher_email',
            DB::raw('(select X.title from cate_rank X where X.code = class_request.etc_rank) as rank'),
            DB::raw('IF((select id from evaluate_company X where X.users_company_id = users_company.id and X.class_request_id = class_request.id) is not null, \'Y\', \'N\') as evaluate_yn'))
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

            //TODO reqeust_recommend와 조인하여 해당 강사 제안서,메모(,제안서발송일도 있어야함) 가져오기

        $data = $data->where('class_request.id', '=', $id);
        $data = $data->first();

        $data->request_deadline = getRequestDeadline($data->class_deadline, $data->created_at); //요청 마감기한
        $data->status = (new ClassRecommend())::select(DB::raw("IFNULL(MAX(status),1) AS status"))->where('class_request_id', '=', $data->id)->whereNull('refuse_type')->first()->status;
        $data->requestLecture = (new CateLecture())->getRequestLectureStr($id); //분야 문자열 code, title

        return $data;

    }

    //##################################################################################################################
    //##
    //## >> Data Table List ==> 프리미엄 의뢰 목록
    //## 관리자페이지 강사, 기업회원 상세페이지 강의, 의뢰 목록 리스트
    //##
    //@param $type - company : 기업의뢰, premium : 프리미엄의뢰
    //@param $usersType - 조회하려는 회원타입 - company : 기업회원, teacher : 강사회원
    //##################################################################################################################
    public function dataTableList($request, $type = '', $userType = '') {
        //### 데이터 조회
        $data = $this->select(
            'class_request.id',
            'class_request.class_title AS title',
            'class_request.class_deadline',
            'class_request.request_status',
            'class_request.created_at',
            DB::raw( "IFNULL(users_teacher.name, '-') AS teacher_name" ),
            DB::raw( "IFNULL(users_teacher.email, '-') AS teacher_email" ),
            'users_company.name AS company_name')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        $data->where('class_request.request_type', '=', $type);

        if($companyId = ($request->input('company_id'))) {
            $data->where('users_'.$userType.'.id', '=', $companyId);
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy('class_request.id','DESC')->get();

        foreach ($data as $item) {
            $item->request_deadline = getRequestDeadline($item->class_deadline, $item->created_at); //요청 마감기한
        }

        $json['data'] = $data;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 의뢰 정보
    public function convSaveData($info, $request) {
        $convFieldArr = array("class_title", "class_place", "etc_area", "class_start_dt", "class_end_dt", "class_time", "etc_rank", "etc_number", "etc_age", "etc_memo",
            "request_type", "class_deadline", "class_pay_type", "class_time_pay", "class_count_pay", 'users_company_id', 'evaluate_type');

        //TODO 있을때만 저장되는 배열도 생성하기

        $info->class_start_time = ($request->input('class_start_time', '12:00')) . ':00';

        foreach ($convFieldArr as $key) {
            if($request->input($key, null) != null) {
                $info->{$key} = $request->input($key);
            }
        }

        return $info;
    }


    //##################################################################################################################
    //##
    //## >> Data Table List -- 클래스이음 통계
    //##
    //##################################################################################################################
    public function statDataTableList($request) {
        //### 데이터 조회
/*        $data = $this->select('users_company.id',
            'users_company.company_name',
            'users_company.company_number');*/

        //### 검색조건

        $searchType = $request->input('columns')[1]['search']['value']; //통계 형식

        //# 날짜 검색
        if( ($searchStartDt = $request->input('columns')[2]['search']['value'])!="" && ($searchEndDt = $request->input('columns')[3]['search']['value'])!="" ) {
            //TODO 결제 날짜 조건 추가
        }

        //TODO 결제 형식에 따라서 day, month, year 마다 다른게 데이터 가져오기

        //#회원상태 카운트
        $score['totalPay'] = 0; //전체 매출
        $json['score'] = $score;

        //### 페이징 데이터 정보
/*        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->limit(10)->orderBy('id','DESC')->get();*/
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = 10;
        $json['recordsFiltered'] = 10;

        $data = array();
        for($i=1; $i<=10; $i++) {
            $data[] = array("dt" => $i, "order_pay" => $i*10000, "note" => "비고");
        }
        $json['data'] = array();

        return response()->json($json);
    }
}
