<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluateUserQuestion extends Model
{
    //수강생 평가 - 평가항목 관리 테이블
    protected $table = 'evaluate_user_question';

    //##################################################################################################################
    //##
    //## >> Data Table List 수강생 평가항목 관리 테이블 목록 - 기본
    //##
    //##################################################################################################################
    public function basicDataTableList($request) {
        //### 데이터 조회
        $data = $this->select('evaluate_user_question.*');
        $data->where('evaluate_type', 'basic');

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count();
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data Table List 수강생 평가항목 관리 테이블 목록 - 일회성
    //##
    //##################################################################################################################
    public function oneDataTableList($request) {
        //### 데이터 조회
        $data = $this->select('evaluate_user_question.*');
        $data->where('evaluate_type', 'one');

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count();
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 데이터 등록 및 수정
    public function convSaveData($info, $request) {
        $convFieldArr = array("question", "answer_type", "answer_score", "evaluate_type", "question_topic");

        foreach ($convFieldArr as $key) {
            $info->{$key} = $request->input($key);
        }

        return $info;
    }
}
