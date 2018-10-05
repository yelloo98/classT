<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UsersTeacherAttach extends Model
{
    //강사회원 첨부 테이블

    protected $table = 'users_teacher_attach';

    /**
     * 강사회원 첨부파일 insert
     * @param $file : 파일명 or 동영상 url
     * @param $fileType : 파일형태 : img or video
     * @param $imgType : 이미지형태
    **/
    public function insertTeacherAttach($usersTeacherId, $file, $fileType, $imgType = "") {
        return $this->insert(['users_teacher_id' => $usersTeacherId, 'file' => $file, 'file_type' => $fileType, 'img_type' => ($imgType =="" ? null : $imgType), 'created_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 강의사진 첨부파일 등록 아이디 반환
     * @param $usersTeacherId : 강사회원아이디
     * @return 등록아이디
    **/
    public function getInsertAttachClassId($usersTeacherId) {
        $info = $this->select(DB::raw("replace(file,'class_','') AS file"))->where('img_type', '=', 'class')->where('users_teacher_id', '=', $usersTeacherId)->orderBy('id', 'DESC')->first();

        if(!empty($info)) {
            $id = ($info->file) + 1;
        } else {
            $id = 1;
        }

        return $id;
    }

    /**
     * 강사 동영상 url 삭제
     * @param $usersTeacherId : 강사회원아이디
     * @param $videoIdAry : 첨부 테이블 아이디 배열
     * @param $videoUrlAry : 동영상 url 배열
    **/
    public function saveTeacherVideo($usersTeacherId, $videoIdAry = array(), $videoUrlAry = array()) {
        $res = 1;

        for ($i=0; $i<count($videoIdAry); $i++) {
            if($videoIdAry[$i] == 0) { //insert
                $res = $this->insert(['users_teacher_id' => $usersTeacherId, 'file' => $videoUrlAry[$i], 'file_type' => 'video', 'created_at' => date('Y-m-d H:i:s')]);
            } else { //update
                $data = $this->find($videoIdAry[$i]);
                $data->users_teacher_id = $usersTeacherId;
                $data->file = $videoUrlAry[$i];
                $data->file_type = 'video';
                $res = $data->save();
            }
        }

        return $res;
    }
}
