<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;

class EtcQna extends Model
{
    //문의사항

    use SoftDeletes;

    protected $table = 'etc_qna';

    public function companyUser()
    {
        return $this->hasOne( 'App\Http\Models\UsersCompany', 'id', 'writer_id');
    }

    public function teacherUser()
    {
        return $this->hasOne( 'App\Http\Models\UsersTeacher', 'id', 'writer_id');
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 기업회원 문의사항
    //##
    //##################################################################################################################
    public function dataTableList($request, $memberId = 0, $type = "") {
        $columns = array(0 => 'etc_qna.id', 1 => 'writer_type', 2 => 'title', 3 => 'writer_name', 4 => 'responded_yn', 5 => 'created_at'); //table column

        //### 데이터 조회
        $data = $this->select('etc_qna.id',
            'etc_qna.title',
            'etc_qna.created_at',
            DB::raw("IF(etc_qna.writer_type = 'company', '기업', '강사') as writer_type"),
            DB::raw("IF(etc_qna.writer_type = 'company', users_company.name, users_teacher.name) AS writer_name"),
            DB::raw("IF(etc_qna.responded_at is null, '미완료', '답변완료') AS responded_yn")
            )
            ->leftJoin('users_company', 'etc_qna.writer_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'etc_qna.writer_id', '=', 'users_teacher.id');

        $order = "etc_qna.id";
        $orderType = "DESC";

        //### 검색조건
        if($memberId == 0) {
            //#전체 검색 정보
            if( ($searchAll = $request->input('search')['value']) != "") {
                $data->where(function ($query) use ($searchAll) {
                    $query->where('etc_qna.title', 'like', "%".$searchAll."%")
                        ->orWhere('users_company.name', 'like', "%".$searchAll."%")
                        ->orWhere('users_teacher.name', 'like', "%".$searchAll."%");
                });
            }
            //# 제목 검색 정보
            if( ($searchTitle = $request->input('columns')[1]['search']['value']) != "") {
                $data->where("etc_qna.title", "like", "%".$searchTitle."%");
            }

            //# 작성자 검색 정보
            $searchName= $request->input('columns')[2]['search']['value'];
            if( ($searchName = $request->input('columns')[2]['search']['value']) != "") {
                $data->where(function ($query) use ($searchName) {
                    $query->where('users_company.name', 'like', "%".$searchName."%")
                        ->orWhere('users_teacher.name', 'like', "%".$searchName."%");
                });
            }

            //# 답변 현황 검색
            if( ($searchResponseYN = $request->input('columns')[3]['search']['value']) != null && $searchResponseYN!="all") {
                if($searchResponseYN == 'Y') {
                    $data->whereNotNull("etc_qna.responded_at");
                } else {
                    $data->whereNull("etc_qna.responded_at");
                }
            }

            //# 요청형태 검색 정보
            if(($searchType = $request->input('columns')[4]['search']['value']) != null && $searchType!="all") {
                $data->where("etc_qna.writer_type", "=", $searchType);
            }

            $order = $request->input('order')[0]['column'];
            $order = ($order!="") ? $columns[$order] : 'id';
            $orderType = $request->input('order')[0]['dir'];
            $orderType = ($orderType!="") ? $orderType : 'DESC';
        } else {
            $data->where('etc_qna.writer_id', $memberId);
        }

        if($type!="") {
            //날짜 비교
            if( ($searchStartDt = $request->input('columns')[1]['search']['value']) != "" && ($searchEndDt = $request->input('columns')[2]['search']['value']) !="") {
                $dataArr = array($searchStartDt, $searchEndDt);
                $data->where(function ($query) use ($dataArr) {
                    $query = $query->whereIn('class_request.start_dt', $dataArr)->orWhereIn('class_request.end_dt', $dataArr);

                });
            }
            $data->where('etc_qna.writer_type', '=', $type);
        }

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

    //##################################################################################################################
    //##
    //## >> Data Table List -- 문의사항
    //##
    //##################################################################################################################
    public function selectQnaOne($id) {
        //### 데이터 조회
        $data = $this->select('etc_qna.*',
            DB::raw("IF(etc_qna.writer_type = 'company', users_company.name, users_teacher.name) AS writer_name"))
            ->leftJoin('users_company', 'etc_qna.writer_id', '=', 'users_company.id')
            ->leftJoin('users_teacher', 'etc_qna.writer_id', '=', 'users_teacher.id');

        $data->where('etc_qna.id', '=', $id);
        return $data->first();
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 데이터 등록 및 수정
    public function convSaveData($info, $request) {
        $convFieldArr = array("title", "content", 'writer_type');

        foreach ($convFieldArr as $key) {
            $info->{$key} = $request->input($key);
        }

        return $info;
    }

    /**
     * 문의사항 첨부파일 올리기
    */
    public function uoloadFile($info, $request, $seqId) {
        if($request->hasFile('content_file')) {
            $file = $request->file('content_file');

            $vFilePath = env('AWS_QNA_PATH').$seqId.'/';
            $vFileName = $file->getClientOriginalName();

            $resFileInfo = FileS3::upload($file , $vFilePath, $vFileName);

            if(!empty($resFileInfo)) {
                $info->content_file = $vFileName;
            }
        }

        return $info;
    }

    /**
     * S3 QNA 첨부파일다운로드
     * @param $id : etc_qna 고유 아이디
     */
    function fileDownload($fileName, $fileUrl) {
        $vFile = FileS3::getFIle($fileUrl);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'filename'=> $fileName
        ];

        return response($vFile, 200, $headers);
    }
}
