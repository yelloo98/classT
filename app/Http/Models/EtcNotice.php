<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class EtcNotice extends Model
{
    use SoftDeletes;

    //공지사항
    protected $table = 'etc_notice';

    public function admin()
    {
        return $this->hasOne( 'App\Http\Models\UsersAdmin', 'id', 'writer_id');
        //select * from user_admin where id = #{writer_id};
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 공지사항
    //##
    //##################################################################################################################
    public function dataTableList($request) {
        $columns = array(0 => 'id', 1 => 'title', 3 => 'created_at');

        //### 데이터 조회
        $data = $this->select('etc_notice.id',
            'etc_notice.title',
            'etc_notice.content',
            'etc_notice.created_at',
            'users_admin.name AS writer_name')
            ->leftJoin('users_admin', 'etc_notice.writer_id', '=', 'users_admin.id');


        //### 검색조건
        //#전체 검색 정보
        $searchAll = $request->input('search')['value'];
        if($searchAll != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('etc_notice.title', 'like', "%".$searchAll."%")
                    ->orWhere('users_admin.name', 'like', "%".$searchAll."%");
            });
        }
        //# 제목 검색 정보
        $searchTitle = $request->input('columns')[1]['search']['value'];
        if($searchTitle != null) {
            $data->where("etc_notice.title", "like", "%".$searchTitle."%");
        }
/*        //# 작성자 검색 정보
        $searchName= $request->input('columns')[2]['search']['value'];
        if($searchName != null) {
            $data->where("users_admin.name", "like", "%$searchName%");
        }*/

        $order = $request->input('order')[0]['column'];
        $order = ($order!="") ? $columns[$order] : 'id';
        $orderType = $request->input('order')[0]['dir'];
        $orderType = ($orderType!="") ? $orderType : 'DESC';

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->offset($limitStart)->limit($limitlength)->orderBy($order, $orderType)->get();

        return response()->json($json);
    }

}
