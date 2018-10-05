<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\CateLecture;
use App\Http\Models\CateRank;
use App\Http\Models\ClassRequestLecture;
use App\Http\Models\TeacherSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\ClassRequest;
use App\Http\Models\UsersTeacher;
use App\Http\Models\ClassRecommend;
use EnjoyWorks\core\ResultFunction;
use \DateTime;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    use ResultFunction;

    /**
     * 프리미엄 의뢰 목록
    **/
    function index() {
        return $this->createView('/admin/request/list', '프리미엄 의뢰', '목록');
    }

    /**
     * [Ajax] 프리미엄 의뢰 리스트 정보
     * -----------------------------------------------------------------------------------------------------------------
     */
    function getList(Request $request) {
        return  (new ClassRequest())->requestDataTableList($request);
    }

    /**
     * 의뢰 요청 상세보기
     * @param $id
    */
    function detail($id) {
        $view = $this->createView('/admin/request/detail','프리미엄 의뢰', '상세');

        $view->requestRank = CateRank::select('*')->get(); //대상직급 리스트
        $view->requestLecture = (new CateLecture())->getRequestLectureStr($id); //분야 문자열
        $view->lectureLargeCode = (new CateLecture())::select('large_cate', 'code', 'title')->where(DB::raw('length(code)'), '=', 4)->orderBy('large_cate')->orderBy('code')->get(); //강의 분야 - 중분류
        $view->lectureSmallCode = (new CateLecture())->getRequestLecture($id); //강의 분야 - 소분류 목록

        $view->info = (new ClassRequest())->selectRequestOne($id); //프리미엄 의뢰 상세 내용
        return $view;
    }

    /**
     * 프리미엄 의뢰 수정
     */
    function updateDB(Request $request) {
        $data = $request->input();

        $info = ClassRequest::find($data['id']);

        $info = $info->convSaveData($info, $request);

        $res = $info->save();

        if($res > 0) {
            $resChangeLecture = (new ClassRequestLecture())->changeRequestLecture(( (isset($data['lecture']) && $data['lecture']!="") ? explode(',', $data['lecture']) : array()), $data['id']); //출강분야 수정
        }

        if($res > 0) {
            return redirect('/admin/request')->with('flash_message', '강의 수정 완료');
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

    //기업에게 강사 추천 ==> 기업 프리미엄 의뢰 페이지에서 클래스이음 추천 화면에서 볼 수 있음
    //프리미엄 요청 하나에 강사 한명 추천 가능 ??? ==> 3명까지 추천하고 요청은 한명에게만 ==> 수 제한 없음
    /**
     * [ajax]강사 찾기
     * @param $requestId : 프리미엄 의뢰 고유 아이디
     */
    function searchTeacher($requestId) {
        return (new UsersTeacher())->requestSearchTeacher($requestId);
    }

    function insertRecommendTeacher(Request $request) {
        $data = new ClassRecommend();

        $data = $data->convSaveData($data, $request);
        $res = $data->save(); //class_recommend 테이블 insert

        if($res>0) {
            return $this->returnSuccess($res, '추천강사 등록에 성공하였습니다.');
        } else {
            return $this->returnFailed('추천강사 등록에 실패하였습니다.');
        }
    }

    /**
     * [ajax] 추천 강사 제안서 요청하기
     * @param $recommendId : 추천 강사 테이블 고유 아이디
     */
    function requestProposal($recommendId) {
        $data = ClassRecommend::find($recommendId);

        $data->status = '2'; //제안서 요청상태로 변경

        $res = $data->save();

        if($res>0) {
            return $this->returnSuccess($res, '추천강사 제안서 요청에 성공하였습니다.');
        } else {
            return $this->returnFailed('추천강사 제안서 요청에 실패하였습니다.');
        }
    }

    /**
     * [ajax] 추천 강사 삭제
     * @param $recommendId : 추천 강사 테이블 고유 아이디
     */
    function delRecommendTeacher($recommendId) {
        $data = ClassRecommend::find($recommendId);

        $res = $data->delete();

        if($res>0) {
            return $this->returnSuccess($res, '추천강사 삭제 성공하였습니다.');
        } else {
            return $this->returnFailed('추천강사 삭제에 실패하였습니다.');
        }
    }

}
