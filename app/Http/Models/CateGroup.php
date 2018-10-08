<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CateGroup extends Model
{
    //출강단체 카테고리
    protected $table = 'cate_group';

    /**
     * 출강 단체 카테고리 코드, 타이틀 얻어오기
     * 강사가 선택한 코드라면 check_yn을 Y로 해서 반환할 것
     * @param $user_teacher_id : 강사 회원 아이디
     * @return array
    */
    public function getTeacherGroup($user_teacher_id) {
        //### 데이터 조회
        $data = $this->select('cate_group.code',
            'cate_group.title',
            (DB::raw('IF(cate_group.code in (select cate_group_code from users_teacher_group where users_teacher_id = '.$user_teacher_id.'), \'Y\', \'N\') AS check_yn')));

        return $data->get();
    }

}
