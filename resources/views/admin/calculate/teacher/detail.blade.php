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
                                <h3 class="box-title">00 강사</h3>
                            </div>
                            <form method="post" id="form" action="/admin/calculate/teacher/save">
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="15%"/>
                                        <col width="85%"/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>상태</th>
                                            <td>
                                                <select name="status" style="width:20%; float:left; margin-right:5px;">
                                                    <option value="">정산대기</option>
                                                    <option value="">정산완료</option>
                                                </select>
                                                <button onclick="save_status(); return false;" class="btn">저장</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="85%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>정산내용</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>강사 아이디</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>강사명</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>기업</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>강의장소</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>결제정보</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>강의료</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>강의일</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>기업평가</th>
                                        <td></td>
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
                                <h3 class="box-title">00 강사 계좌정보</h3>
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
                                    <th>은행</th>
                                    <td></td>
                                    <th>계좌번호</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>정산금액</th>
                                    <td></td>
                                    <th>처리일</th>
                                    <td></td>
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
                                <h3 class="box-title">멘토 계좌정보</h3>
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
                                        <th>이름</th>
                                        <td></td>
                                        <th>아이디</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>은행</th>
                                        <td></td>
                                        <th>계좌번호</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>정산금액</th>
                                        <td></td>
                                        <th>처리일</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table_fee" style="float:right; width:100%; margin : 15px;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">강의료</th>
                                        <th rowspan="2">카드결제 수수료(3%)</th>
                                        <th colspan="3">수수료</th>
                                        <th colspan="2">제안서</th>
                                        <th rowspan="2">정산금액</th>
                                    </tr>
                                    <tr>
                                        <th>클래스이음(%)</th>
                                        <th>클래스이음(%)</th>
                                        <th>멘토(%)</th>
                                        <th>클래스이음(%)</th>
                                        <th>멘토(%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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
                            <button type="button" class="btn" id="list_btn" onclick="location.href='/admin/calculate/teacher'">목록</button>
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