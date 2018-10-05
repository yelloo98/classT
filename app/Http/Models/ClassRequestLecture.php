<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRequestLecture extends Model
{
    //강의, 분야 카테고리 연결 테이블
    protected $table = 'class_request_lecture';

    /**
     * 강의 - 강의분야 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 분야 값들은 delete 후 넘어온 값으로 insert
     * @param $lecture : post로 넘어온 기업이 선택한 출강분야 코드값 배열
     * @param $class_request_id : 강의 고유 아이디
     * @return $res
     * */
    public function changeRequestLecture($lecture, $class_request_id) {
        $classRequestLecture = $this;

        $resDelGroup = $classRequestLecture ->where('class_request_id', '=', $class_request_id)->delete(); //강사 출강 단체 삭제

        $selBusiness = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($lecture) && !empty($lecture)) {
            foreach ($lecture as $item) {
                $selLecture[] = array('class_request_id' => $class_request_id, 'cate_lecture_code' => $item);
            }

            $res = $classRequestLecture->insert($selLecture);
        }

        return $res;

    }
}
