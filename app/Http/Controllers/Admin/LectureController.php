<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\CateLecture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\core\ResultFunction;
class LectureController extends Controller
{
    //강의 분야관리

    use ResultFunction;

    /**
     * 분야관리 목록 페이지
    **/
    function index() {
        return $this->createView('/admin/lecture/list', '강의분야', '관리');
    }

    /**
     * 분야 데이터 요청
    **/
    function getList(Request $request) {
        return (new CateLecture())->dataTableList($request);
    }

    /**
     * [ajax] 대분류 목록 반환
    */
    function getLargeCateList() {
        return (new CateLecture())->getLargeCateList();
    }

    /**
     * [ajax] 선택한 대분류에 해당되는 중분류 목록 반환
     * @param $largeCate : 선택한 대분류
    */
    function getMidCateList($largeCate) {
        return (new CateLecture())->getMidCateList($largeCate);
    }

    /**
     * [ajax] 분야 한행 반환
     * @param $id : 강의 분야 테이블 고유 아이디
    */
    function selectLectureOne($id) {
        return (new CateLecture())->selectLectureOne($id);
    }

    /**
     * 강의 분야 등록 or 수정
     * */
    function saveDB(Request $request) {
        $data = $request->input();
        $code = '';

        if($data['id'] == null) { //처음 등록하는 경우
            $info = new CateLecture();

            if($data['lectureType'] == 'm') { //중분류 등록시
                $code = (new CateLecture())->select(DB::raw("substr(code,2,3) as code"))->where(DB::raw("length(code)"), '4')->orderBy("code", "desc")->first()->code;
                $code = 'L'.sprintf("%03d",((int)$code)+1);
            } else if($data['lectureType'] == 's') { //소분류 등록시
                $code = (new CateLecture())->select(DB::raw("substr(code,5,3) as code"))->where(DB::raw("length(code)"), '7')->where(DB::raw("substr(code, 1,4)"), $data['mid_cate'])->orderBy("code", "desc")->first();
                $code = $data['mid_cate'] . (($code == null ) ? '001' : sprintf("%03d",((int)$code->code)+1));
            }
            $info->code = $code;
            $info->large_cate = $data['large_cate'];

        } else {
            $info = CateLecture::find($data['id']);
        }

        //TODO 강의분야 이미 있는 내용인지 중복체크할 것 ==> 보류

        $info->title = $data['title']; //강의문야 텍스트

        $res = $info->save();

        if($res > 0) {
            return $this->returnData($res, '강의 분야 '. (($data['id']!="") ? '수정' : '등록') . ' 성공!');
        } else {
            return $this->returnFailed($res);
        }
    }
}
