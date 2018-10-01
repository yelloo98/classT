<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassRecommend extends Model
{
    use SoftDeletes;

    //강사 추천 테이블
    protected $table = 'class_recommend';

	//강사 회원
	function teacherUser() {
		return $this->hasOne( 'App\Http\Models\UsersTeacher', 'id', 'users_teacher_id');
	}

	//##################################################################################################################
	//##
	//## >> Common processing
	//##
	//##################################################################################################################
	/**
	 * 추천강사 상태값 변경 및 시간 저장
	 * @param $id : 강사 ID
	 * @param $status : 상태 값
	 */
	public function updateStatus($id, $status) {
		$info = $this::find($id);
		$info->status = $status;
		switch ($status) {
			case 1: break;
			//# 제안서 요청 일자
			case 2: $info->p_request_dt = 'now()'; break;
			case 3: break;
			//# 제안서 발송일자
			case 4: $info->p_send_dt = 'now()'; break;
			//# 강의요청 일자
			case 5: $info->c_request_dt = 'now()'; break;
			//# 출강요청 일자
			case 6: $info->c_call_dt = 'now()'; break;
			//# 강의확정 일자
			case 7: $info->c_confirm_dt = 'now()'; break;

		}
		return $info->save();
	}

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 추천강사 insert
    public function convSaveData($info, $request) {
        $convFieldArr = array("users_teacher_id", "class_request_id");

        foreach ($convFieldArr as $key) {
            $info->{$key} = $request->input($key);
        }

        return $info;
    }

    /**
     * 해당 강의 아이디에 추천되어져 있는 강사 아이디 목록 리턴
     * @param $classRequestId : 강의 의뢰 아이디
    */
    public function getClassRecommendedTeacher($classRequestId) {
        $data = $this->select( DB::raw('DISTINCT users_teacher_id'));

        $data->where('class_request_id', '=', $classRequestId);

        return $data->get();
    }

    /**
     * 추천 강사 목록 가져오기 추천상태 or 거절상태
     * @param $requestId : 프리미엄 요청 테이블 고유 아이디
     * @param $status : 강사 상태 : recommend or refuse
     * @return json parameter
     */
    public function getRecommendTeacherList($requestId, $status) {

        $data = $this->select(
            'class_recommend.id',
            'class_recommend.status',
            'class_recommend.refuse_type',
            'class_recommend.proposal_name',
            'class_recommend.p_send_dt',
            'class_recommend.c_request_dt',
            'class_recommend.c_call_dt',
            'class_recommend.c_confirm_dt',
            'users_teacher.name',
            'users_teacher.id AS users_teacher_id'
        )->leftJoin('users_teacher', 'class_recommend.users_teacher_id', '=', 'users_teacher.id');

        $data->where('class_recommend.class_request_id', '=', $requestId);

        if($status == 'recommend') {
            $data->whereNotNull('class_recommend.proposal_name');
        }

        $data = $data->orderBy('class_recommend.status', 'DESC')->get();

        return response()->json($data);
    }

    /**
     * 추천강사 한명에 대한 강의 의뢰정보, 추천강사 정보, 제안서 얻기
     * @param $recommendId : 추천 강사 고유 아이디
    */
    public function selectRecommendOne($recommendId) {
        $data = $this->select(
            'class_recommend.*', 'class_request.request_type',
            'users_teacher.name', 'users_teacher.birth', 'users_teacher.introduction',
            'class_request.class_title', 'class_request.class_place', 'class_request.class_time', 'class_request.etc_number', 'class_request.etc_age', 'class_request.class_time_pay',
            'class_request.etc_memo', 'class_request.class_deadline', 'class_request.created_at', 'class_request.id as class_request_id',
            DB::raw("concat(class_start_dt, ' ~ ', class_end_dt) as class_date"),
            DB::raw("(SELECT X.title from cate_rank X where X.code = class_request.etc_rank) as etc_rank"),
            DB::raw("(SELECT group_concat(X.title) from cate_lecture X where X.code in (SELECT X.cate_lecture_code from class_request_lecture X where X.class_request_id = class_request.id)) as lecture"),
            'class_recommend.id as class_recommend_id', 'class_recommend.proposal_name', 'class_recommend.proposal_memo', 'class_recommend.status', 'class_recommend.p_send_dt',
            DB::raw("IFNULL( (select X.pdf_name from request_proposal X where X.class_recommend_id = class_recommend.id and X.request_type = 'classeum'), '') as p_classeum"),
            DB::raw("IFNULL( (select X.pdf_name from request_proposal X where X.class_recommend_id = class_recommend.id and X.request_type = 'mentor'), '') as p_mentor"),
            DB::raw("(select X.id from request_proposal X where X.class_recommend_id = class_recommend.id) as p_req_id")
            )->leftJoin('class_request', 'class_recommend.class_request_id', '=', 'class_request.id')
            ->leftJoin('users_teacher', 'class_recommend.users_teacher_id', '=', 'users_teacher.id');

        $data = $data->where('class_recommend.id', '=', $recommendId)->first();

        $data->request_deadline = getRequestDeadline($data->class_deadline, $data->created_at); //요청 마감기한
        return $data;
    }

    /**
     * 강사 회원 - 출강 업종 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 업종 값들은 delete 후 넘어온 값으로 insert
     * @param $recommendAry : post로 넘어온 요청 강사회원 아이디 배열
     * @param $classRequestId : 등록 의뢰 아이디
     * @return $res
     * */
    public function insertClassRecommend($recommendAry, $classRequestId) {
        $classRecommend = $this;

        $selRecommend = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($recommendAry) && !empty($recommendAry)) {
            foreach ($recommendAry as $usersTeacherId) {
                $selRecommend[] = array('class_request_id' => $classRequestId, 'users_teacher_id' => $usersTeacherId, 'status' =>2, 'created_at' => date('Y-m-d H:i:s'));
            }

            $res = $classRecommend->insert($selRecommend);
        }

        return $res;

    }

    /**
     * 강사의 강의현황 목록 확인하기
     * @param $usersTeacherId : 강사 회원 아이디
     **/
    public function teacherClassDataTableList($request, $usersTeacherId = 0) {

        $data = $this->select(
            'class_recommend.id',
            DB::raw('IF(class_request.request_type = "company", "강의의뢰", "프리미엄의뢰") as request_type'),
            'class_request.class_title', 'users_company.company_name', 'class_recommend.status', 'class_request.order_id', 'class_request.created_at',
            DB::raw("concat(class_request.class_start_dt, ' ~ ', class_request.class_end_dt) as class_dt")
        )->leftJoin('class_request', 'class_recommend.class_request_id', '=', 'class_request.id')
        ->leftJoin('users_company', 'users_company.id', '=', 'class_request.users_company_id');

        if( ($startDt = $request->input('columns')[1]['search']['value']) != "" && ($endDt = $request->input('columns')[2]['search']['value'])!="") {
            $dataArr = array($startDt.' 00:00:00', $endDt.' 23:59:59');
            $data->whereBetween('class_request.created_at', $dataArr);
        }

        $status = $request->input('columns')[3]['search']['value'];
        if($status!= 'all') {
            if($status >= 1 && $status <= 7) {
                $data->where('class_recommend.status', '=', $status);
            } else { //결제완료
                $data->whereNotNull('class_request.order_id');
            }
        }

        if($usersTeacherId > 0) {
            $data->where('class_recommend.users_teacher_id', '=', $usersTeacherId);
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw']            = $request->input('draw');
        $json['recordsTotal']    = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->get();

        foreach ($data as $itemData) {
            if($itemData->order_id != null) {
                $itemData->status_str = '결제완료';
            } else {
                $itemData->status_str = recommendStatus($itemData->status);
            }
        }
        $json['data'] = $data;

        return response()->json($json);
    }

}
