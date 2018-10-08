<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculateController extends Controller
{
    //정산

    /**
     * 강사회원 정산 관리 목록
    */
    function teacherList(Request $request) {
        $view = $this->createView('/admin/calculate/teacher/list', '강사 회원 정산관리', '목록');

        return $view;
    }

    /**
     * 강사회원 정산 관리 목록 dataTable
    */
    function getTeacherList(Request $request) {

    }

    /**
     * 강사회원 정산관리 상세보기
     * @param $id : class_request table id
    */
    function teacherDetail($id) {
        $view = $this->createView('/admin/calculate/teacher/detail', '강사 회원 정산관리', '상세보기');

        return $view;
    }

    /**
     * 기업회원 정산 관리 목록
     */
    function companyList(Request $request) {
        $view = $this->createView('/admin/calculate/company/list', '기업 회원 정산관리', '목록');

        return $view;
    }

    /**
     * 기업회원 정산 관리 목록 dataTable
     */
    function getCompanyList(Request $request) {

    }

    /**
     * 기업회원 정산관리 상세보기
     * @param $id : class_request table id
     */
    function companyDetail($id) {
        $view = $this->createView('/admin/calculate/company/detail', '강사 회원 정산관리', '상세보기');

        return $view;
    }

}
