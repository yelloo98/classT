<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\ClassRequest;
use App\Http\Models\UsersCompany;
use App\Http\Models\UsersTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{
    //통계

    /**
     * 강사별 통계
     */
    function teacherStats() {
        return $this->createView('/admin/stats/teacherStat', '통계', '강사');
    }

    /**
     * 강사 통계 데이터
    */
    function teacherStatList(Request $request) {
        return (new UsersTeacher())->statDataTableList($request);
    }

    /**
     * 기업별 통계
     */
    function companyStats() {
        return $this->createView('/admin/stats/companyStat', '통계', '기업');
    }

    /**
     * 기업 통계 데이터
    */
    function companyStatList(Request $request) {
        return (new UsersCompany)->statDataTableList($request);
    }

    /**
     * 클래스이음 통계
    */
    function classeumStats() {
        return $this->createView('/admin/stats/classeumStat', '통계', '클래스이음');
    }

    /**
     * 클래스이음 통계 데이터
    */
    function classeumStatList(Request $request) {
        return (new ClassRequest())->statDataTableList($request);
    }
}
