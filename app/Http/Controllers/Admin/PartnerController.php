<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\EtcPartnership;
use EnjoyWorks\core\ResultFunction;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;

class PartnerController extends Controller
{
    //협력사 관리

    use ResultFunction;

    /**
     * 협력사 관리 페이지
    */
    function index(Request $request) {
        return $this->createView('/admin/partner/list', '협력사', '관리');
    }

    /**
     * [Ajax] 강사회원 리스트 정보 요청
     */
    function getList(Request $request) {
        return  (new EtcPartnership())->dataTableList($request);
    }

    /**
     * [ajax] 협력사 select
     * @param $id : 협력사 테이블 아이디 
     */
    function selectPartnerOne($id) {
        $info = EtcPartnership::find($id);

        if($info->id != "") {
            return $this->returnData($info);
        } else {
            return $this->returnFailed($info);
        }
    }

    /**
     * [ajax] 협력사 insert or update
    */
    function saveDB(Request $request) {
        $data = $request->input();

        if($data['id'] == null) {
            $info = new EtcPartnership();
        } else {
            $info = EtcPartnership::find($data['id']);
        }

        $info = $info->convSaveData($info, $request);

        $res = $info->save();

        //TODO 협력사 배너 이미지 등록
        if($request->hasFile('banner_img')) {
            $bannerImg = $request->file('banner_img');

            $ex =  $bannerImg->getClientOriginalExtension();

            $vFilePath = env('AWS_PARTNER_PATH');
            $vFileName = 'partner_'.$info->id.'.'.$bannerImg->extension();

            $resFileInfo = FileS3::upload($bannerImg, $vFilePath, $vFileName);

            $info->banner_img = $vFileName;

            $res = $info->save();
        }

        if($res > 0) {
            return $this->returnData($res);
        } else {
            return $this->returnFailed($res);
        }

    }

    /**
     * [ajax] 협력사 삭제
     * @param $id : 협력사 테이블 아이디
     */
    function deleteDB($id) {
        $info = EtcPartnership::find($id);

        //TODO 협력사 배너 이미지 삭제

        $res = $info->delete();

        if($res > 0) {
            return $this->returnData($res);
        } else {
            return $this->returnFailed($res);
        }

    }

}
