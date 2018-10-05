<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassProposal extends Model
{
    //클래스이음에게 요청한 제안서
    protected $table = 'class_proposal';

    //##################################################################################################################
    //##
    //## 해당 요청은 강사 회원 페이지에서 합니다.
    //## >> Data Table List - 클래스이음에게 요청한 제안서
    //##
    //##################################################################################################################
    public function dataTableList($request) {

        //### 데이터 조회
        $data = $this->select('class_proposal.id',
            'users_teacher.name as teacher_name',
            'users_company.name as company_name',
            'class_request.class_title',
            DB::raw('IF(class_proposal.pdf_url != null, \'Y\',\'N\') as response_yn'),
            'class_proposal.created_at as request_dt',
            DB::raw('IF(class_proposal.pdf_url != null, class_proposal.updated_at, \'\') as response_dt')
            )
            ->leftJoin('class_recommend', 'class_proposal.class_recommend_id', '=', 'class_recommend.id')
            ->leftJoin('class_request', 'class_recommend.class_request_id', '=', 'class_request.id')
            ->leftJoin('users_teacher', 'class_recommend.users_teacher_id', '=', 'users_teacher.id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id');

        //### 검색조건
        //#전체 검색 정보
        $searchAll = $request->input('search')['value'];
        if($searchAll != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('class_request.title', 'like', "%".$searchAll."%")
                    ->orWhere('users_teacher.name', 'like', "%".$searchAll."%");
            });
        }

        //# 강의명 검색 정보
        $searchTitle = $request->input('columns')[1]['search']['value'];
        if($searchTitle != null) {
            $data->where("class_request.title", "like", "%".$searchTitle."%");
        }
        //# 요청강사 검색 정보
        $searchName= $request->input('columns')[2]['search']['value'];
        if($searchName != null) {
            $data->where("users_teacher.name", "like", "%".$searchName."%");
        }
        //#전달 상태 검색
        $searchStatus = $request->input('columns')[3]['search']['value'];
        if($searchStatus!=null) {
            if($searchStatus == 'Y') {
                $data->whereNotNull('class_proposal.pdf_url');
            } else if($searchStatus == 'N') {
                $data->whereNull('class_proposal.pdf_url');
            }
        }
        //#요청 날짜 검색
        if($request->input('columns')[4]['search']['value'] != "" && $request->input('columns')[5]['search']['value']!="") {
            $dataArr = array($request->input('columns')[4]['search']['value'].' 00:00:00', $request->input('columns')[5]['search']['value'].' 23:59:59');
            $data->whereBetween('class_proposal.created_at', $dataArr);
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');


        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $this->count(); //전체 제안서 요청 건수
        $json['completeCnt'] = $this->whereNotNull('class_proposal.pdf_url')->count(); //전달완료한 제안서 건수
        $json['incompleteCnt'] = $this->whereNull('class_proposal.pdf_url')->count(); //전달 안한 제안서 건수
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        return response()->json($json);
    }

    /**
     * 제안서 관리 상세페이지 데이터 가져오기
     * @param $id : 제안서 고유 아이디
    */
    public function selectProposalOne($id) {
        //### 데이터 조회
        $data = $this->select('class_proposal.id',
            'users_teacher.name as teacher_name',
            'users_company.name as company_name',
            'class_request.id as class_request_id',
            'class_request.class_title',
            'class_request.class_start_dt',
            'class_request.class_place',
            'class_request.class_time',
            'class_request.etc_rank',
            'class_request.etc_age',
            'class_request.etc_memo',
            'class_request.etc_number',
            DB::raw('IF(class_request.class_pay_type = "time", class_request.class_time_pay, class_request.class_count_pay) AS class_pay'),
            DB::raw('IF(class_proposal.pdf_url != null, \'Y\',\'N\') as response_yn'),
            'class_proposal.created_at as request_dt',
            'class_proposal.pdf_url',
            DB::raw('IF(class_proposal.pdf_url != null, class_proposal.updated_at, \'\') as response_dt')
        )
            ->leftJoin('class_recommend', 'class_proposal.class_recommend_id', '=', 'class_recommend.id')
            ->leftJoin('class_request', 'class_recommend.class_request_id', '=', 'class_request.id')
            ->leftJoin('users_teacher', 'class_recommend.users_teacher_id', '=', 'users_teacher.id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id');

        //제안서 고유 아이디 검색조건 추가
        $data->where('class_proposal.id', '=', $id);

        return $data->first();
    }
}
