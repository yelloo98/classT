<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\UsersAdmin;
use App\Http\Models\UsersCompany;
use App\Http\Models\UsersTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\EtcQna;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;

class QnaController extends Controller
{
    //문의사항

    /**
     * 문의사항 목록
    */
    function index() {
        return $this->createView('/admin/qna/list', '문의사항', '목록');
    }

    /**
     * [Ajax] 문의사항 리스트 정보
     * -----------------------------------------------------------------------------------------------------------------
     */
    function getList(Request $request) {
        return  (new EtcQna())->dataTableList($request);
    }

    /**
     * 문의사항 상세보기
     * @param $id :  문의사항 아이디
    */
    function detail($id = 0) {
        $view = $this->createView('/admin/qna/detail', '기업회원 문의사항','상세');
        $view->title = '문의사항 ' . ($id == 0) ? '등록' : '수정';

        if($id == 0) {
            $info = new EtcQna();
        } else {
            $info = EtcQna::find($id);
	        //# 문의하기 작성자명 확인
	        $writerStr = ($info->writer_type == "COMPANY"? new UsersCompany : new UsersTeacher)::find($info->writer_id)->name;
	        $info->writer = $writerStr;
        }

        //# 답변 작성자명 확인
        if($info->res_admin_id > 0) {
	        $info->res_admin_str = UsersAdmin::find($info->res_admin_id)->name;
        } else {
	        $info->res_admin_str = getAdminUserInfo('name');
        }

        $view->info = $info;

        return $view;

    }

    /**
     * 문의사항 답변 update
    **/
    function updateDB(Request $request) {
        $data = $request->input();

        $info = EtcQna::find($data['id']);
        
        foreach (array("res_content") as $key) {
            $info->{$key} = $data[$key];
        }

        $info->res_admin_id = getAdminUserInfo('id');
        $info->responded_at = date("Y-m-d H:i:s");

        $res = $info->save();

        if($res > 0) {
            return redirect('/admin/qna')->with('flash_message', "문의사항 답변 등록 완료");
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }
    }

}
