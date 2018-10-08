@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
    <link rel="stylesheet" type="text/css" href="/admin/dist/css/custom.css" >
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
                        <form id="form">
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>날짜</span></div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" name="searchStartDt" id="searchStartDt" data-date-format='yyyy-mm-dd' autocomplete="off"/>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" name="searchEndDt" id="searchEndDt" data-date-format='yyyy-mm-dd' autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_excel">EXCEL</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-6">
                                    @foreach(array("day" => "일별통계", "month" => "월별통계", "year" => "연별통게") as $key => $value)
                                        <label><input type="radio" class="minimal" name="searchType" value="{{$key}}" @if($key == 'day') checked @endif/>{{$value}}</label>
                                    @endforeach
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>
                        </form>

                        <div class="row" style="margin-top:2%;">
                            <div class="col-sm-12">
                                <table id="totalTable" class="table stat-table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>전체 매출</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row" style="margin-top:2%;">
                            <div class="col-md-12">
                                <div id="bar-chart" style="height: 450px; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:2%;">
                            <div class="col-md-12">
                                <table id="table_list" class="table stat-table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>일자</th>
                                            <th>매출액</th>
                                            <th>비고</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    <script type="text/javascript" src="/dist/Common.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.resize.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.pie.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.categories.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/stat/page.classeumStat.datatable.js"></script>
@endsection

@section("script")
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