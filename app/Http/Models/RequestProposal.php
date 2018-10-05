<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RequestProposal extends Model
{
    //요청 제안서
    protected $table = 'request_proposal';

    //##################################################################################################################
    //##
    //## 해당 요청은 강사 회원 페이지에서 합니다.
    //## >> Data Table List - 클래스이음에게 요청한 제안서
    //##
    //##################################################################################################################
    public function dataTableClasseumList($request) {
        $columns = array(0 => 'id', 1 => 'company_name', 2 => 'users_teacher.name', 3 => 'class_title', 5 => 'created_at', 6 => 'request_proposal.updated_at');

        //### 데이터 조회
        $data = $this->select('request_proposal.id',
            'users_teacher.name as teacher_name',
            'users_company.name as company_name',
            'class_request.class_title',
            DB::raw('IF(request_proposal.pdf_name is not null, \'Y\',\'N\') as response_yn'),
            'request_proposal.created_at as request_dt',
            DB::raw('IF(request_proposal.pdf_name is not null, request_proposal.updated_at, \'\') as response_dt')
        )
            ->leftJoin('class_recommend', 'request_proposal.class_recommend_id', '=', 'class_recommend.id')
            ->leftJoin('class_request', 'class_recommend.class_request_id', '=', 'class_request.id')
            ->leftJoin('users_teacher', 'class_recommend.users_teacher_id', '=', 'users_teacher.id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id');

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
                if($searchTitle != "") { //# 강의명 검색 정보
                    $query = $query->orwhere('class_title', 'like', "%".$searchTitle."%");
                }
                if($searchTeacher != "") { //#강사명 검색정보
                    $query = $query->orwhere('users_teacher.name', 'like', "%".$searchTeacher."%");
                }
                if($searchCompany != "") { //#기업명 검색 정보
                    $query = $query->orwhere('users_company.name', 'like', "%".$searchCompany."%");
                }
            });
        }

        //#전달 상태 검색
        $searchStatus = $request->input('columns')[4]['search']['value'];
        if($searchStatus!=null) {
            if($searchStatus == 'Y') {
                $data->whereNotNull('request_proposal.pdf_name');
            } else if($searchStatus == 'N') {
                $data->whereNull('request_proposal.pdf_name');
            }
        }
        //#요청 날짜 검색
        if($request->input('columns')[5]['search']['value'] != "" && $request->input('columns')[6]['search']['value']!="") {
            $dataArr = array($request->input('columns')[5]['search']['value'].' 00:00:00', $request->input('columns')[6]['search']['value'].' 23:59:59');
            $data->whereBetween('request_proposal.created_at', $dataArr);
        }

        $data->where('request_proposal.request_type', 'classeum');

        $order = $request->input('order')[0]['column'];
        $order = ($order!="") ? $columns[$order] : 'id';
        $orderType = $request->input('order')[0]['dir'];
        $orderType = ($orderType!="") ? $orderType : 'DESC';

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 강의관리 모든 리스트 및 현황
        $score['completeCnt'] = $this->whereNotNull('request_proposal.pdf_name')->count(); //전달완료한 제안서 건수
        $score['incompleteCnt'] = $this->whereNull('request_proposal.pdf_name')->count(); //전달 안한 제안서 건수
        $json['score'] = $score;


        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $this->count(); //전체 제안서 요청 건수
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->offset($limitStart)->limit($limitlength)->orderBy($order,$orderType)->get();

        return response()->json($json);
    }

    /**
     * 제안서 관리 상세페이지 데이터 가져오기
     * @param $id : 제안서 고유 아이디
     * @return array
     */
    public function selectClasseumProposalOne($id) {
        //### 데이터 조회
        $data = $this->select('request_proposal.id',
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
            DB::raw('IF(request_proposal.pdf_name is not null, \'Y\',\'N\') as response_yn'),
            'request_proposal.created_at as request_dt',
            'request_proposal.pdf_name',
            DB::raw('IF(request_proposal.pdf_name is not null, request_proposal.updated_at, \'\') as response_dt')
        )
            ->leftJoin('class_recommend', 'request_proposal.class_recommend_id', '=', 'class_recommend.id')
            ->leftJoin('class_request', 'class_recommend.class_request_id', '=', 'class_request.id')
            ->leftJoin('users_teacher', 'class_recommend.users_teacher_id', '=', 'users_teacher.id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id');

        //제안서 고유 아이디 검색조건 추가
        $data->where('request_proposal.id', '=', $id);

        return $data->first();
    }
}
