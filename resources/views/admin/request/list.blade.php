@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <form id="form" action="/admin/request/teacher">
                            <div class="row">
                                <div class="col-xs-1"><span>조회</span></div>
                                <div class="col-xs-6">
                                    <button class="btn" onclick="setCalcDate(7, 'D'); return false;">1주일</button>
                                    <button class="btn" onclick="setCalcDate(15, 'D'); return false;">15일</button >
                                    <button class="btn" onclick="setCalcDate(1, 'M'); return false;">1개월</button>
                                    <button class="btn" onclick="setCalcDate(3, 'M'); return false;">3개월</button></div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>날짜</span></div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="searchStartDt" data-date-format='yyyy-mm-dd' autocomplete="off"/>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="searchEndDt" data-date-format='yyyy-mm-dd' autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>상태</span></div>
                                <div class="col-xs-6">
                                    <label><input type="radio" class="minimal" name="searchStatus" value="all" checked>전체</label>

                                    @foreach(recommendStatus() as $key => $value)
                                        <label><input type="radio" class="minimal" name="searchStatus" value="{{$key}}"/>{{$value}}</label>
                                    @endforeach
                                </div>
                            </div>
                            {{--<div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('' => '전체', 'title' => '제목') as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="searchWord" value="{{app('request')->input('searchWord')}}">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>--}}
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-2">
                                    @foreach(array('company_name' => '기업명', 'teacher_name' => '강사명', 'title' => '강의명') as $key => $value)
                                        <input type="checkbox" class="minimal search-type" name="searchType[]" value="{{$key}}" />
                                        <label for="searchType">{{$value}}</label>
                                    @endforeach
                                </div>
                                <div class="col-xs-4" style="vertical-align: middle!important;">
                                    <input type="text" class="form-control" name="searchWord" style="width:100%;">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-sm-6" style="margin-top:3%;">
                                <p style="margin-left:2%;">클래스이음 기업들의 프리미엄 의뢰 화면입니다.</p>
                            </div>
                            <div class="col-sm-6">
                                <table id="scoreTable" class="table table-bordered table-hover" style="float:right; width:100%; margin : 15px;">
                                    <thead>
                                    <tr>
                                        <th>전체</th>
                                        <th>의뢰중</th>
                                        <th>제안서요청</th>
                                        <th>제안서리뷰</th>
                                        <th>제안서발송</th>
                                        <th>강의요청</th>
                                        <th>출강요청</th>
                                        <th>강의확정</th>
                                        <th>결제완료</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h3 class="box-title totalCnt"></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="table_list" class="table table-bordered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="10%"/>
                                <col width="25%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="15%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>의뢰 제목</th>
                                    <th>의뢰처</th>
                                    <th>요청마감기한</th>
                                    <th>강사명</th>
                                    <th>강사아이디</th>
                                    <th>상태</th>
                                    <th>강의의뢰일</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section("plugins")
    <script type="text/javascript" src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/request/page.request.datatable.js"></script>
    <script type="text/javascript" src="/admin/page/request/page.request.func.js"></script>
    <script type="text/javascript" src="/dist/Common.js"></script>
@stop

@section('script')
    <script>
        $(document).ready(function() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

            setCalcDate(0,'d');
        });
    </script>
@endsection