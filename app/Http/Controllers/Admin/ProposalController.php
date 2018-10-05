<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\ClassRecommend;
use App\Http\Models\RequestProposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\ClassProposal;
use App\Http\Models\ClassRequest;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;
use EnjoyWorks\core\ResultFunction;

class ProposalController extends Controller
{
    //강사가 클래스이음에게 요청한 제안서 관리

    use ResultFunction;

    /**
     * 제안서관리 목록
    */
    function index() {
        return $this->createView('/admin/proposal/list', '제안서 관리', '목록');
    }

    /**
     * [Ajax] 리스트 정보
     * -----------------------------------------------------------------------------------------------------------------
     */
    function getList(Request $request) {
        return  (new RequestProposal())->dataTableClasseumList($request);
    }

    /**
     * 제안서 상세페이지 이동
     * @param $id : 요청제안서 테이블 아이디
     */
    function detail($id) {
        $view = $this->createView('/admin/proposal/detail', '제안서 관리', '상세보기');

        $data = new RequestProposal();

        $data = $data->selectClasseumProposalOne($id);

        $mRequest = new ClassRequest();
        $data->class_field = $mRequest->getClassField($data->class_request_id); //강의 분야

        $view->info = $data;
        debug( $data);
        return $view;
    }

    /**
     * [Proc] 제안서 파일 수정
     * -----------------------------------------------------------------------------------------------------------------
     */
    function saveDB(Request $request) {

        $seqID = $request->input('id');

        $info = RequestProposal::find($seqID);

        //TODO 제안서 pdf_name 파일 저장하기
        if($request->hasFile('pdf_name')) {
            $pdf_name = $request->file('pdf_name');
            $recommendInfo = ClassRecommend::find($info->class_recommend_id);

            $vFilePath = env('AWS_PROPOSAL_REQUEST_PATH').'/'.(($info->request_type == 'classeum') ? 0 : $info->mentor_id).'/'. $seqID .'/';
            $vFileName = $pdf_name->getClientOriginalName();

            $resFileInfo = FileS3::upload($pdf_name, $vFilePath, $vFileName);


            if(!empty($resFileInfo)) {
                $info->pdf_name = $vFileName;
            }
        }

        $res = $info->save();

        if($res > 0) {
            return redirect('/admin/proposal')->with('flash_message', "제안서 수정 완료");
        } else {
            return back()->with('flash_error', "처리중 오류가 발생하였습니다. 확인후 다시 시도해주세요.");
        }

    }

    /**
     * [ajax]클래스이음 전달 제안서 삭제
     * @param $id : request_proposal 고유 아이디
    */
    function delProposalFile($id) {
        $info = RequestProposal::find($id);
        $recommendInfo = ClassRecommend::find($info->class_recommend_id);


        $vFilePath = env('AWS_PROPOSAL_REQUEST_PATH').'/'.$info->request_type.'/'.$recommendInfo->users_teacher_id.'/'.$recommendInfo->class_request_id.'/'.$info->pdf_name;

        FileFunc::deletePath($vFilePath); //S3에 올라가져 있는 파일 삭제

        $info->pdf_name = null;

        $res = $info->save();

        if($res>0) {
            return $this->returnSuccess('제안서 삭제 성공',$res);
        } else {
            return $this->returnFailed('제안서 삭제 실패');
        }
    }

}
