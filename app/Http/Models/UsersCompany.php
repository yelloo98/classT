<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use EnjoyWorks\S3\FileFunc;
use EnjoyWorks\S3\FileS3;
use Illuminate\Support\Facades\Hash;

class UsersCompany extends Authenticatable
{
    //기업회원 테이블

    use SoftDeletes;

    protected $table = 'users_company';

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
        $columns = array(0 => 'id', 1 => 'users_company.email', 2 => 'users_company.name', 3 => 'users_company.phone', 4 => 'users_company.company_name', 5 => 'users_company.status', 6 => 'users_company.created_at');

        //### 데이터 조회
        $data = $this->select('users_company.id',
            'users_company.email',
            'users_company.name',
            'users_company.phone',
            'users_company.created_at',
            'users_company.company_name',
            'users_company.status');

        //### 검색조건
        //# 이메일, 이름, 전화번호, 회사명 검색
        foreach (array(1 => 'email', 2 => 'name', 3 => 'phone', 4 => 'company_name') as $key => $value) {
            if( ($searchValue = $request->input('columns')[$key]['search']['value']) != null) {
                $data->where("users_company.".$value, "like", "%".$searchValue."%");
            }
        }

        //# 회원 상태 검색
        $searchStatus = $request->input('columns')[5]['search']['value'];
        if($searchStatus != null) {
            $data->where("users_company.status", "=", $searchStatus);
        }

        $order = $request->input('order')[0]['column'];
        $order = ($order!="") ? $columns[$order] : 'id';
        $orderType = $request->input('order')[0]['dir'];
        $orderType = ($orderType!="") ? $orderType : 'DESC';

        //### 페이징 개수 조건
        $limitStart = $request->input('start');
        $limitlength = $request->input('length');

        //#회원상태 카운트
        $score['completeCnt'] = UsersCompany::where('status', '=', '3')->count(); //가입완료
        $score['waitCnt'] = UsersCompany::where('status', '=', '1')->count(); //가입대기
        $score['holdCnt'] = UsersCompany::where('status', '=', '2')->count(); //가입보류
        $score['disabledCnt'] = UsersCompany::where('status', '=', '4')->count(); //비활성화
        $json['score'] = $score;

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->offset($limitStart)->limit($limitlength)->orderBy($order,$orderType)->get();

        foreach ($data as $item) {
            if($item->status != "") {
                $item->status = memberStatus($item->status);
            }
        }
        $json['data'] = $data;

        return response()->json($json);
    }

    //##################################################################################################################
    //##
    //## >> Data processing
    //##
    //##################################################################################################################
    //# 데이터 등록 및 수정
    public function convSaveData($info, $request, $type = '') {
        $convFieldArr = array('name', 'phone', 'birth', 'send_email_agree', 'send_sms_agree', 'status', 'company_name', 'company_number');

        foreach ($convFieldArr as $key) {
            if( $request->input($key, null) != null ) { //값이 넘어온 경우에만 수정해주기
                $info->{$key} = $request->input($key);
            }
        }

        if($type == 'join') { //회원가입인 경우
            $info->password = Hash::make($request->input('password')); //암호화
            $info->email = $request->input('email');
            $info->status = 1; //회원상태 대기로 변경
        }

        return $info;
    }

    /**
     * 사업자 등록증 aws s3서버에 올리기
    **/
    public function uoloadFile($info, $request, $id) {
        $file = $request->file('business_license');

        $vFilePath = env('AWS_COMPANY_LICENSE_PATH').$id.'/';
        $vFileName = $file->getClientOriginalName();

        $resFileInfo = FileS3::upload($file , $vFilePath, $vFileName);

        if(!empty($resFileInfo)) {
            $info->business_license = $vFileName;
        }

        return $info;
    }

    //##################################################################################################################
    //##
    //## >> Data Table List -- 기업 통계
    //##
    //##################################################################################################################
    public function statDataTableList($request) {
        //### 데이터 조회
        $data = $this->select('users_company.id',
            'users_company.company_name',
            'users_company.company_number');

        //### 검색조건

        if( ($searchCompany = $request->input('columns')[1]['search']['value'])!="" ) { //기업명이 있는 경우
            $data->where("users_company.company_name", "like", "%".$searchCompany."%");
        }

        //# 날짜 검색
        if( ($searchStartDt = $request->input('columns')[2]['search']['value'])!="" && ($searchEndDt = $request->input('columns')[3]['search']['value'])!="" ) {
            //TODO 결제 날짜 조건 추가
        }

        //#회원상태 카운트
        $score['totalPay'] = 100000000; //전체 매출
        $json['score'] = $score;

        //### 페이징 데이터 정보
        $json['draw'] = $request->input('draw');
        $json['recordsTotal'] = $data->count(); //전체행개수
        $json['recordsFiltered'] = $data->count();
        $data = $data->limit(10)->orderBy('id','DESC')->get();

        //결제 순위
        /*select count(id) as order_count from
        class_request group by users_company_id
        order by order_count Desc;*/

        foreach ($data as $item) {
            $item->note = "";
            $item->order_cnt = 10;
            $item->order_pay = 1000000;
        }
        $json['data'] = $data;

        return response()->json($json);
    }
}
