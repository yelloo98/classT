<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EvaluateUser extends Model
{
    //
    protected $table = 'evaluate_user';

    //##################################################################################################################
    //##
    //## >> Data Table List
    //##
    //@param $userTeacherId : 강사회원아이디
    //##################################################################################################################
    public function dataTableList($request, $userTeacherId = 0) {
        $columns = array(0 => 'evaluate_user.id', 1 => 'company_name', 2 => 'teacher_name', 3 => 'class_title', 4 => 'etc_number', 5 => 'satisfaction', 6 => 'rating', 7 => 'class_start_dt');

        //### 데이터 조회
        $data = $this->select('evaluate_user.*',
            'users_teacher.name as teacher_name',
            'class_request.class_title',
            'class_request.class_start_dt',
            'users_company.name as company_name',
            DB::raw('(select count(X.id) from evaluate_user X where X.class_request_id = class_request.id) as etc_number'),
            DB::raw('(select ROUND(AVG(answer_num),1) from evaluate_user_answer X where X.evaluate_user_id in (select id from evaluate_user Y where Y.class_request_id = evaluate_user.class_request_id) and X.answer_type = \'STAR\') as satisfaction'), //만족도
            DB::raw('(select ROUND(AVG(answer_num),1) from evaluate_user_answer X where X.evaluate_user_id in (select id from evaluate_user Y where Y.class_request_id = evaluate_user.class_request_id) and X.answer_type = \'NUM\') as rating'))
            ->leftJoin('evaluate_user_answer', 'evaluate_user.id', '=', 'evaluate_user_answer.evaluate_user_id')
            ->leftJoin('class_request', 'class_request.id', '=', 'evaluate_user.class_request_id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        //TODO 종료된 강의만 가져올 것!

        //### 검색조건
        //#전체 검색 정보
        $searchAll = $request->input('search')['value'];
        if($searchAll != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('class_request.class_title', 'like', "%".$searchAll."%")
                    ->orWhere('users_teacher.name', 'like', "%".$searchAll."%")
                    ->orWhere('users_company.name', 'like', "%".$searchAll."%");
            });
        }

        //# 제목 검색 정보
        $searchTitle = $request->input('columns')[1]['search']['value'];
        if($searchTitle != null) {
            $data->where("class_request.class_title", "like", "%".$searchTitle."%");
        }
        //# 강사명 검색 정보
        $searchTeacherName= $request->input('columns')[2]['search']['value'];
        if($searchTeacherName != null) {
            $data->where("users_teacher.name", "like", "%".$searchTeacherName."%");
        }
        //# 기업명 검색 정보
        $searchCompanyName= $request->input('columns')[3]['search']['value'];
        if($searchCompanyName != null) {
            $data->where("users_company.company_name", "like", "%".$searchCompanyName."%");
        }

        if($userTeacherId > 0) {
            $data->where('class_request.users_teacher_id', '=', $userTeacherId);
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### sorting
        $order = $request->input('order')[0]['column'];
        $order = ($order!="") ? $columns[$order] : 'id';
        $orderType = $request->input('order')[0]['dir'];
        $orderType = ($orderType!="") ? $orderType : 'DESC';

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->groupBy('class_request_id')->get()->count(); //class_request_id로 묶은 후의 전체 행수
        $json['recordsFiltered'] = $data->groupBy('class_request_id')->get()->count();

        $data = $data->offset($limitStart)->limit($limitlength)->groupBy('class_request_id')->orderBy($order, $orderType)->get();

        $json['data'] = $data;

        return response()->json($json);
    }

    public function selectEvaluateUserOne($id) {

        $data = $this->select('evaluate_user.*',
            'users_teacher.name as teacher_name',
            'class_request.class_title',
            'class_request.class_start_dt',
            'class_request.etc_number',
            'users_company.name as company_name',
            DB::raw('(select count(X.id) from evaluate_user X where X.class_request_id = class_request.id) as etc_number'),
            DB::raw('(select ROUND(AVG(answer_num),1) from evaluate_user_answer X where X.evaluate_user_id in (select id from evaluate_user Y where Y.class_request_id  = evaluate_user.class_request_id) and X.answer_type = \'STAR\') as satisfaction'), //만족도
            DB::raw('(select ROUND(AVG(answer_num),1) from evaluate_user_answer X where X.evaluate_user_id in (select id from evaluate_user Y where Y.class_request_id  = evaluate_user.class_request_id) and X.answer_type = \'NUM\') as rating'),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_gender = 'M') as gender_m"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_gender = 'G') as gender_g"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_age = '2') as age_2"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_age = '3') as age_3"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_age = '4') as age_4"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_age = '5') as age_5"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_age = '6') as age_6"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_position = 'R001') as pos_1"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_position = 'R002') as pos_2"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_position = 'R003') as pos_3"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_position = 'R004') as pos_4"),
            DB::raw("(select count(X.id) from evaluate_user X where X.class_request_id  = evaluate_user.class_request_id and X. rater_position = 'R005') as pos_5"))
            ->leftJoin('class_request', 'class_request.id', '=', 'evaluate_user.class_request_id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'class_request.users_teacher_id', '=', 'users_teacher.id');

        $data = $data->where('evaluate_user.class_request_id', '=', $id)->groupBy('evaluate_user.class_request_id')->first();

        return $data;
    }

    /**
     * 강의에 대한 평가문항의 점수, 질문 목록 반환
     * @param $id : (class_request) 강의 고유 아이디
     * @return array
    */
    public function getRequestScore($id) {
        $data = $this->select(
            DB::raw('ROUND(AVG(answer_num)) as avg'),
            'question_topic as question'
        )->leftJoin('evaluate_user_answer', 'evaluate_user.id', '=', 'evaluate_user_answer.evaluate_user_id');

        $data = $data->where('evaluate_type', 'basic')->where('evaluate_user_answer.answer_type', '=', 'NUM')->groupBy('question_id')->get();

        $scoreAry = array();

        foreach ($data as $item) {
            $scoreAry[] = array('score_avg' => $item->avg, 'question' => $item->question);
        }

        return $scoreAry;
    }

}
