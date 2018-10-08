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
                        <form id="form" action="/admin/request/teacher">
                            {{csrf_field()}}
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('all' => '전체', 'mid_cat' => '중분류', 's_cat' => '소분류') as $key => $value)
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

                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>분류</span></div>
                                <div class="col-xs-6">
                                    <label><input type="radio" class="minimal" name="searchCat" value="all" checked/>전체</label>
                                    <label><input type="radio" class="minimal" name="searchCat" value="2"/>중분류</label>
                                    <label><input type="radio" class="minimal" name="searchCat" value="3"/>소분류</label>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-sm-6" style="margin-top:3%;">
                                <p style="margin-left:2%;">클래스이음 강의분야 관리 화면입니다.</p>
                            </div>
                            <div class="col-sm-6">
                                <table id="scoreTable" class="table table-bordered table-hover" style="float:right; width:100%; margin : 15px; text-align: center!important;">
                                    <colgroup>
                                        <col width="25%"/>
                                        <col width="25%"/>
                                        <col width="25%"/>
                                        <col width="25%"/>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>전체(2분류+3분류)</th>
                                            <th>1분류</th>
                                            <th>2분류</th>
                                            <th>3분류</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
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
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-id="0" href="#mid_lecture_upload">중분류 등록</button>
                            <button type="button" class="btn btn-default" data-toggle="modal" data-id="0" href="#small_lecture_upload">소분류 등록</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="table_list" class="table table-bordlered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="20%"/>
                                <col width="20%"/>
                                <col width="20%"/>
                                <col width="15%"/>
                                <col width="5%"/>
                                <col width="5%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>대분류</th>
                                    <th>중분류</th>
                                    <th>소분류</th>
                                    <th>등록일</th>
                                    <th>수정</th>
                                    <th>삭제</th>
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

    <div class="modal fade" id="modal-lecture-mid">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">강의분야 중분류 등록</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" style="margin-bottom : 5px;">
                        <form id="midUploadForm" enctype="multipart/form-data">
                            <input type="hidden" name="m_id"/>
                            <table class="table table-bordered" style="margin-left:2%; vertical-align: middle!important;">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>대분류</th>
                                        <td>
                                            <select name="m_large_cate" id="m_large_cate" class="form-control">
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>중분류</th>
                                        <td><input type="text" name="m_title" class="form-control" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center;">
                                            <button class="btn" onclick="javascript:saveLecture('m'); return false;">저장</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="modal-lecture-small">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">강의분야 소분류 등록</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" style="margin-bottom : 5px;">
                        <form id="smallUploadForm" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="s_id"/>
                            <table class="table table-bordered" style="margin-left:2%; vertical-align: middle!important;">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th>대분류</th>
                                    <td>
                                        <select name="s_large_cate" id="s_large_cate" class="form-control">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>중분류</th>
                                    <td>
                                        <select name="s_mid_cate" id="s_mid_cate" class="form-control">
                                        </select>
                                </tr>
                                <tr>
                                    <th>소분류</th>
                                    <td><input type="text" name="s_title" class="form-control"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:center;">
                                        <button class="btn" onclick="javascript:saveLecture('s'); return false;">저장</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@section("script")
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8"></script>
    <script type="text/javascript" src="/js/common.js" ></script>
    <script type="text/javascript" src="/admin/page/lecture/page.lecture.func.js" ></script>
    <script type="text/javascript" src="/admin/page/lecture/page.lecture.init.js" ></script>
    <script type="text/javascript" src="/admin/page/lecture/page.lecture.datatable.js" ></script>
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

        });
    </script>
@endsection