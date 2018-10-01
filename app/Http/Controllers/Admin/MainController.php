<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\ClassRecommend;
use App\Http\Models\ClassRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    /**
     * 어드민 메인 페이지
    */
    function index(Request $request) {
        $view = $this->createView('/admin/main', '메인', '');
        //- 1:의뢰요청|2:제안서요청|3:제안서리뷰|4:제안서발송|5:강의요청|6:출강요청|7:강의확정

	    //# 강의의뢰건
	    $tbData['data_1_1'] = ClassRequest::count();
	    //# 제안서 미요청건
	    $tbData['data_1_2'] = ClassRecommend::where('status','=','1')->count();
	    //# 제안서 미수신건
	    $tbData['data_1_3'] = ClassRecommend::where('status','=','2')->count();
	    //# 제안서 미발송건
	    $tbData['data_1_4'] = ClassRecommend::where('status','=','3')->count();
	    //# 출강 미 요청건 (프리미엄 | 강의 의뢰)
	    $tbData['data_2_1'] = ClassRecommend::leftJoin('class_request', 'class_request.id', '=', 'class_recommend.class_request_id')->where('request_type', '=', 'premium')->where('class_recommend.status', '=', '5')->count();
	    //# 출강 미 확정건 (프리미엄 | 출강요청)
	    $tbData['data_2_2'] = ClassRecommend::leftJoin('class_request', 'class_request.id', '=', 'class_recommend.class_request_id')->where('request_type', '=', 'premium')->where('class_recommend.status', '=', '6')->count();
	    //# 정산대기 (강의확정 | 주문ID 없을시)
	    $tbData['data_2_3'] = ClassRequest::where('request_status','=','c_confirm')->where('order_id','=','')->count();
	    //# 세금계산서 미발행건 (세금계산서 ID 없을시)
	    $tbData['data_2_4'] = ClassRequest::where('tax_id','=','')->count();

	    $view->tbData = $tbData;

        return $view;
    }


}
