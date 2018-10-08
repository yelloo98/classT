@extends('admin.layouts.app')

@section("style")
    <link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <form id="form" action="/admin/qna/company">
                            <div class="row">
                                <div class="col-xs-1" style="margin-top:10px;"><span>검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('all' => '전체', 'title' => '제목', 'writer_name' => '작성자') as $key => $value)
                                            <option value="{{$key}}" @if(app('request')->input('searchType') == $key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="searchWord" value="{{app('request')->input('searchWord')}}">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                    {{--                                        <button id="btn_search">검색</button>--}}
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>답변상태</span></div>
                                <div class="col-xs-6">
                                    <label><input type="radio" class="minimal" name="searchResponseYN" value="all" checked/>전체</label>
                                    <label><input type="radio" class="minimal" name="searchResponseYN" value="Y"/>답변완료</label>
                                    <label><input type="radio" class="minimal" name="searchResponseYN" value="N"/>미완료</label>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>회원 타입</span></div>
                                <div class="col-xs-6">
                                    <label><input type="radio" class="minimal" name="searchWriterType" value="all" checked/>전체</label>
                                    <label><input type="radio" class="minimal" name="searchWriterType" value="TEACHER"/>강사회원</label>
                                    <label><input type="radio" class="minimal" name="searchWriterType" value="COMPANY"/>기업회원</label>
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
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="30%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="30%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>분류</th>
                                <th>제목</th>
                                <th>작성자</th>
                                <th>답변여부</th>
                                <th>등록일</th>
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
    <script type="text/javascript" src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/qna/page.qna.datatable.js"></script>
    <script>
        $(document).ready(function() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

        });
    </script>
@stop