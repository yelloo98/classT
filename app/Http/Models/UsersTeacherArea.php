<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTeacherArea extends Model
{
    //강사 - 지역코드 연결 테이블

    protected $table = 'users_teacher_area';

    /**
     * 강사 회원 - 출강 지역 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 업종 값들은 delete 후 넘어온 값으로 insert
     * @param $area : post로 넘어온 강사 회원이 선택한 출강지역 코드값 배열
     * @param $users_teacher_id : 강사 회원 고유 아이디
     * @param $beforeArea : 변경전 출강지역
     * @return $res
     * */
    public function changeTeacherArea($area, $users_teacher_id, $beforeArea = array()) {
        if( empty( array_merge(array_diff($area, $beforeArea), array_diff($beforeArea, $area)) ) ) { //이전이랑 다른게 없는경우 변경 코드 실행하지않는다.
            return 1;
        }

        $userTeacherArea = $this;

        $resDelArea = $userTeacherArea->where('users_teacher_id', '=', $users_teacher_id)->delete(); //강사 출강 단체 삭제

        $selArea = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($area) && !empty($area)) {
            foreach ($area as $item) {
                $selArea[] = array('users_teacher_id' => $users_teacher_id, 'cate_area_code' => $item);
            }

            $res = $userTeacherArea->insert($selArea);
        }

        return $res;

    }
}
