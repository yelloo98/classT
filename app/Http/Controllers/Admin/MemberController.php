<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\CateArea;
use App\Http\Models\CateLecture;
use App\Http\Models\CateRank;
use App\Http\Models\TeacherGrade;
use App\Http\Models\UsersTeacherArea;
use App\Http\Models\UsersTeacherBusiness;
use App\Http\Models\UsersTeacherLecture;
use App\Http\Models\UsersTeacherRank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\UsersCompany;
use App\Http\Models\ClassRequest;
use App\Http\Models\UsersTeacher;
use App\Http\Models\CateBusiness;
use App\Http\Models\UsersTeacherGroup;
use App\Http\Models\ClassRecommend;
use App\Http\Models\CateGroup;
use EnjoyWorks\core\ResultFunction;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;

class MemberController extends Controller
{
    use ResultFunction;

    /**
     * 기업회원 목록
    */
    function companyUserList(Request $request) {
        return $this->createView('/admin/member/company/list', '기업회원', '목록');
    }

    /**
     * [Ajax] 기업회원 리스트 정보 요청
     * -----------------------------------------------------------------------------------------------------------------
     */
    function getCompanyUserList(Request $request) {
        return  (new UsersCompany())->dataTableList($request);
    }

    /**
     * 기업회원 상세보기
     * @param $id : 기업회원 아이디
    */
    function companyUserDetail($id = 0) {
        $view = $this->createView('/admin/member/company/detail', '기업회원', '상세');

        if($id == 0) {
            $data = new UsersCompany();
        } else {
            $data = UsersCompany::find($id);
        }

        $view->info = $data;

        return $view;
    }
    
