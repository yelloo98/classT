<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTeacherGroup extends Model
{
    //강사회원 - 출강단체 연결 테이블
    protected $table = 'users_teacher_group';

    /**
     * 강사 회원 - 출강 단체 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 단체 값들은 delete 후 넘어온 값으로 insert
     * @param $group : post로 넘어온 강사 회원 단체 코드값 배열
     * @param $users_teacher_id : 강사 회원 고유 아이디
     * @param $beforeGroup : 변경전 코드값
     * @return $res
     * */
    public function changeTeacherGroup($group, $users_teacher_id, $beforeGroup = array()) {

        if( empty( array_merge(array_diff($group, $beforeGroup), array_diff($beforeGroup, $group)) ) == true ) { //이전이랑 다른게 없는경우 변경 코드 실행하지않는다.
            return 1;
        }

        $userTeacherGroup = $this;

        $resDelGroup = $userTeacherGroup->where('users_teacher_id', '=', $users_teacher_id)->delete(); //강사 출강 단체 삭제

        $selGroup = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($group) && !empty($group)){
            foreach ($group as $item) {
                $selGroup[] = array('users_teacher_id' => $users_teacher_id, 'cate_group_code' => $item);
            }

            $res = $userTeacherGroup->insert($selGroup);
        }

        return $res;

    }

}
