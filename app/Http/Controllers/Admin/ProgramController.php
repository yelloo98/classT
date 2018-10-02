<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\EtcProgram;

class ProgramController extends Controller
{

	/**
	 * [View] 리스트 페이지
	 * -----------------------------------------------------------------------------------------------------------------
	 */
    function index(Request $request) {
        return $this->createView('/admin/program/list', "교육프로그램", "목록");
    }

	/**
	 * [Ajax] 리스트 정보
	 * -----------------------------------------------------------------------------------------------------------------
	 */
    function getList(Request $request) {
    	return  (new EtcProgram())->dataTableList($request);
    }

	/**
	 * [View] 프로그램 상세보기(수정 or 등록)
	 * @param id : 교육프로그램 아이디
	 * -----------------------------------------------------------------------------------------------------------------
	 */
    function detail($id = 0) {
    	//## View 설정
        $view = $this->createView('admin/program/detail', "교육프로그램", "상세");
        $view->title = ($id == 0) ? '프로그램 등록' : '프로그램 수정';

        //## 게시글 정보 확인
        if($id > 0) {
	        $data = EtcProgram::find($id);

	        //# 값 체크
	        if($data == null) {
		        return redirect('/admin/program')->with('flash_message', "정상적인 경로가 아닙니다.");
	        }

	        //## 정보 추가
	        //# 작성자 이름 확인
	        $adminInfo = $data->admin()->first();
	        if($adminInfo != null) $data->writer_name = $adminInfo->name;

	        $view->info = $data;
        }

        return $view;

    }

	/**
	 * [Proc] 프로그램 등록 or 수정
	 * -----------------------------------------------------------------------------------------------------------------
	 */
    function saveDB(Request $request) {

        //# Request Data
        $data = $request->input();
        $rStr = "";

        if($data['id'] == null) {
            $info = new EtcProgram();
            $rStr = "등록";
        } else {
            $info = EtcProgram::find($data['id']);
            $rStr = "수정";
        }

        //# 삭제요청시
        if($data['edit_type'] == "delete") {
            $res = $info->delete();
            $rStr = "삭제";
        }
        //# 수정 & 등록시
        else {
            $info->writer_id = getAdminUserInfo('id');

            $info = $info->convSaveData($info, $request);

            $res = $info->save();

            if($res>0) { //프로그램 썸네일 이미지 저장
                $info = (new EtcProgram())->uoloadFile(EtcProgram::find($info->id), $request, $info->id);

                $res = $info->save();
            }
        }

        //# 결과처리
        if($res > 0) {
            return redirect('/admin/program')->with('flash_message', "프로그램 " . $rStr . " 완료");
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

}
