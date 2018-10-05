<?php

namespace App\Http\Controllers\Api;

use App\Http\Models\CateArea;
use App\Http\Models\CateLecture;
use App\Http\Models\ClassRecommend;
use App\Http\Models\ClassRequest;
use App\Http\Models\RequestProposal;
use App\Http\Models\TeacherSchedule;
use App\Http\Models\UsersTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;
use EnjoyWorks\core\ResultFunction;

class ApiController extends Controller
{
    use ResultFunction;

	/**
	 * [Ajax] 선생님 스케쥴 정보
	 * -----------------------------------------------------------------------------------------------------------------
	 * @param $id : 강사 아이디
	 * @param $month : 조회 월
	 */
	function getScheduleData($id) {

		$data = new TeacherSchedule();
		$data = $data->getScheduleTable($id);

		return response()->json($data);

	}

    /**
     * [Ajax] 멘티 강사 회원 목록
     * -----------------------------------------------------------------------------------------------------------------
     * @param $id : 강사 아이디
     */
    function getMenteeData($id) {

        $data = new UsersTeacher();

        //# 0일 경우에는 ALL
        if($id > 0) {
            $data = $data->where('recommend_user_id', '=', $id);
        }

        $data = $data->orderBy('id', 'DESC');

        $data = $data->get();

        return response()->json($data);
    }

    /**
     * [ajax]강사 찾기
     * @param $id : users_teacher_id
     * @param $searchType : 검색 타입 [default : name]
     * @param $searchWord : 검색어
     * @param $isMentor : 멘토 검색인지 true : 멘토검색, false : 멘토검색아님
     */
    function getSearchTeacher($id=0, $searchType="", $searchWord="", $isMentor = "false") {
        $teacher = new UsersTeacher();

        if( $searchType !="" && $searchWord !="" ){ //검색 조건 추가
            $teacher = $teacher->where($searchType, 'like', '%' . $searchWord . '%');
        }

        $teacher = $teacher->where('users_teacher.status', '=', '3'); //승인받은 회원만 추천

        if($id>0) { //강사 아이디가 넘어온 경우 본인 제외한 강사 목록 뿌리기
            $teacher = $teacher->whereNotIn('users_teacher.id', [$id]);
        }

        if($isMentor == "true") { //멘토 검색인 경우 해당 멘토의 멘티가 10명이하인경우에만 출력
            $teacher = $teacher->where(DB::raw("(select count(X.id) from users_teacher X where X.recommend_user_id = users_teacher.id)"), '<=', 'users_teacher.mentee_max_num');

        }
        $teacher = $teacher->get();

        return response()->json($teacher);
    }

    /**
     * [ajax]
     * 강의 분야 찾기
     * @param $searchWord : 찾고자 하는 분야 title
     * @return lecture list
    */
    function getSearchLecture($searchWord = "", $notInCode = "") {
        $cateLecture = (new CateLecture())::select('*')->where(DB::raw('length(code)'), '=', 7);

        if($searchWord !="") {
            $cateLecture = $cateLecture->where('title', 'like', '%'.$searchWord.'%');
        }

        if(trim($notInCode) != "") {
            $cateLecture = $cateLecture->whereNotIn('code', explode(',', $notInCode));
        }

        $cateLecture = $cateLecture->orderBy('code', 'ASC')->get();

        return response()->json($cateLecture);
    }

    /**
     * S3 클래스이음 파일 다운로드
     * @param $id
     * @param $fileName : 파일명
     * @param $type : aws경로 타입
     * @return download file
     */
    function fileDownload($id=0, $fileName, $type) {

        if($type == 'PROPOSAL_REQUEST') { //강사 요청 제안서
            $info = RequestProposal::find($id);
            $vFile = FileS3::getFIle(env('AWS_'.$type.'_PATH').'/'.(($info->request_type == 'classeum') ? 0 : $info->mentor_id).'/'. $id .'/'.$info->pdf_name);

        } else if($type == 'PROPOSAL_TEACHER') { //강사 제안서
            //id : 추천강사아이디
            $info = ClassRecommend::select('class_request_id', 'users_teacher_id')->where('id', '=', $id)->first();
            $vFile = FileS3::getFIle( env('AWS_'.$type.'_PATH'). $info->users_teacher_id . '/'. $info->class_request_id . '/' . $fileName );

        } else { //그밖에
            $vFile = FileS3::getFIle( env('AWS_'.$type.'_PATH').$id.'/'.$fileName );

        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'filename'=> $fileName
        ];

        return response($vFile, 200, $headers);
    }

    /**
     * 지역코드 얻기
     * */
    function getCateArea($areaType, $searchWord) {
        $data = (new CateArea())::select("*")->where($areaType, '=', $searchWord)->first();

        return response()->json($data);
    }

    /**
     * [ajax] 추천강사 상태값 변경하기
     * @param $recommendId : 추천강사 테이블 고유 아이디
     * @param $changeStatus[int] : 수정될 상태
     */
    function changeRequestStatus($recommendId, $changeStatus) {
        $data = ClassRecommend::find($recommendId);
        //request:의뢰요청|p_request:제안서요청|p_revirw:제안서리뷰|p_send:제안서발송|c_request:강의요청|c_call:출강요청|c_confirm:강의확정
        //1:의뢰요청|2:제안서요청|3:제안서리뷰|4:제안서발송|5:강의요청|6:출강요청|7:강의확정
        $dtAry = array( 2=> "p_request_dt", 4 => "p_send_dt", 5 => "c_request_dt", 6 => "c_call_dt", 7 => "c_confirm_dt");

        if($changeStatus != 2) { //제안서 발송일자 or 출강요청일자
            $data->{$dtAry[$changeStatus]} = date("Y-m-d H:i:s");
        }

        $data->status = $changeStatus;

        $res = $data->save();

        if($res>0) {
            return $this->returnSuccess($res, '수정 성공');
        } else {
            return $this->returnFailed('처리중 문제가 발생하였습니다.');
        }
    }

    /**
     * [ajax] 추천 거절
     * @param $id : 추천 강사 테이블 고유 아이디
     * @param $type : 거절 형태 - teacher :  강사가 거절, company : 기업이 거절
     **/
    function refuseClass($recommendId, $type) {
        $info = ClassRecommend::find($recommendId);

        $info->refuse_type = $type;

        $res = $info->save();

        if($res>0) {
            return $this->returnSuccess($res, '거절 성공');
        } else {
            return $this->returnFailed('처리중 문제가 발생하였습니다.');
        }
    }

    /**
     * [ajax] 추천 강사 목록 가져오기 추천상태 or 삭제
     * @param $requestId : 프리미엄 요청 테이블 고유 아이디
     * @param $status : 강사 상태 : recommend or refuse
     */
    function getRecommendTeacherList($requestId, $status = 'recommend') {
        return (new ClassRecommend())->getRecommendTeacherList($requestId, $status);
    }

    /**
     * [ajax] 최근 강의 의뢰 정보 가져오기
     * @param $id : 가져올 강의 의뢰 고유 아이디
     **/
    function getRecentClassData($id) {
        $info = (new ClassRequest())->selectRequestOne($id);

        return response()->json($info);
    }

}
