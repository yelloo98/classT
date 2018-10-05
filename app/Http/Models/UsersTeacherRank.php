<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTeacherRank extends Model
{
    //강사회원 - 대상직급 연결 테이블
    protected $table = 'users_teacher_rank';

    /**
     * 강사 회원 - 대상직급 post로 넘어온 값 새로 설정해주기
     * 이전에 있던 단체 값들은 delete 후 넘어온 값으로 insert
     * @param $rank : post로 넘어온 강사 회원이 선택한 대상직급 코드값 배열
     * @param $users_teacher_id : 강사 회원 고유 아이디
     * @param $beforeRank : 변경전 직급 코드
     * @return $res
     * */
    public function changeTeacherRank($rank, $users_teacher_id, $beforeRank = array()) {
        if( empty( array_merge(array_diff($rank, $beforeRank), array_diff($beforeRank, $rank)) ) ) { //이전이랑 다른게 없는경우 변경 코드 실행하지않는다.
            return 1;
        }

        $userTeacherRank = $this;

        $resDelRank = $userTeacherRank->where('users_teacher_id', '=', $users_teacher_id)->delete(); //강사 출강 단체 삭제

        $selRank = array(); //사용자가 선택한 출강단체
        $res = 0;

        if(isset($rank) && !empty($rank)) {
            foreach ($rank as $item) {
                $selRank[] = array('users_teacher_id' => $users_teacher_id, 'cate_rank_code' => $item);
            }

            $res = $userTeacherRank->insert($selRank);
        }

        return $res;

    }

}
