@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@endsection

@section('content')
    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <form id="form" action="/admin/review/company">
                            <div class="row">
                                <div class="col-xs-1" style="margin-top:10px;"><span>검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('title' => '강의명', 'name' => '강사명', 'company_name' => '기업명') as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="searchWord" value="">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>
                        </form>
                        <br/>
                        <h3 class="box-title totalCnt"></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="table_list" class="table table-bordered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="15%"/>
                                <col width="15%"/>
                                <col width="30%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>기업명</th>
                                <th>강사명</th>
                                <th>강의명</th>
                                <th>수강인원</th>
                                <th>평점</th>
                                <th>강의일자</th>
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
@section("script")
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/review/company/page.evaluateCompany.datatable.js"></script>
@stop