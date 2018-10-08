<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherGrade extends Model
{
    //강사 등급 수수료 관리 테이블
    protected $table = 'teacher_grade';

    //##################################################################################################################
    //##
    //## >> Data Table List 강사 등급 수수료 관리 테이블 목록
    //##
    //##################################################################################################################
    public function dataTableList($request) {

        //### 데이터 조회
        $data = $this->select('teacher_grade.*');

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count();
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->get();//$data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 데이터 등록 및 수정
    public function convSaveData($info, $request) {
        $convFieldArr = array("name", "classeum_fee", "mentor_fee", "classeum_proposal_fee", "mentor_proposal_fee");

        foreach ($convFieldArr as $key) {
            $info->{$key} = $request->input($key);
        }

        return $info;
    }

    /**
     * 등급 아이디, 코드 얻어오기
     * 강사가 선택한 등급이면 check_yn을 Y로 해서 반환할 것
     * @param $user_teacher_id
     * @return array
     */
    public function getTeacherGrade($user_teacher_id) {
        //### 데이터 조회
        $data = $this->select('teacher_grade.id',
            'teacher_grade.name',
            (DB::raw('IF(teacher_grade.id in (select users_teacher.grade from users_teacher where id = '.$user_teacher_id.'), \'Y\', \'N\') AS check_yn')));

        return $data->get();
    }
}
