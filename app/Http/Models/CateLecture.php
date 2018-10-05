<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class CateLecture extends Model
{
    use SoftDeletes;

    //강의 분야 카테고리
    protected $table = 'cate_lecture';

    /**
     * 강사회원의 강의 분야인 경우 표시한 후 반환
     * @param $usersTeacherId : 강사회원아이디
     * @return array
     * */
    public function getTeacherLecture($usersTeacherId) {
        $data = $this->select('cate_lecture.code','cate_lecture.title', 'cate_lecture.large_cate',
            DB::raw('IF(cate_lecture.code in(select cate_lecture_code from users_teacher_lecture X where X.users_teacher_id = '.$usersTeacherId.') , \'Y\', \'N\') as checkYN'));

        $data = $data->where(DB::raw('length(code)'), '=', 7);

        return $data->orderBy('cate_lecture.large_cate', 'ASC')->orderBy('cate_lecture.code')->get();
    }

    /**
     * 강사회원의 강의분야만 문자열로 반환하기
     * @param $userTeacherId : 강사 회원 아이디
     * @return array
    */
    public function getTeacherLectureStr($userTeacherId) {
        $data = $this->select(DB::raw('group_concat(title) as title'), DB::raw('group_concat(code) as code'))
            ->leftJoin('users_teacher_lecture', 'cate_lecture.code', '=', 'users_teacher_lecture.cate_lecture_code')
            ->where('users_teacher_lecture.users_teacher_id', '=', $userTeacherId);

        return $data->first();
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 강의분야
    //##
    //##################################################################################################################
    public function dataTableList($request) {
        //### 데이터 조회
        $data = $this->select(
            'cate_lecture.id',
            DB::raw("concat(large_cate,'분류') as large_cate"),
            'cate_lecture.code',
            'cate_lecture.created_at',
            DB::raw("IF(length(code)=7, (select X.title from cate_lecture X where substr(X.code, 1, 4) = substr(cate_lecture.code, 1, 4)  and length(X.code)=4), cate_lecture.title ) as mid_cat"),
            DB::raw("IF(length(code)=7, cate_lecture.title, '' ) as s_cat")
        );

        //### 검색조건
        //#전체 검색 정보
        $searchAll = $request->input('search')['value'];
        if($searchAll != null) {
            $data->where(function ($query) use ($searchAll) {
                $query->where('cate_lecture.title', 'like', "%".$searchAll."%");
            });
        }
        //# 중분류 검색
        if(($searchMidCat = $request->input('columns')[1]['search']['value']) != null) {
            $data->where(function ($query) use ($searchMidCat) {
                //중분류, 중분류에 속해져 있는 소분류까지 출력하기
                $query->whereIn(DB::raw("substr(cate_lecture.code, 1,4)"),
                    convKeyArray($this->select('code')->where('title','like', '%'.$searchMidCat.'%')->where(DB::raw('length(code)'),4)->get(), 'code' ) );
            });
        }

        //# 소분류 검색
        if(($searchSCat = $request->input('columns')[2]['search']['value']) != null) {
            $data->where(function ($query) use ($searchSCat) {
                $query->where('cate_lecture.title', 'like', "%".$searchSCat."%");
                $query->where(DB::raw("length(cate_lecture.code)"), 7);
            });
        }

        //#분류 검색 정보
        if(($searchCat = $request->input('columns')[3]['search']['value']) && $searchCat!="") {
            if($searchCat == "2") { //중분류
                $data->where(DB::raw("length(cate_lecture.code)"), '=', '4');
            }else if($searchCat == "3") { //소분류
                $data->where(DB::raw("length(cate_lecture.code)"), '=', '7');
            }
        }

        //### 강의관리 모든 리스트 및 현황
        $totalData = $this->get();
        $score['totalCnt'] = $totalData->count();
        $score['largeCnt'] = ($this->groupBy('cate_lecture.large_cate')->get())->count();
        $score['midCnt'] = $this->where(DB::raw("length(cate_lecture.code)"), '4')->count();
        $score['smallCnt'] = $this->where(DB::raw("length(cate_lecture.code)"), '7')->count();
        $json['score'] = $score;

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $json['data'] = $data->offset($limitStart)->limit($limitlength)->orderBy('id','DESC')->get();

        return response()->json($json);
    }

    /**
     * json형태 반환
     * 대분류 목록
     * @return json
    **/
    public function getLargeCateList() {
        $info = $this->select('large_cate', DB::raw("concat(large_cate, '분류') as large_title"))->groupBy('large_cate')->get();

        return response()->json($info);
    }

    /**
     * json형태 반환
     * 중분류 목록
     * @param $largeCate : 선택한 대분류 목록
     * @return json
     */
    public function getMidCateList($largeCate) {

        $info = $this->select('code', 'title');
        $info = $info->where(DB::raw("length(code)"), '4')
            ->where('large_cate', $largeCate);
        $info = $info->get();

        return response()->json($info);
    }

    /**
     * 수정시 id에 해당하는 분류 내용 가져오기
     * @param $id : cate_lecture 고유 아이디
     * @return json data
    */
    public function selectLectureOne($id) {
        $data = $this->select(
            'cate_lecture.id',
            'cate_lecture.large_cate',
            'cate_lecture.code',
            'cate_lecture.created_at',
            DB::raw("IF(length(code)=7, (select X.title from cate_lecture X where substr(X.code, 1, 4) = substr(cate_lecture.code, 1, 4)  and length(X.code)=4), cate_lecture.title ) as mid_cat"),
            DB::raw("IF(length(code)=7, (select X.code from cate_lecture X where substr(X.code, 1, 4) = substr(cate_lecture.code, 1, 4)  and length(X.code)=4), '' ) as mid_code"),
            DB::raw("IF(length(code)=7, cate_lecture.title, '' ) as s_cat")
        );

        $data = $data->where('id', '=', $id)->first();

        return response()->json($data);
    }

    /**
     * 강의의 강의 분야인 경우 표시한 후 반환
     * @param $classRequestId : 강의 아이디
     * @return array
     * */
    public function getRequestLecture($classRequestId) {
        $data = $this->select('cate_lecture.code','cate_lecture.title', 'cate_lecture.large_cate',
            DB::raw('IF(cate_lecture.code in(select cate_lecture_code from class_request_lecture X where X.class_request_id = '.$classRequestId.') , \'Y\', \'N\') as checkYN'));

        $data = $data->where(DB::raw('length(code)'), '=', 7);

        return $data->orderBy('cate_lecture.large_cate', 'ASC')->orderBy('cate_lecture.code')->get();
    }

    /**
     * 강의 분야 중분류
    **/
    public function getMidLecture() {
        $data = $this->select('large_cate', 'code', 'title',
                DB::raw(
                    '(case
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 1 order by X.id asc limit 1)) then \'first\'
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 2 order by X.id asc limit 1)) then \'first\'
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 3 order by X.id asc limit 1)) then \'first\'
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 4 order by X.id asc limit 1)) then \'first\'
                            else \'\'
                            end
                        ) as first'
                ),
                DB::raw(
                    '(case
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 1 order by X.id DESC limit 1)) then \'last\'
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 2 order by X.id DESC limit 1)) then \'last\'
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 3 order by X.id DESC limit 1)) then \'last\'
                            when (code = (select X.code from cate_lecture X where LENGTH(X.code) = \'4\' and X.large_cate = 4 order by X.id DESC limit 1)) then \'last\'
                            else \'\'
                            end
                        ) as last'
                )
            )->where(DB::raw('length(code)'), '=', 4);

        return $data->orderBy('large_cate')->orderBy('code')->get();

    }

    /**
     * 강의의 강의분야만 문자열로 반환하기
     * @param $classRequestId : 강의 고유 아이디
     * @return array
     */
    public function getRequestLectureStr($classRequestId) {
        $data = $this->select(DB::raw('group_concat(title) as title'), DB::raw('group_concat(code) as code'))
            ->leftJoin('class_request_lecture', 'cate_lecture.code', '=', 'class_request_lecture.cate_lecture_code')
            ->where('class_request_lecture.class_request_id', '=', $classRequestId);

        return $data->first();
    }
}
