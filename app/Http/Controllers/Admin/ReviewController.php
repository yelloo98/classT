<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\EvaluateCompany;
use App\Http\Models\EvaluateUser;
use App\Http\Models\EvaluateUserQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    //강사 평가 후기 관리 

    /**
     * 수강생 평가 리스트
    **/
    function studentList() {
        return $this->createView('/admin/review/student/list', '수강생 평가', '목록');
    }

    /**
     * 수강생 평가 dataTable 목록 요청
    */
    function getEvaluateUserList(Request $request) {
        return (new EvaluateUser())->dataTableList($request);
    }

    /**
     * 수강생 평가 상세 보기
     * @param $id : 강의 고유 아이디
     */
    function studentDetail($id) {
        $view = $this->createView('/admin/review/student/detail', '기업 평가', '상세');

        $view->info = (new EvaluateUser())->selectEvaluateUserOne($id);
        $view->score = (new EvaluateUser())->getRequestScore($id);

        return $view;
    }

    /**
     * 기업 평가 목록
    **/
    function companyList(Request $request) {
        return $this->createView('/admin/review/company/list', '기업 평가', '목록');
    }

    /**
     * 기업평가 목록 dataTable 데이터 요청
    */
    function getEvaluateCompanyList(Request $request) {
        return (new EvaluateCompany())->dataTableList($request);
    }

    /**
     * 기업 평가 상세 보기
     * @param $id : 기업 후기 아이디
    */
    function companyDetail($id) {
        $view = $this->createView('/admin/review/company/detail', '기업 평가', '상세');

        $view->info = (new EvaluateCompany())->selectEvaluateCompanyOne($id);

        return $view;
    }

}
