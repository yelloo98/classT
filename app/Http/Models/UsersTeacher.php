<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;
use Illuminate\Support\Facades\Hash;

class UsersTeacher extends Authenticatable
{
    //강사회원 테이블

    use SoftDeletes;

    protected $table = 'users_teacher';
	protected $fillable = [
		'email', 'password',
	];
	protected $hidden = ['password'];

    //##################################################################################################################
    //##
    //## >> Data Table List -- 기업회원 목록
    //##
    //##################################################################################################################
    public function dataTableList($request) {
        $columns = array(0 => 'id', 1 => 'users_teacher.grade', 2 => 'users_teacher.email', 3 => 'users_teacher.name', 4 => 'users_teacher.phone', 5 => 'recommend_teacher_yn', 6 => 'users_teacher.status', 7 => 'users_teacher.created_at');

        //### 데이터 조회
        $data = $this->select('users_teacher.id',
            'users_teacher.grade',
            'users_teacher.email',
            'users_teacher.name',
            'users_teacher.phone',
            'users_teacher.status',
            'users_teacher.created_at',
            DB::raw('IF((select count(X.id) from users_teacher X where X.recommend_user_id = id)>0, "Y", "N") AS recommend_teacher_yn')
            );

        //### 검색조건
        //# 이메일, 이름, 회사명 검색
        foreach (array(1 => 'email', 2 => 'name', 3 => 'phone') as $key => $value) {
            if( ($searchValue = $request->input('columns')[$key]['search']['value']) != null) {
                $data->where("users_teacher.".$value, "like", "%".$searchValue."%");
            }
        }

        //# 회원 상태 검색
        $searchStatus = $request->input('columns')[4]['search']['value'];
        if($searchStatus != null) {
            $data->where("users_teacher.status", "=", $searchStatus);
        }

        //정렬타입
        $order = $request->input('order')[0]['column'];
        $order = ($order!="") ? $columns[$order] : 'id';
        $orderType = $request->input('order')[0]['dir'];
        $orderType = ($orderType!="") ? $orderType : 'DESC';

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //#회원상태 카운트
        $score['completeCnt'] = UsersTeacher::where('status', '=', '3')->count(); //가입완료
        $score['waitCnt'] = UsersTeacher::where('status', '=', '1')->count(); //가입대기
        $score['holdCnt'] = UsersTeacher::where('status', '=', '2')->count(); //가입보류
        $score['disabledCnt'] = UsersTeacher::where('status', '=', '4')->count(); //비활성화
        $json['score'] = $score;

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy($order, $orderType)->get();

        foreach ($data as $item) {
            if($item->status != "") {
                $item->status = memberStatus($item->status);
            }
        }
        $json['data'] = $data;

        return response()->json($json);
    }

    /**
     * 강사 회원 상세 페이지
     * @param $id : 강사 고유 아이디
     * @return array
    */
    public function selectTeacherOne($id) {
        //### 데이터 조회
        $data = $this->select('users_teacher.*',
            DB::raw('IF((select count(X.id) from users_teacher X where X.recommend_user_id = users_teacher.id)>0, "Y", "N") AS recommend_teacher_yn'),
            DB::raw('(select count(X.id) from users_teacher X where X.recommend_user_id = users_teacher.id) AS mentee_cnt'),
            DB::raw('(IF(users_teacher.recommend_user_id is null, \'\', (SELECT name from users_teacher Y where Y.id = users_teacher.recommend_user_id) )) as mentor_name')
        );

        $data = $data->where('id', '=', $id);

        return $data->first();
    }


    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 강사 회원 데이터 수정
    public function convSaveData($info, $request, $type = '') {
        $convFieldArr = array("name", "grade", "gender", "birth", "phone", "status", "recommend_user_id", "send_email_agree", "send_sms_agree", "account_bank", "account_number", "account_host", "time_pay",
            "count_pay", "major_career", "greeting", "class_feature", "class_topic", "major_career", 'mentee_max_num' );

        foreach ($convFieldArr as $key) {
            if($request->input($key, null) != null) {
                $info->{$key} = $request->input($key);
            }
        }

        if(isset($info->recommend_user_id) && $request->input('recommend_user_id', null) != null && $info->recommend_user_id != $request->input('recommend_user_id')) { //멘토 등록 or 수정된 경우 멘토수정일 변경
            $info->recommend_user_created_dt = date('Y-m-d H:i:s');
        }

        $info->place_bargain = $request->input('place_bargain', 'N');

        if($type == 'join') { //회원가입인 경우
            $info->password = Hash::make($request->input('password')); //암호화시키기
            $info->email = $request->input('email');
            $info->status = 1; //회원상태 대기로 변경
        }

        return $info;
    }

