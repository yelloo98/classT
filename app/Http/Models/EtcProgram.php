<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;

class EtcProgram extends Model
{
    //교육 프로그램 테이블
    protected $table = "etc_program";

	//##################################################################################################################
	//##
	//## >> Relationships
	//##
	//##################################################################################################################

    public function admin() {
        return $this->belongsTo( '\App\Http\Models\UsersAdmin', 'writer_id', 'id');
    }

	//##################################################################################################################
	//##
	//## >> Data processing
	//##
	//##################################################################################################################
	//# 데이터 등록 및 수정
	public function convSaveData($info, $request) {
    	$convFieldArr = array("title", "content", "program_topic");

		foreach ($convFieldArr as $key) {
			$info->{$key} = $request->input($key);
		}
		$info->writer_id = getAdminUserInfo('id');
		return $info;
	}

    /**
     * 교육 프로그램 썸네일 이미지 파일 올리기
     */
    public function uoloadFile($info, $request, $seqId) {
        if($request->hasFile('thumbnail_name')) {
            $file = $request->file('thumbnail_name');

            $vFilePath = env('AWS_PROGRAM_THUMBNAIL_PATH').$seqId.'/';
            $vFileName = $file->getClientOriginalName();

            $resFileInfo = FileS3::upload($file , $vFilePath, $vFileName);

            if(!empty($resFileInfo)) {
                $info->thumbnail_name = $vFileName;
            }
        }

        return $info;

    }

	//##################################################################################################################
	//##
	//## >> Data Table List
	//##
	//##################################################################################################################
	public function dataTableList($request) {
        $columns = array(0 => 'etc_program.id', 1 => 'etc_program.title', 3 => 'etc_program.created_at');

    	//### 데이터 조회
		$data = $this->select('etc_program.id',
							  'etc_program.title',
							  'etc_program.content',
							  'etc_program.created_at',
							  'etc_program.thumbnail_name',
							  'etc_program.program_topic',
							  'users_admin.name AS writer_name')
                            ->leftJoin('users_admin', 'etc_program.writer_id', '=', 'users_admin.id');

		
		//### 검색조건
		//#전체 검색 정보
		$searchAll = $request->input('search')['value'];
		if($searchAll != null) {
			$data->where(function ($query) use ($searchAll) {
				$query->where('etc_program.title', 'like', "%".$searchAll."%")
				      ->orWhere('users_admin.name', 'like', "%".$searchAll."%");
			});
		}
		//# 제목 검색 정보
		$searchTitle = $request->input('columns')[1]['search']['value'];
		if($searchTitle != null) {
			$data->where("etc_program.title", "like", "%".$searchTitle."%");
		}
		//# 작성자 검색 정보
		$searchContent= $request->input('columns')[2]['search']['value'];
		if($searchContent != null) {
			$data->where("etc_program.content", "like", "%".$searchContent."%");
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

		foreach($data as $item) {
            $item->thumbnail_name = ($item->thumbnail_name!="")? (env('AWS_URL').env('AWS_PROGRAM_THUMBNAIL_PATH').$item->id.'/'.$item->thumbnail_name ): "";
        }

		$json['data'] = $data;

		return response()->json($json);
	}

}
