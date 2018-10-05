<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTeacherBusiness extends Model
{
    //강사회원 - 출강업종 연결 테이블
    protected $table = 'users_teacher_business';

    /**
     * 강사 회원 - 출강 업종 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 업종 값들은 delete 후 넘어온 값으로 insert
     * @param $business : post로 넘어온 강사 회원이 선택한 출강업종 코드값 배열
     * @param $users_teacher_id : 강사 회원 고유 아이디
     * @param $beforeBusiness : 변경전 출강업종
     * @return $res
     * */
    public function changeTeacherBusiness($business, $users_teacher_id, $beforeBusiness = array()) {
        if( empty( array_merge(array_diff($business, $beforeBusiness), array_diff($beforeBusiness, $business)) ) ) { //이전이랑 다른게 없는경우 변경 코드 실행하지않는다.
            return 1;
        }

        $userTeacherBusiness = $this;

        $resDelGroup = $userTeacherBusiness->where('users_teacher_id', '=', $users_teacher_id)->delete(); //강사 출강 단체 삭제

        $selBusiness = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($business) && !empty($business)) {
            foreach ($business as $item) {
                $selBusiness[] = array('users_teacher_id' => $users_teacher_id, 'cate_business_code' => $item);
            }

            $res = $userTeacherBusiness->insert($selBusiness);
        }

        return $res;

    }

}
