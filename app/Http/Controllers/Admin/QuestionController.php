<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\EvaluateUserQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EnjoyWorks\core\ResultFunction;

class QuestionController extends Controller
{
    //수강생 평가항목 관리

    use ResultFunction;

    //TODO 수강생 평가 항목 목록
    /**
     * 수강생 평가 평가항목 관리 페이지로 이동
     */
    function index() {
        return $this->createView('/admin/review/student/question/list', '수강생평가 평가항목', '목록');
    }

    /**
     * 수강생 평가항목 목록 - 기본
     */
    function getBasicList(Request $request) {
        return (new EvaluateUserQuestion())->basicDataTableList($request);
    }

    /**
     * 수강생 평가항목 목록  - 일회성
     */
    function getOneList(Request $request) {
        return (new EvaluateUserQuestion())->oneDataTableList($request);
    }

    /**
     * [ajax] 수강생 평가항목 select
     * @param $id : 수강생 평가 항목 아이디
     */
    function selectQuestionOne($id) {
        $info = EvaluateUserQuestion::find($id);

        if($info->id != "") {
            return $this->returnData($info);
        } else {
            return $this->returnFailed($info);
        }
    }

    /**
     * [ajax] 수강생 평가항목 insert or update
     */
    function saveDB(Request $request) {
        $data = $request->input();

        if($data['id'] == null) {
            $info = new EvaluateUserQuestion();
        } else {
            $info = EvaluateUserQuestion::find($data['id']);
        }

        $info = $info->convSaveData($info, $request);

        $res = $info->save();

        if($res > 0) {
            return $this->returnData($res);
        } else {
            return $this->returnFailed($res);
        }

    }

    /**
     * [ajax] 수강생 평가항목 삭제
     * @param $id : 수강생 평가항목 아이디
     */
    function deleteDB($id) {
        $info = EvaluateUserQuestion::find($id);

        $res = $info->delete();

        if($res > 0) {
            return $this->returnData($res);
        } else {
            return $this->returnFailed($res);
        }

    }
}
