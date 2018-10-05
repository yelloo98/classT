<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\CateLecture;
use App\Http\Models\CateRank;
use App\Http\Models\ClassRecommend;
use App\Http\Models\ClassRequest;
use App\Http\Models\ClassRequestLecture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\ClassRequest as tRequest;
use App\Http\Models\UsersTeacher;
use EnjoyWorks\core\ResultFunction;
use \DateTime;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;

class ClassController extends Controller
{
    use ResultFunction;

    /**
     * 기본 강의 목록
    **/
    function index() {
        return $this->createView('/admin/class/list', '강의', '목록');
    }

    /**
     * [Ajax] 리스트 정보
     * -----------------------------------------------------------------------------------------------------------------
     */
    function getList(Request $request) {
        return  (new tRequest())->classDataTableList($request);
    }

    /**
     * 기본 강의 관리 상세보기
     * @param $id : 강의 의뢰 아이디
     */
    function detail($id) {
        $view = $this->createView('/admin/class/detail','강의', '상세');

        $data = new tRequest();
        $data = $data->selectRequestOne($id);

        $teacherList = $data->recommendTeacherUser()->get(); //해당 강의의 추천강사 목록
        foreach ($teacherList  as $info) {
            $teacherInfo = $info->teacherUser()->first();
            $info['teacher_name'] = $teacherInfo->name;
            $info['teacher_email'] = $teacherInfo->email;
        }

        $view->requestRank = (new CateRank())::select('*')->get(); //대상직급 리스트
        $view->requestLecture = (new CateLecture())->getRequestLectureStr($id); //분야 문자열
        $view->lectureLargeCode = (new CateLecture())::select('large_cate', 'code', 'title')->where(DB::raw('length(code)'), '=', 4)->orderBy('large_cate')->orderBy('code')->get(); //강의 분야 - 중분류
        $view->lectureSmallCode = (new CateLecture())->getRequestLecture($id); //강의 분야 - 소분류 목록

        $view->info = $data;
	    $view->teacherList = $teacherList;
        return $view;

    }

    /**
     * 강의 상세 페이지 수정
     */
    function updateDB(Request $request) {
        $data = $request->input();

        $info = ClassRequest::find($data['id']);

        $info = $info->convSaveData($info, $request);

        $res = $info->save();

        if($res > 0) {
            $resChangeLecture = (new ClassRequestLecture())->changeRequestLecture(( (isset($data['lecture']) && $data['lecture']!="") ? explode(',', $data['lecture']) : array()), $data['id']); //출강분야 수정 결과
        }

        if($res > 0) {
            return redirect('/admin/class')->with('flash_message', '강의 수정 완료');
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }


}