    /**
     * 최종학위증명서, 통장사본이미지, 석사졸업증명서, 경력증명서, 재직증명서, 개인사업등록증 올리기
     * @param $seqId : 강사회원아이디
    */
    public function uoloadFile($request, $seqId) {

        foreach (array('last_degree'=>'lastDegree', 'bank_copy'=>'bank', 'master_degree'=>'master', 'experience' => 'experience', 'work_certificate'=>'work', 'business_license'=>'license', 'profile' => 'profile') as $key => $type) {
            if($request->hasFile($key)) { //파일이 있으면 저장하기
                $file = $request->file($key);

                $vFilePath = env('AWS_TEACHER').$seqId.'/attach/';
                $vFileName = $key.'.'.$file->getClientOriginalExtension();

                $resFileInfo = FileS3::upload($file , $vFilePath, $vFileName);

                if(!empty($resFileInfo)) {
                    $fileInfo = (new UsersTeacherAttach())->select('id')->where('file', '=', $vFileName)->first(); //첨부파일조회

                    if($fileInfo == null) { //예전에 저장된적이 없는 경우에만 첨부파일 정보 테이블에 insert
                        $res = (new UsersTeacherAttach())->insertTeacherAttach($seqId, $vFileName, 'img', $type);
                    }
                }
            }
        }

        return $res;
    }

    /**
     * 파일 한개 저장
     * @param $seqId : 강사 회원 아이디
    **/
    public function uploadFileOne($request, $seqId) {
        $key = $request->input('file_name');

        if($request->hasFile($key)) { //파일이 있으면 저장하기
            $file = $request->file($key);

            $vFilePath = env('AWS_TEACHER_PATH').$seqId.'/attach/';
            $vFileName = $key.'.'.$file->getClientOriginalExtension();

            $resFileInfo = FileS3::upload($file , $vFilePath, $vFileName);

            if(!empty($resFileInfo)) {
                $fileInfo = (new UsersTeacherAttach())->select('id')->where('file', '=', $vFileName)->first(); //첨부파일조회

                if($fileInfo == null) { //예전에 저장된적이 없는 경우에만 첨부파일 정보 테이블에 insert
                    $fileInfo = new UsersTeacherAttach();

                    $fileInfo->file_type = 'img';
                    $fileInfo->img_type = $request->input('file_type');
                    $fileInfo->file = $vFileName;
                    $fileInfo->users_teacher_id = $seqId;
                    $res = $fileInfo->save();
                }

                $fileId = $fileInfo->id;
            }

            $filePath = env('AWS_URL').$vFilePath.$vFileName;

            return array('file_path' => $filePath, 'file_id' => $fileId); //script로 뿌려줄수있는 이미지 url 반환할것
        }

        return array();
    }

    /**
     * 강사 강의 사진 업로드
     * @seqId : 강사회원 아이디
    **/
    public function uploadAttachClassFile($request, $seqId) {
        $attachFile = $request->input('attach_class');

        for($i=1; $i<=count($attachFile); $i++) {
            $file = $attachFile[$i];

            $vFilePath = env('AWS_TEACHER').$seqId.'/attach/';
            $vFileName = 'class_'.$i.'.'.$file->getClientOriginalExtension();

            $resFileInfo = FileS3::upload($file , $vFilePath, $vFileName);

            if(!empty($resFileInfo)) {
                $res = (new UsersTeacherAttach())->insertTeacherAttach($seqId, $vFileName, 'img', 'class');
            }
        }
    }

