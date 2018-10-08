<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\EtcNotice;

class NoticeController extends Controller
{

    /**
     * 공지사항 목록
     */
    function index() {
        return $this->createView('/admin/notice/list', "공지사항", "목록");
    }

    /**
     * [Ajax] 공지사항 리스트 정보
     */
    function getList(Request $request) {
        return  (new EtcNotice())->dataTableList($request);
    }

    /**
     * 공지사항 상세보기(수정 or 등록)
     * @param id : 공지사항 테이블 아이디
     */
    function detail($id = 0) {

        $view = $this->createView('admin/notice/detail', "공지사항", "상세");
        $view->title = ($id == 0) ? '공지사항 등록' : '공지사항 수정';

        if($id == 0) {
            $data = new EtcNotice();
        } else {
            $data = EtcNotice::find($id);
        }

        $view->info = $data;

        if($data->writer_id>0) {
            $data->writer = ($data->writer_id!="") ? $data->admin()->first()->name : "";
        }

        return $view;

    }

    /**
     * 공지사항 등록 or 수정
     */
    function saveDB(Request $request) {

    	//# Request Data
        $data = $request->input();
        $rStr = "";

	    if($data['id'] == null) {
		    $info = new EtcNotice();
            $rStr = "등록";
	    } else {
		    $info = EtcNotice::find($data['id']);
            $rStr = "수정";
	    }

	    //# 삭제요청시
        if(isset($data['edit_type']) && ($data['edit_type']) == "delete") {
	        $res = $info->delete();
            $rStr = "삭제";
        }
        //# 수정 & 등록시
        else {
	        $info->writer_id = getAdminUserInfo('id');
	        foreach (array("title", "content") as $key) {
		        $info->{$key} = $data[$key];
	        }

	        $res = $info->save();
        }

        //# 결과처리
        if($res > 0) {
            return redirect('/admin/notice')->with('flash_message', "공지사항 " . $rStr . " 완료");
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

}
