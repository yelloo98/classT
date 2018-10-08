<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EvaluateCompany extends Model
{
    //강사평가 - 기업용

    protected $table = 'evaluate_company';

    //##################################################################################################################
    //##
    //## >> Data Table List
    //##
    // $usersCompanyId : 기업회원아이디
    // $usersTeacherId : 강사회원아이디
    //##################################################################################################################
    public function dataTableList($request, $usersCompanyId = 0, $usersTeacherId = 0) {
        $columns = array(0 => 'evaluate_company.id', 1 => 'company_name', 2 => 'teacher_name', 3 => 'class_title', 4 => 'etc_number', 5 => 'score', 6 => 'class_start_dt');

        //### 데이터 조회
        $data = $this->select('evaluate_company.*', 'users_teacher.name as teacher_name', 'class_request.class_title', 'class_request.etc_number', 'class_request.class_start_dt', 'users_company.company_name as company_name',
            DB::raw("concat(class_request.class_start_dt, class_request.class_end_dt) as class_dt"))
            ->leftJoin('class_request', 'class_request.id', '=', 'evaluate_company.class_request_id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        //### 검색조건
        //#전체 검색 정보
        if( ($searchAll = $request->input('search')['value']) != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('class_request.class_title', 'like', "%".$searchAll."%")
                    ->orWhere('users_teacher.name', 'like', "%".$searchAll."%")
                    ->orWhere('users_company.name', 'like', "%".$searchAll."%");
            });
        }

        $order = "evaluate_company.id";
        $orderType = "DESC";

        if($usersCompanyId > 0) { //기업페이지에서 확인하는 경우
            $data->where('evaluate_company.users_company_id', '=', $usersCompanyId);

            //#날짜 검색 정보
            if( ($searchStartDt = $request->input('columns')[1]['search']['value']) != "" && ($searchEndDt = $request->input('columns')[2]['search']['value']) !="") {
                $dataArr = array($searchStartDt, $searchEndDt);
                $data->where(function ($query) use ($dataArr) {
                    $query = $query->whereBetween('evaluate_company.created_at', $dataArr);

                });
            }
        } else { //관리자페이지용 || 강사가 보는 후기
            //# 제목 검색 정보
            if( ($searchTitle = $request->input('columns')[1]['search']['value']) != "") {
                $data->where("class_request.class_title", "like", "%".$searchTitle."%");
            }
            //# 강사명 검색 정보
            if(($searchTeacherName = $request->input('columns')[2]['search']['value']) != "") {
                $data->where("users_teacher.name", "like", "%".$searchTeacherName."%");
            }
            //# 기업명 검색 정보
            if( ($searchCompanyName = $request->input('columns')[3]['search']['value'])!= "") {
                $data->where("users_company.company_name", "like", "%".$searchCompanyName."%");
            }

            if($usersTeacherId > 0) {
                $data->where('class_request.users_teacher_id', '=', $usersTeacherId);
            } else {
                $order = $request->input('order')[0]['column'];
                $order = ($order!="") ? $columns[$order] : 'id';
                $orderType = $request->input('order')[0]['dir'];
                $orderType = ($orderType!="") ? $orderType : 'DESC';
            }
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count();
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy($order, $orderType)->get();

        foreach ($data as $item) {
            if($item->etc_number !="") {
                $item->etc_number = stdNumber($item->etc_number);
            }
        }
        $json['data'] = $data;

        return response()->json($json);
    }

    /**
     * 기업 평가 상세 데이터
     * @param $id : 평가 테이블 고유 아이디
     * @return
    */
    public function selectEvaluateCompanyOne($id) {
        $data = EvaluateCompany::select('evaluate_company.*', 'users_teacher.name as teacher_name', 'class_request.class_title', 'class_request.class_start_dt', 'users_company.name as company_name',
            'class_request.id as class_request_id')
            ->leftJoin('class_request', 'class_request.id', '=', 'evaluate_company.class_request_id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        return $data->where('evaluate_company.id', '=', $id)->first();
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 기업 리뷰
    public function convSaveData($info, $request) {
        $convFieldArr = array('users_company_id', 'class_request_id', 'score', 'content');

        foreach ($convFieldArr as $key) {
            if($request->input($key, null) != null) {
                $info->{$key} = $request->input($key);
            }
        }

        return $info;
    }
}
