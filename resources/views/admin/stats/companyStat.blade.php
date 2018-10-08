@extends('admin.layouts.app')

@section('style')
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
                        <form id="form" action="/admin/stats/company">
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
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_excel">EXCEL</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('all' => '전체', 'name' => '기업명') as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="searchWord">
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
                                            <th>전체매출</th>
                                            <th>전체 기업수</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
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
                                <table id="table_list" class="table stat-table table-bordered table-hover" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>순위</th>
                                            <th>기업</th>
                                            <th>사업자등록번호</th>
                                            <th>결제건수</th>
                                            <th>결제금액</th>
                                            <th>비고</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{--@foreach($info as $item)
                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->business_number}}</td>
                                                <td>건</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endforeach--}}
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
    <script type="text/javascript" src="/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/dist/Common.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.resize.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.pie.js"></script>
    <script src="/admin/bower_components/Flot/jquery.flot.categories.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/stat/page.companyStat.datatable.js"></script>
@endsection

@section("script")
    <script>
        $(document).ready(function() {
            setCalcDate(0,'d');
        });
    </script>

@endsection