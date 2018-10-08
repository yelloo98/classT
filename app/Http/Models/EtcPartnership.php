<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtcPartnership extends Model
{
    use SoftDeletes;

    //협력사
    protected $table = 'etc_partnership';

    //##################################################################################################################
    //##
    //## >> Data Table List 협력사
    //##
    //##################################################################################################################
    public function dataTableList($request) {
        $columns = array(0 => 'id', 1 => 'name', 6 => 'created_at');

        //### 데이터 조회
        $data = $this->select('etc_partnership.*');

        $searchAll = $request->input('search')['value'];
        if($searchAll != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('etc_partnership.name', 'like', "%".$searchAll."%");
            });
        }

        //# 협력사명 검색
        $searchName = $request->input('columns')[1]['search']['value'];
        if($searchName != null) {
            $data->where("etc_partnership.name", "like", "%".$searchName."%");
        }

        //# 배너 이미지 노출 여부 검색
        $searchUseYN = $request->input('columns')[2]['search']['value'];
        if($searchUseYN != "all" && $searchUseYN != "") {
            $data->where("etc_partnership.use_yn", "=", $searchUseYN);
        }

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        $order = $request->input('order')[0]['column'];
        $order = ($order!="") ? $columns[$order] : 'id';
        $orderType = $request->input('order')[0]['dir'];
        $orderType = ($orderType!="") ? $orderType : 'DESC';

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count();
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy($order,$orderType)->get();

        foreach ($data as $item) {
            if($item->banner_img != null) {
                $item->banner_img = getFullS3Path($item->banner_img, 'PARTNER');
            }
        }

        $json['data'] = $data;
        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 데이터 등록 및 수정
    public function convSaveData($info, $request) {
        $convFieldArr = array("name", "banner_url", "use_yn");

        foreach ($convFieldArr as $key) {
            $info->{$key} = $request->input($key);
        }

        return $info;
    }
}
