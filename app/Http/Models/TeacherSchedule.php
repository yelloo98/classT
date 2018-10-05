<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherSchedule extends Model
{
	protected $table = "teacher_schedule";

	//##################################################################################################################
	//##
	//## >> Schedule table
	//##
    //@param $usersTeacherId : 강사아이디
	//##################################################################################################################
	public function getScheduleTable($usersTeacherId = 0) {

	    //# 강의 의뢰  인원수 표시 쿼리 작성
        $caseNumber = "case";
	    foreach (stdNumber() as $key => $value) {
            $caseNumber .=' when etc_number='.$key.' then \''.$value.'\'';
        }
        $caseNumber .= ' end';

		//# 강사 상세 스케쥴 + 개인스케줄
		if($usersTeacherId > 0) {
			$data = ClassRequest::select(
									"id",
									DB::raw("0 AS schedule_id"),
									"users_teacher_id",
									"class_title AS title",
                                    'class_place AS place',
                                    DB::raw('(SELECT X.title from cate_rank X where X.code = class_request.etc_rank) AS rank'),
                                    DB::raw('(SELECT X.company_name from users_company X where X.id = class_request.users_company_id) AS company_name'),
									DB::raw("(".$caseNumber.') as number'),
									'etc_memo as memo',
									DB::raw("left(class_start_time, 5) as s_time"),
									'class_time as time',
									"class_start_dt AS start",
									"class_end_dt AS end",
                                    "class_start_dt AS schedule_start",
                                    "class_end_dt AS schedule_end",
									DB::raw("'#808080' AS backgroundColor"),
									DB::raw("'#808080' AS borderColor"),
                                    DB::raw("'class' AS schedule_type")
								)->where('users_teacher_id', '!=', null);

			if($usersTeacherId > 0) { //특정 강사의 스케줄만 조회하기
				$data = $data->where('users_teacher_id', '=', $usersTeacherId);
			}

			$vScheduleArr = $this->select(
									DB::raw("0 AS id"),
									'id AS schedule_id',
									'users_teacher_id',
									'title',
									'place',
									'rank',
                                    'company_name',
									'number',
									'memo',
                                    DB::raw("left(start_time, 5) as s_time"),
                                    'time',
                                    'start',
                                    'end',
                                    'start as schedule_start',
                                    'end as schedule_end',
									'backgroundColor',
									'borderColor',
                                    'schedule_type'
								);

			$vScheduleArr = $vScheduleArr->union($data);

			if($usersTeacherId > 0) { //특정 강사의 스케줄만 조회하기
				$vScheduleArr = $vScheduleArr->where('users_teacher_id', '=', $usersTeacherId);
			}
			$vScheduleArr = $vScheduleArr->get();
		}
		//# 특정일자 전체 스케쥴
		else {
			$calDate = date("Y-m");
			$vScheduleArr = ClassRequest::select(
									"class_request.id",
									"class_request.users_teacher_id",
									DB::raw("CONCAT('[',users_company.name,'] ', class_request.class_title) AS title"),
									"class_request.class_start_dt AS start",
									"class_request.class_end_dt AS end",
									DB::raw("'#0073b7' AS backgroundColor"),
									DB::raw("'#0073b7' AS borderColor")
								)->leftJoin('users_company', 'class_request.users_company_id', 'users_company.id')
			                    ->where('class_request.users_teacher_id', '!=', null)
			                    ->where(function($query) use ($calDate){
				                    $query->where('class_request.class_start_dt', '>', $calDate);
				                    $query->orWhere('class_request.class_end_dt', '>', $calDate);
			                    })->get();
		}

		return $vScheduleArr;
	}

    //##################################################################################################################
    //##
    //## >> Schedule 달에 따른 값 얻기
    //##
    //@param $usersTeacherId : 강사아이디
    //@param $ym : 년-월
    //##################################################################################################################
    public function getScheduleData($usersTeacherId = 0, $ym = "") {
	    $ym = ($ym == "") ? date('Y-m') : $ym;

        //# 강사 상세 스케쥴 + 개인스케줄
        $data = ClassRequest::select(
            'class_title as title', 'class_start_dt as start',
            DB::raw('if(class_start_dt != class_end_dt, concat(substr(class_start_dt,6,5), \' ~ \', if(left(class_end_dt, 7) = left(class_start_dt,7), substr(class_end_dt, 9,2), substr(class_end_dt,6,5))), substr(class_start_dt,6,5)) as dt')
            )->where('users_teacher_id', '!=', null)
             ->where(function($query) use ($ym) {
                $query->where(DB::raw('left(class_start_dt, 7)'), '=', $ym)->orWhere(DB::raw('left(class_end_dt, 7)'), '=', $ym);
            });

        //TODO 날짜 조건 추가

        if($usersTeacherId > 0) { //특정 강사의 스케줄만 조회하기
            $data = $data->where('users_teacher_id', '=', $usersTeacherId);
        }

        $vScheduleArr = $this->select(
            'title', 'start',
            DB::raw('if(start != end, concat(substr(start,6,5), \' ~ \', if(left(end, 7) = left(start,7), substr(end, 9,2), substr(end,6,5))), substr(start,6,5)) as dt')
        );

        $vScheduleArr = $vScheduleArr->union($data);

        if($usersTeacherId > 0) { //특정 강사의 스케줄만 조회하기
            $vScheduleArr = $vScheduleArr->where('users_teacher_id', '=', $usersTeacherId);
        }

        $vScheduleArr = $vScheduleArr->where(function($query) use ($ym) {
            $query->where(DB::raw('left(start, 7)'), '=', $ym)->orWhere(DB::raw('left(end, 7)'), '=', $ym);
        });
        $vScheduleArr = $vScheduleArr->orderBy('start', 'ASC');

        $vScheduleArr = $vScheduleArr->get();

        return $vScheduleArr;
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //## 강사 스케줄
    //##################################################################################################################
    public function convSaveData($info, $request) {
	    $prefixType = $request->input('prefix_type', 'c');
        $convFieldArr = array("company_name", "title", "place", "rank", "number", "memo", "start", "end", 'time');

        $info->start_time = ($request->input($prefixType.'_start_time', '12:00')) . ':00';
        $info->backgroundColor = ($prefixType == 'p' ? '#f4645f' : '#808080' );
        $info->borderColor = ($prefixType == 'p' ? '#f4645f' : '#808080' );

        //TODO 개인일정, 강의일정에 따라서 색깔 다르게 저장하기

        foreach ($convFieldArr as $key) {
            $inputK = $prefixType.'_'.$key;
            if($request->input($inputK, null) != null) {
                $info->{$key} = $request->input($inputK);
            }
        }

        $info->schedule_type = $request->input('schedule_type', null);

        return $info;
    }

    /**
     * 해당 스케줄 상세 내역 가져오기
     * @param $id : 스케줄 테이블 고유아이디
     * @return array
    **/
    public function seleceScheduleOne($id) {
        $data = $this->select(
            DB::raw("0 AS id"),
            'id AS schedule_id',
            'users_teacher_id',
            'title',
            'place',
            'rank',
            'number',
            'memo',
            'company_name',
            DB::raw("LEFT(start, 10) AS start"),
            DB::raw("LEFT(end, 10) AS end"),
            'backgroundColor',
            'borderColor',
            'schedule_type',
            'start_time',
            'time'
        )->where('id', '=', $id);

        return $data->first();
    }
}


