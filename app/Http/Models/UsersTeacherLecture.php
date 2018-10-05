<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTeacherLecture extends Model
{
    //강사회원 - 강의분야 연결 테이블
    protected $table = 'users_teacher_lecture';

    /**
     * 강사 회원 - 강의분야 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 분야 값들은 delete 후 넘어온 값으로 insert
     * @param $lecture : post로 넘어온 강사 회원이 선택한 출강분야 코드값 배열
     * @param $users_teacher_id : 강사 회원 고유 아이디
     * @param $beforeLecture : 변경전 강의 분야
     * @return $res
     * */
    public function changeTeacherLecture($lecture, $users_teacher_id, $beforeLecture = array()) {
        if( empty( array_merge(array_diff($lecture, $beforeLecture), array_diff($beforeLecture, $lecture)) ) ) { //이전이랑 다른게 없는경우 변경 코드 실행하지않는다.
            return 1;
        }

        $userTeacherLecture = $this;

        $resDelGroup = $userTeacherLecture->where('users_teacher_id', '=', $users_teacher_id)->delete(); //강사 출강 단체 삭제

        $selBusiness = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($lecture) && !empty($lecture)) {
            foreach ($lecture as $item) {
                $selLecture[] = array('users_teacher_id' => $users_teacher_id, 'cate_lecture_code' => $item);
            }

            $res = $userTeacherLecture->insert($selLecture);
        }

        return $res;

    }
}
