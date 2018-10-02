@extends('admin.layouts.app')

@section("style")
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <form id="form" action="/admin/program">
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1" style="text-align:center; vertical-align:middle;"><span>검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('title' => '교육프로그램명', 'content' => '내용') as $key => $value)
                                            <option value="{{$key}}" @if(app('request')->input('searchType') == $key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="searchWord" value="{{app('request')->input('searchWord')}}">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>
                        </form>
                        <br/>
                        <h3 class="box-title totalCnt"></h3>
                    </div>


                    <div class="box-body">
                        <table id="table_list" class="table table-bordered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="10%"/>
                                <col width="50%"/>
                                <col width="20%"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>교육프로그램명</th>
                                    <th>작성자</th>
                                    <th>등록일</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section("script")
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/program/page.program.datatable.js"></script>
@stop