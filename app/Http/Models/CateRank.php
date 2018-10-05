<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CateRank extends Model
{
    //대상 직급
    protected $table = 'cate_rank';

    /**
     * 대상직급 카테고리 코드, 타이틀 얻어오기
     * 강사가 선택한 대상직급 코드라면 check_yn Y로 해서 반환할 것
     * @param $user_teacher_id : 강사 회원 아이디
     * @return array
     */
    public function getTeacherRank($user_teacher_id) {
        //### 데이터 조회
        $data = $this->select('cate_rank.code',
            'cate_rank.title',
            (DB::raw('IF(cate_rank.code in (select cate_rank_code from users_teacher_rank where users_teacher_id = '.$user_teacher_id.'), \'Y\', \'N\') AS check_yn')));

        return $data->get();
    }
}