    /**
     * 기업 회원 수정
    */
    function companyUserUpdate(Request $request) {
        $info = UsersCompany::find($request->input('id'));

        $info = $info->convSaveData($info, $request);

        $res = $info->save();

        if($res>0) { //기업회원 사업자 등록증 서버에 올리기
            $info = $info->uoloadFile(UsersCompany::find($info->id), $request, $info->id);
            $res = $info->save();
        }

        if($res > 0) {
            return redirect('/admin/member/company')->with('flash_message', '기업 회원 수정 완료');
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

    /**
     * 기업 회원 삭제하기
     * @param $id : 기업회원 아이디
    */
    function companyUserDelete($id) {
        $info = UsersCompany::find($id);

        //TODO 이미지가 있는 경우 삭제하기

        $res = $info->delete();

        if($res > 0) {
            return redirect('/admin/member/company')->with('flash_message', '기업 회원 삭제 완료');
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

    /**
     * 기업회원 강의현황 목록 요청
    */
    function getCompanyClassList(Request $request) {
        return (new ClassRequest())->dataTableList($request, 'company', 'company');
    }

    /**
     * 기업회원 프리미엄 의뢰 현황 목록 요청
    */
    function getCompanyRequestList(Request $request) {
        return (new ClassRequest())->dataTableList($request, 'premium', 'company');
    }

    /**
     * 기업회원 정산 현황 목록 요청
    */
    function getCompanyOrderList(Request $request) {
        return null;
    }

    /**
     * 강사회원 목록
     */
    function teacherUserList(Request $request) {
        $view = $this->createView('/admin/member/teacher/list', '강사회원', '목록');
        return $view;
    }

    /**
     * [Ajax] 강사회원 리스트 정보 요청
     * -----------------------------------------------------------------------------------------------------------------
     */
    function getTeacherUserList(Request $request) {
        $mUsersTeacher = new UsersTeacher();
        return  $mUsersTeacher->dataTableList($request);
    }

    /**
     * 강사회원 상세보기
     * @param $id : 강사회원 아이디
     */
    function teacherUserDetail($id) {
        $view = $this->createView('/admin/member/teacher/detail', '강사회원', '상세');

        $view->teacherGroup = (new CateGroup())->getTeacherGroup($id); //출강단체 리스트
        $view->teacherBusiness = (new CateBusiness())->getTeacherBusiness($id); //출강업종 리스트
        $view->teacherRank = (new CateRank())->getTeacherRank($id); //대상직급 리스트
        $view->teacherLecture = (new CateLecture())->getTeacherLectureStr($id); //분야 문자열
        $view->teacherArea = (new CateArea())->getTeacherArea($id); //출강지역
        $view->teacherGrade = (new TeacherGrade())->getTeacherGrade($id); //회원 등급 리스트

        $view->lectureLargeCode = CateLecture::select('large_cate', 'code', 'title')->where(DB::raw('length(code)'), '=', 4)->orderBy('large_cate')->orderBy('code')->get(); //강의 분야 - 중분류
        $view->lectureSmallCode = (new CateLecture())->getTeacherLecture($id); //강의 분야 - 소분류 목록

        $view->info = (new UsersTeacher())->selectTeacherOne($id);

        return $view;
    }

    /**
     * 강사 회원 수정
     */
    function teacherUserUpdate(Request $request) {
        $data = $request->input();

        $info = UsersTeacher::find($data['id']);

        //TODO 정보 저장 필드 설정
        $info = $info->convSaveData($info, $request);

        $res = $info->save();

        if($res>0) { //강사회원 파일(최종학위증명서, 통장사본) 업로드 처리
            $res = $info->uoloadFile($request, $info->id);
        }

        if($res > 0) {
            $resChangeGroup = (new UsersTeacherGroup())->changeTeacherGroup((isset($data['group']) ? $data['group'] : array()), $data['id']); //출강단체 수정 결과
            $resChangeRank = (new UsersTeacherRank())->changeTeacherRank( (isset($data['rank']) ? $data['rank'] : array()), $data['id']); //대상직급 수정 결과
            $resChangeBusiness = (new UsersTeacherBusiness())->changeTeacherBusiness((isset($data['business']) ? $data['business'] : array()), $data['id']); //출강업종 수정 결과
            $resChangeLecture = (new UsersTeacherLecture())->changeTeacherLecture(( (isset($data['lecture']) && $data['lecture']!="") ? explode(',', $data['lecture']) : array()), $data['id']); //출강분야 수정 결과
            $resChangeArea = (new UsersTeacherArea())->changeTeacherArea( (isset($data['area']) ? $data['area'] : array()), $data['id']); //출강지역 수정 결과
        }

        if($res > 0) {
            return redirect('/admin/member/teacher')->with('flash_message', '기업 회원 수정 완료');
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

    /**
     * 강사 회원 삭제하기
     * @param $id : 강사회원 아이디
     */
    function teacherUserDelete($id) {
        $info = UsersTeacher::find($id);

        //TODO 이미지가 있는 경우 삭제하기

        $res = $info->delete();

        if($res > 0) {
            return redirect('/admin/member/teacher')->with('flash_message', '기업 회원 삭제 완료');
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

    /**
     * [Ajax] 프리미엄 연결 목록
     */
    function getPremiumList(Request $request, $teacherId) {
        $mRequest = new ClassRequest();

        return  $mRequest->requestDataTableList($request, 'contact', $teacherId);
    }

    /**
     * 강사회원 강의현황 목록 요청
     */
    function getTeacherClassList(Request $request) {
        return (new ClassRequest())->dataTableList($request, 'company', 'teacher');
    }

    /**
     * 강사회원 프리미엄 의뢰 현황 목록 요청
     */
    function getTeacherRequestList(Request $request) {
        return (new ClassRequest())->dataTableList($request, 'premium', 'teacher');
    }

    /**
     * [ajax] 프리미엄 의뢰 - 강사 연결하기
     * 추천강사로 등록할 것
    */
    function contactRequest($teacherId, $reuqestId) {
        $data = new ClassRecommend();

        $data->users_teacher_id = $teacherId;
        $data->class_request_id = $reuqestId;
        $data->status = 2; //제안서 요청으로 상태값 등록

        $res = $data->save();

        if($res>0) {
            return $this->returnSuccess($res, '프리미엄 의뢰 연결에 성공하였습니다.');
        } else {
            return $this->returnFailed('프리미엄 의뢰 연결에 실패하였습니다.');
        }
    }

}
