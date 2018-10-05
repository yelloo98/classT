<?php
/**
 * Created by PhpStorm.
 * User: boram
 * Date: 2018-08-06
 * Time: 오후 2:22
 */

/**
 * 프리미엄 의뢰 요청 마감 상태 반환
 * @param $deadline : 요청 마감
 * @param $created_at : 요청 등록일
 * @return 마감일
 **/
function getRequestDeadline($deadline, $created_at) {
    $addDay = date('Y-m-d', strtotime($created_at . " +".$deadline." days")); //등록일에서 요청마감일수 를 더한다

    $today = date("Y-m-d", strtotime("Now")); //오늘 날짜를 구한다

    $diff = date_diff(new DateTime($addDay), new DateTime($today)); //그 날짜 차이 구하기
    //오늘 날짜가 마감되었는지 확인

    if((strtotime($addDay) - strtotime($today)) > 0) {
        return 'D-'.$diff->days;
    } else {
        return '마감';
    }
}

/**
 * datetime 형식 데이터에서 분을 더해서 반환
 * startDate에서 시간을 더해 endDate을 구할 때 사용
 * @param $date : 날짜
 * @param $startTime : 시작시간
 * @param $addMinute : 더할 값(분)
 * @param $addDate : 더할 날짜
 * @return datetime
 */
function getAddDateTime($date, $startTime = "13:00:00", $addMinute = "0", $addDate = "0") {
    return date('Y-m-d H:i:s', strtotime($date. " ".$startTime . " +".$addMinute." minutes +".$addDate." days"));
}

/**
 * date형식 데이터에서 일수를 더해서 반환
 * @param $date : 날짜
 * @param $addDate : 더할 날짜
 * @return date
 */
function getAddDate($date, $addDate = "0") {
    return date('Y-m-d', strtotime($date ." +".$addDate." days"));
}
/**
 * time형식 데이터에서 분을 더해서 반환
 * @param $time : 시간
 * @param $addDate : 더할 날짜
 * @return time
 */
function getAddTime($time, $addMinute = "0") {
    return date('H:i:'.($addMinute == 0 ? 's' : '59'), strtotime($time ." +".$addMinute." minutes"));
}

/**
 * 날짜 연산한 날짜값 반환
 * @param $date : 날짜
 * @param $format : return 형식
 * @param $operator : + or -
 * @param $number : 더하거나 뺄 수
 * @param $type : years or months or days
 * @return date
 */
function getAddDateF($date, $format = "Y-m-d", $operator = "+", $addDate = "0", $type='days') {
    return date($format, strtotime($date ." ".$operator.$addDate." days"));
}

/**
 * 두 날짜의 일수 차이 구하기
 * @param $startDate : 시작일
 * @param $endDate : 종료일
 * @return date_diff
 */
function diffDate($startDate, $endDate) {
    if($startDate == $endDate) {
        return 0;
    }else {
        return date_diff(new DateTime($startDate), new DateTime(($endDate)))->days;
    }
}

/**
 * [관리자] Auth 정보 체크
 * -----------------------------------------------------------------------------------------------------------------
 */
function getAdminUserInfo($key = "") {
    $data = \Illuminate\Support\Facades\Auth::guard('admins')->user();

    //# 관리자 정보가 있을 경우
    if($data != null) {
        if($key != "") return $data[$key];
        else return $data;
    } else {
        return null;
    }
}

/**
 * 강사회원 정보 반환
 * @param $key
 * @return array or value
 **/
function getAuthTeacher($key = "") {
    $data = \Illuminate\Support\Facades\Auth::guard('teacher')->user();

    //# 관리자 정보가 있을 경우
    if($data != null) {
        if($key != "") return $data[$key];
        else return $data;
    } else {
        return null;
    }
}

/**
 * 기업회원 정보 반환
 * @param $key
 * @return array or value
 **/
function getAuthCompany($key = "") {
    $data = \Illuminate\Support\Facades\Auth::guard('company')->user();

    //# 관리자 정보가 있을 경우
    if($data != null) {
        if($key != "") return $data[$key];
        else return $data;
    } else {
        return null;
    }
}

/**
 * 비밀번호 초기화 메일 전송
 * @param $userType : 사용자 로그인 타입 company | teacher
 * @param $toEmail : 전송 이메일
 * @param $autoNumber : 인증번호
 */
function sendPasswordResetMail($userType, $toEmail, $authNumber) {

    $subjectStr = ($userType == "company") ? "기업/교육업체" : "강사";

    $data = [
        "email" => $toEmail,
        "resetType"=>$userType,
        "autoNumber"=>$authNumber
    ];


    //### Send Email
    Mail::send('web.common.resetProc', $data, function ($mail) use($toEmail, $subjectStr) {
        $mail->from(env('MAIL_USERNAME'));
        $mail->to($toEmail);
        $mail->subject(sprintf('[%s] 클래스이음 비밀번호 초기화', $subjectStr));
    });
}

/**
 * image tag에서 사용할 src 주소 반환
 * @param $fileName
 * @param $fileType
 * @return path
 */
function getFullS3Path($fileName, $fileType) {
    return env('AWS_URL').env('AWS_'.$fileType.'_PATH').$fileName;
}



//##################################################################################################################
//##
//## 사용자
//##
//##################################################################################################################
/**
 * [사용자] Auth 정보 체크
 * @return userType
 */
function isUserType() {
    $vCompanyData = \Illuminate\Support\Facades\Auth::guard('company')->user();
    $vTeacherData = \Illuminate\Support\Facades\Auth::guard('teacher')->user();

    if($vCompanyData) {
        return "company";
    } else if($vTeacherData) {
        return "teacher";
    } else {
        return "guest";
    }
}

/**
 * 데이터에서 특정 키의 값으로만 배열생성
 * @param $data : 추출한 데이터 배열
 * @param $key : 특정 키값
 * @return array
 */
function convKeyArray($data, $key) {
    $dataArr = array();

    foreach ($data as $item) {
        if(isset($item->{$key}) && $item->{$key} != "") {
            array_push($dataArr, $item->{$key});
        }
    }

    return $dataArr;
}

/**
 * 배열 값 비교
 * @param : 배열값
 * @return array
 */
function compareAryValue($aryValue) {
    if(isset($aryValue)) {
        if(is_array($aryValue)) {
            return $aryValue;
        } else {
            return explode(',', $aryValue);
        }
    }else {
        return array();
    }
}