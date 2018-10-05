<?php
/**
 * Created by PhpStorm.
 * User: boram
 * Date: 2018-08-06
 * Time: 오후 1:57
 */


/**
 * 프리미엄 의뢰 상태
 * @param $code : 의뢰 상태 코드
**/
function requestStatus($code = "")
{
    $status = array(
		'register'=>'의뢰중',
		'p_request'=>'제안서요청',
	    'p_revirw'=>'제안서리뷰',
	    'p_send'=>'제안서발송',
	    'c_request'=>'강의요청',
	    'c_call'=>'출강요청',
	    'c_confirm'=>'강의확정'
    );

    if($code!="") {
        return $status[$code];
    } else {
        return $status;
    }

}

/**
 * 의뢰상태
 * @param $code[int] : 의뢰 상태 코드
*/
function recommendStatus($code = "") {
    $status = array(
		        1=>"의뢰요청",
			    2=>"제안서요청",
			    3=>"제안서리뷰",
			    4=>"제안서발송",
			    5=>"강의요청",
			    6=>"출강요청",
			    7=>"강의확정"
		    );

    if($code!="") {
        return $status[$code];
    } else {
        return $status;
    }
}

/**
 * 회원 상태
 * @param $code : 회원 상태
 */
function memberStatus($code = "") {
    $status = array('1' => '가입대기', '2' => '가입보류', '3' => '가입완료', '4' => '비활성화');

    if($code!="") {
        return $status[$code];
    } else {
        return $status;
    }
}

/**
 * 수강생 직급
 * @param $code
*/
function stdRank($code="") {
    $rank = array('R001' => '사원', 'R002' => '대리', 'R003' => '과장', 'R004' => '차부장',  'R005' => '임원');

    if($code!="") {
	    return isset($rank[$code])?$rank[$code]:'';
    } else {
        return $rank;
    }
}

/**
 * 수강생 인원
 * @param $code
*/
function stdNumber($code="") {
    $number = array( '1' => '10명이내', '2' => '10~20명', '3' => '20~30명', '4' => '40~50명', '5' => '50~100명', '6' => '100명이상');

    if($code!="") {
        return $number[$code];
    } else {
        return $number;
    }
}

/**
 * 수강생 연령대
 * @param $code
*/
function stdAge($code="") {
    $age = array('1' => '10대', '2' => '20대', '3' => '30대', '4' => '40대', '5' => '50대', '6' =>'60대 이상');

    if($code!="") {
        return $age[$code];
    } else {
        return $age;
    }
}

/**
 * 정산 상태
 * @param $code
*/
function calculateStatus($code="") {
    $status = array('wait' => '정산대기', 'success' => '정산완료');

    if($code!="") {
        return $status[$code];
    } else {
        return $status;
    }
}

/**
 * 파일 타입에 따른 이미지 src 파일 패스
 * @param $ifleType
 * @return path
 * */
function s3ImagePath($fileType="") {
    $path = array('company_business_license' => 'users/company/licenses/');

    if($fileType!="") {
        return env('AWS_URL'). S3_UPLOAD_PATH . $path[$fileType];
    }else {
        return $path;
    }


}

/**
 * 파일 타입에 따른 s3 저장 경로
 * @param $fileType
 * @return url
*/
function s3FilePath($fileType="") {
    $path = array("company_business_license" => "users/company/licenses/");

    return S3_UPLOAD_PATH.$path[$fileType];
}

/**
 * 강의 시간 목록 얻기
 * @param $time : 강의 시간(etc_time)
 * @return
 */
function getClassTime($time = "") {
    $times = array();

    for($i=2; $i<=24; $i++) {
        $var = $i * 30;

        $times[$var] = sprintf('%02d', (int)($var/60)) .'시간 '. sprintf("%02d", ($var%60)) .'분';
    }

    if($time!="") {
        return $times[$time];
    } else {
        return $times;
    }
}


//##################################################################################################################
//##
//## >> Web Menu
//##
//##################################################################################################################

/**
 * 사용자 타입
 * @param $userType
 * @return array
 */
function getUserType($type) {
	$arr = array('company' => '기업/교육업체', 'teacher'=>'강사', 'guest'=>'인트로');

	if($arr!="") {
		return isset($arr[$type])?$arr[$type]:'';
	} else {
		return $arr;
	}
}

/**
 * 메뉴 설정
 * @param $userType : 회원타입
 * @return array
 */
function getWebMenu($userType) {
	$arr = array();

	//# 게스트 메뉴
	if("guest" == $userType) {
		array_push($arr, ["title"=>"강사찾기", "url"=>"/teacher"]);
		array_push($arr, ["title"=>"강사사례", "url"=>"/portfolio"]);
		array_push($arr, ["title"=>"교육프로그램", "url"=>"/program"]);
		array_push($arr, ["title"=>"출강현황", "url"=>"/schedule"]);
	}
	//# 기업/교육업체 메뉴
	else if("company" == $userType) {
		array_push($arr, ["title"=>"강사찾기", "sub"=>array(
							["title"=>"강사찾기", "url"=>"/teacher"],
							["title"=>"강의현황", "url"=>"/company/class"])]);
		array_push($arr, ["title"=>"프리미엄의뢰", "url"=>"/company/request"]);
		array_push($arr, ["title"=>"강의사례", "url"=>"/portfolio"]);
		array_push($arr, ["title"=>"교육프로그램", "url"=>"/program"]);
		array_push($arr, ["title"=>"출강현황", "url"=>"/schedule"]);
		array_push($arr, ["title"=>"마이페이지", "sub"=>array(
							["title"=>"회원정보", "url"=>"/company/mypage/profile"],
							["title"=>"결제정보", "url"=>"/admin/main"],
							["title"=>"내가 쓴 강사평가 보기", "url"=>"/company/mypage/review"],
							["title"=>"1:1문의", "url"=>"/company/mypage/qna"])]);
	}
	//# 강사 메뉴
	else if("teacher" == $userType) {
		array_push($arr, ["title"=>"포트폴리오 관리", "url"=>"/teacher/portfolio"]);
		array_push($arr, ["title"=>"강의현황", "url"=>"/teacher/class"]);
		array_push($arr, ["title"=>"강사평사", "url"=>"/teacher/review"]);
		array_push($arr, ["title"=>"마이페이지", "sub"=>array(
							["title"=>"회원정보", "url"=>"/teacher/mypage/info"],
							["title"=>"강의프로필", "url"=>"/teacher/mypage/profile"],
							["title"=>"결제정보", "url"=>"/admin/main"],
							["title"=>"멘티목록", "url"=>"/teacher/mypage/mentee"],
							["title"=>"1:1문의", "url"=>"/teacher/mypage/qna"])]);
		array_push($arr, ["title"=>"스케줄러", "url"=>"/teacher/schedule"]);
	}

	return $arr;
}