    /**
     * 프리미엄 관리자 상세페이지 강사찾기
     * @param $requestId : class_request 고유 아이디
     * @return json
    */
    public function requestSearchTeacher($requestId) {
        //# 분야, 직급, 시도

        $classData = ClassRequest::find($requestId);
        $lectureData = convKeyArray(ClassRequestLecture::select("cate_lecture_code")->where('class_request_id', '=', $requestId)->get(), 'cate_lecture_code');
        debug($lectureData);
        //TODO 시작일, 종료일, 시작시간, 진행시간
        $classDateTime = array('startDt' => $classData->class_start_dt, 'endDt' => $classData->class_end_dt, 'start_time' => $classData->class_start_time, 'time' => $classData->class_time );

        $json = $this->searchTeacher(array('rank_code' => array($classData->etc_rank), 'lecture_code' => $lectureData, 'area_code' => array($classData->etc_area), 'class_datetime' => $classDateTime ,'request_id' => $requestId));

        return response()->json($json);
    }

    /**
     * 강사 찾기 조건 - and
     * @param $params : where절 검색 값
     * @return [array]teacher list
     */
    public function searchTeacher($params = array()) {
        //TODO 다섯개 조건 조회중 users_teacher_#에 값이 없는 강사는 출력되지 않음

        //# 업종 조건(business)
        $businessData = UsersTeacherBusiness::select(DB::raw("DISTINCT users_teacher_id"));
        if(isset($params['business_code']) && $params['business_code']  != null) $businessData = $businessData->whereIn('cate_business_code',$params['business_code']);
        $businessDataArr = convKeyArray($businessData->get(), 'users_teacher_id');

        //# 직급 조건(rank)
        $rankData = UsersTeacherRank::select(DB::raw("DISTINCT users_teacher_id"));
        if(isset($params['rank_code']) && $params['rank_code'] != null) $rankData = $rankData->whereIn('cate_rank_code',$params['rank_code']);
        $rankDataArr = convKeyArray($rankData->get(), 'users_teacher_id');

        //# 분야(lecture)
        $lectureData = UsersTeacherLecture::select(DB::raw('DISTINCT users_teacher_id'));
        if(isset($params['lecture_code']) && $params['lecture_code']!= null) $lectureData = $lectureData->whereIn('cate_lecture_code', $params['lecture_code']);
        $lectureDataArr = convKeyArray($lectureData->get(), 'users_teacher_id');

        //# 장소(area)
        $areaData = UsersTeacherArea::select(DB::raw('DISTINCT users_teacher_id'));
        if(isset($params['area_code']) && $params['area_code']!=null) $areaData = $areaData->whereIn('cate_area_code', $params['area_code']);
        $areaDataArr = convKeyArray($areaData->get(), 'users_teacher_id');

        //# 단체(group)
        $groupData = UsersTeacherGroup::select(DB::raw('DISTINCT users_teacher_id'));
        if(isset($params['group_code']) && $params['group_code']!=null) $groupData = $groupData->whereIn('cate_group_code', $params['group_code']);
        $groupDataArr = convKeyArray($groupData->get(), 'users_teacher_id');

        //#금액
        $payData = UsersTeacher::select("id");
        //사용자가 입력한 금액보다 적거나 같은 금액을 입력한 강사 목록
        if(isset($params['time_pay']) && $params['time_pay']!=null) $payData = $payData->where("users_teacher.time_pay", '<=', $params['time_pay']);
        if(isset($params['count_pay']) && $params['count_pay']!=null) $payData = $payData->where("users_teacher.count_pay", '<=', $params['count_pay']);
        $payDataArr = convKeyArray($payData->get(), 'id');

        //# 위 검색조건중 공통된 강사 ID값 확인
        $res = array_intersect($businessDataArr, $rankDataArr, $lectureDataArr, $areaDataArr, $groupDataArr, $payDataArr); //교집합

        //# 강사 스케쥴 확인
        $res2 = array();
        $mergeSchduleDataArr = array();
        if(isset($params['class_datetime']) && ($classDateTime = $params['class_datetime'])!=null) {

            $dateArr = array(); //해당 강의의 start_dt ~ end_dt 사이에 포함되어져 있는 모든 일자
            for($i=0; $i<=diffDate($classDateTime['startDt'], $classDateTime['endDt']); $i++) {
                $dateArr[] = getAddDate($classDateTime['startDt'], $i);
            }

            $timeAry = array($classDateTime['start_time'], getAddTime($classDateTime['start_time'], $classDateTime['time']) ); //해당 프리미엄의뢰의 시작시간, 끝시간

            //# teacher_schedule 에서 강의할 수 없는 강사아이디 얻기
            $scheduleData = TeacherSchedule::select(DB::raw("DISTINCT users_teacher_id"));

            $scheduleData = $scheduleData->where(function($query) use ($dateArr, $timeAry) { //시작일, 종료일 중 하나라도 포함되는 일자의 강의가 있는경우
                $query->whereIn(DB::raw("start"), $dateArr)->whereBetween(DB::raw("start_time"), $timeAry);
            });
            $scheduleData = $scheduleData->orWhere(function($query) use ($dateArr, $timeAry) {
                $query->whereIn(DB::raw("end"), $dateArr)->whereBetween(DB::raw('DATE_ADD(start_time, INTERVAL time MINUTE)'), $timeAry);
            });

            //# class_requst 강의할 수 없는 강사아이디 얻기
            $cScheduleData = ClassRequest::select(DB::raw("DISTINCT users_teacher_id"));

            $cScheduleData = $cScheduleData->where(function($query) use ($dateArr, $timeAry) { //시작일, 종료일 중 하나라도 포함되는 일자의 강의가 있는경우
                $query->whereIn('class_start_dt', $dateArr)->whereBetween('class_start_time', $timeAry);
            });

            $cScheduleData = $cScheduleData->where(function($query) use ($dateArr, $timeAry) {
                $query->whereIn('class_end_dt', $dateArr)->whereBetween(DB::raw('DATE_ADD(class_start_time, INTERVAL class_time MINUTE)'), $timeAry);
            });

            $cScheduleData = $cScheduleData->whereNotNull('users_teacher_id');

            $scheduleDataArr = convKeyArray($scheduleData->get(), 'users_teacher_id');
            $cScheduleDataArr = convKeyArray($cScheduleData->get(), 'users_teacher_id');
            $mergeSchduleDataArr = array_unique(array_merge($scheduleDataArr, $cScheduleDataArr)); //합집합
        }
        $res2 = array_diff($res, $mergeSchduleDataArr);

        //#강사 상세정보 조회
        $vTeacherInfoArr = $this->select('name', 'id')->whereIn('id', $res2)->where('status','=','3'); //users_teacher table에서

        if(isset($params['request_id']) && $params['request_id']) { //# 이미 해당 강의에 추천된 강사는 제외할 것
            $vTeacherInfoArr = $vTeacherInfoArr->whereNotIn('id', (new ClassRecommend())->getClassRecommendedTeacher($params['request_id']));
        }

        return $vTeacherInfoArr->get();
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 강사 통계
    //##
    //##################################################################################################################
    public function statDataTableList($request) {
        //### 데이터 조회
        $data = $this->select(
            DB::raw("count(class_recommend.id) as order_cnt"),
            'users_teacher.id',
            'users_teacher.name',
            'users_teacher.email',
            'users_teacher.account_number')
        ->leftJoin('class_recommend', 'users_teacher.id', '=', 'class_recommend.users_teacher_id')
        ->leftJoin('class_request', 'class_request.id', '=', 'class_recommend.class_request_id');

        //TODO 완료된 강의만 얻어오는 조건 추가
        //$data->where('class_recommend.status', '=', 7);

        //# 강사명 검색
        if( ($searchName = $request->input('columns')[1]['search']['value'])!="" ) {
            $data->where("users_teacher.name", "like", "%".$searchName."%");
        }

        //# 날짜 검색
        if( ($searchStartDt = $request->input('columns')[2]['search']['value'])!="" && ($searchEndDt = $request->input('columns')[3]['search']['value'])!="" ) {
            //TODO 결제 날짜 조건 추가
        }

        //#회원상태 카운트
        $score['teacherTotal'] = $this->count(); //전체 강의건수
        $json['score'] = $score;

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->limit(10)->groupBy('users_teacher.id')->orderBy('order_cnt','DESC')->get();

        //TODO 정산금액 얻기
        $count=0;
        foreach ($data as $item) {
            $item->rank = ++$count;
            $item->note = "";
            $item->order_pay = 1000000;
        }
        $json['data'] = $data;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 월별 강사 스케줄(강사 포트폴리오 등록시 내스케줄 모달창에서 사용)
    //##
    //## @param $isEnd : 강의 종료 여부 false - 종료아님, true : 종료된 강의만 얻어오기
    //## @param $usersTeacherId : 강사회원아이디
    //##################################################################################################################
    public function getDataTableTeacherSchedule($request, $isEnd = false, $usersTeacherId = 0) {

        if( ($year = $request->input('columns')[1]['search']['value']) != "" && ($month = $request->input('columns')[2]['search']['value']) != "") {
            $calDate = $year.'-'.sprintf('%02d', $month);
        } else {
            $calDate = date("Y-m");
        }

        $data = $this->select(
                "class_request.id as class_request_id", "class_request.users_teacher_id", 'users_company.company_name', 'class_request.class_title',
                'class_request.etc_number as class_number', 'class_request.class_time', 'class_request.class_place',
                DB::raw('concat(class_request.class_start_dt, " ~ ", class_request.class_end_dt) as class_dt'),
                DB::raw("IFNULL( (select X.title from cate_rank X where class_request.etc_rank = X.code), '') as class_rank")

            )->leftJoin('class_request', 'class_request.users_teacher_id', '=', 'users_teacher.id')
            ->leftJoin('users_company', 'class_request.users_company_id', '=', 'users_company.id')
            ->where(function($query) use ($calDate){
                $query->where(DB::raw('substr(class_request.class_start_dt, 1, 7)'), '=', $calDate);
                $query->orWhere(DB::raw('substr(class_request.class_end_dt, 1, 7)'), '=', $calDate);
            });

        if($isEnd == true) {
            $data->whereNotNull('class_request.users_teacher_id');
        }

        if($usersTeacherId>0) {
            $data->where('class_request.users_teacher_id', '=', $usersTeacherId);
        }

        $a_month = date("m", strtotime($calDate." +1 months"));
        $a_year = date("Y", strtotime($calDate." +1 months"));
        $b_month = date("m", strtotime($calDate." -1 months"));
        $b_year = date("Y", strtotime($calDate." -1 months"));

        $date = array('b_month' => $b_month, 'b_year' => $b_year, 'a_month' => $a_month, 'a_year' => $a_year, 'calDate' => $calDate);
        $json['date'] = $date;

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->limit(10)->orderBy('class_request.id','DESC')->get();

        foreach ($data as $item) {
            $item->class_number = ($item->class_number != null) ? stdNumber($item->class_number) : '';
        }

        $json['data'] = $data;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 멘티회원 목록
    //##
    //@param $usersTeacherId : 강사회원아이디
    //##################################################################################################################
    public function menteeDataTableList($request, $usersTeacherId) {
        $data = $this->select('id', 'name', 'recommend_user_created_dt',
                            DB::raw('(select count(request_proposal.id) as cnt 
                        from request_proposal left join class_recommend on(request_proposal.class_recommend_id = class_recommend.id) 
                        where request_proposal.mentor_id = '.$usersTeacherId.' 
                        and class_recommend.users_teacher_id = users_teacher.id 
                        and request_proposal.pdf_name is null 
                        and request_proposal.refuse_yn=\'N\')
                        as p_req_cnt'))
                            ->where('recommend_user_id', '=', $usersTeacherId);

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $json['mentee_max_num'] = UsersTeacher::select('mentee_max_num')->where('id', $usersTeacherId)->first()->mentee_max_num; //강사 멘티 최대 인원
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy('users_teacher.id', 'DESC')->get();

        foreach ($data as $item) {
            $item->lecture = (new CateLecture())->getTeacherLectureStr($item->id)->title;
        }

        $json['data'] = $data;

        return response()->json($json);
    }
}
