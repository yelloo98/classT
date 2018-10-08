<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CateBusiness extends Model
{
    //출강업종
    protected $table = 'cate_business';

    /**
     * 출강 업종 카테고리 코드, 타이틀 얻어오기
     * 강사가 선택한 코드라면 check_yn을 Y로 해서 반환할 것
     * @param $user_teacher_id : 강사 회원 아이디
     * @return array
     */
    public function getTeacherBusiness($user_teacher_id) {
        //### 데이터 조회
        $data = $this->select('cate_business.code',
            'cate_business.title',
            (DB::raw('IF(cate_business.code in (select cate_business_code from users_teacher_business where users_teacher_id = '.$user_teacher_id.'), \'Y\', \'N\') AS check_yn')));

        return $data->get();
    }
}
