@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="/admin/dist/css/custom.css"/>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="col-md-12 m-b-30">
                            <div class="box-header">
                                <h3 class="box-title">기업명</h3>
                            </div>

                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="85%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th>상태</th>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="55%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>정산내용</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>강사 아이디</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>강사명</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>기업명</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>담당자 이름</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>담당자 아이디</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>담당자 전화번호</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>강의 장소</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>강의 시간</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>결제정보</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>결제정보</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th rowspan="10">강의료</th>
                                        <th colspan="2">결제일</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">정산내용</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">카드 수수료(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">수수료</th>
                                        <th>클래스이음(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>클래스이음(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">멘토 리워드</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">제안서</th>
                                        <th>클래스이음(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>멘토(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">원천징수</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">송금액</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>강의일</th>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th>기업평가</th>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-body">
                        <div class="col-md-12 m-b-30">
                            <div class="box-header">
                                <h3 class="box-title">세금계산서 발행여부</h3>
                            </div>

                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="35%"/>
                                    <col width="15%"/>
                                    <col width="35%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th>발행상태</th>
                                    <td colspan="3">
                                        <input type="hidden" name="id" value=""/>
                                        <select name="status" style="width:20%; float:left; margin-right:5px;" class="form-control">
                                            <option value=""></option>
                                            <option value=""></option>
                                        </select>
                                        <button onclick="save_status(); return false;" class="btn">저장</button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>발행요청일</th>
                                    <td></td>
                                    <th>금액</th>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div>
                    <ul class="pager wizard no-style">
                        <li class="pull-right" style="margin-left:5px;">
                            <button type="button" class="btn" id="list_btn" onclick="location.href='/admin/calculate/company'">목록</button>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

@endsection
@section("script")
    <script type="text/javascript" src="/js/common.js" ></script>
    <script src="/dist/Common.js"></script>
@stop