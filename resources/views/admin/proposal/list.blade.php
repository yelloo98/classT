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
                        <form id="form" action="/admin/proposal">
                            <div class="row">
                                <div class="col-xs-1"><span>조회</span></div>
                                <div class="col-xs-6">
                                    <button class="btn" onclick="setCalcDate(7, 'D'); return false;">1주일</button>
                                    <button class="btn" onclick="setCalcDate(15, 'D'); return false;">15일</button>
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
                                            <input type="text" class="form-control pull-right" id="searchStartDt" data-date-format='yyyy-mm-dd' autocomplete="off" />
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="searchEndDt" data-date-format='yyyy-mm-dd' autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>상태</span></div>
                                <div class="col-xs-6">
                                    <label><input type="radio" class="minimal" name="searchStatus" value="ALL" checked/>전체</label>
                                    @foreach(array("Y" => '전달완료', "N" => "미완료") as $key => $value)
                                        <label><input type="radio" class="minimal" name="searchStatus" value="{{$key}}"/>{{$value}}</label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-2">
                                    @foreach(array('company_name' => '기업명', 'title' => '강의명', 'teacher_name' => '강사명') as $key => $value)
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
                            <div class="col-sm-8" style="margin-top:3%;">
                                <p style="margin-left:2%;">강사들이 클래스이음에 요청한 제안서를 전달하는 화면입니다.</p>
                            </div>
                            <div class="col-sm-4">
                                <table id="scoreTable" class="table table-bordered table-hover" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="33%"/>
                                        <col width="33%"/>
                                        <col width="33%"/>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>전체</th>
                                        <th>전달완료</th>
                                        <th>미완료</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
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
                                <col width="5%"/>
                                <col width="10%"/>
                                <col width="15%"/>
                                <col width="30%"/>
                                <col width="10%"/>
                                <col width="15%"/>
                                <col width="15%"/>
                            </colgroup>
                            <thead>
                                <th>No</th>
                                <th>기업명</th>
                                <th>강사명</th>
                                <th>강의명</th>
                                <th>제안서전달여부</th>
                                <th>요청일</th>
                                <th>전달일</th>
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

@section("script")
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script src="/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/proposal/page.proposal.datatable.js"></script>
    <script src="/dist/Common.js"></script>
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
@stop