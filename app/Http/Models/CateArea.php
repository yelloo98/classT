<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CateArea extends Model
{
    //지역 코드 테이블
    protected $table = 'cate_area';

    /**
     * 지역 코드, 명칭 얻어오기
     * 강사가 선택한 지역이면 check_yn을 Y로 해서 반환할 것
     * @param $user_teacher_id : 강사 회원 아이디
     * @return array
     */
    public function getTeacherArea($user_teacher_id) {
        //### 데이터 조회
        $data = $this->select('cate_area.code',
            'cate_area.title',
            (DB::raw('IF(cate_area.code in (select cate_area_code from users_teacher_area where users_teacher_id = '.$user_teacher_id.'), \'Y\', \'N\') AS check_yn')));

        return $data->get();
    }
}
