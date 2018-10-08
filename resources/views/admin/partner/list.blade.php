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
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>검색</span></div>
                                <div class="col-xs-5">
                                    <span style="margin-right:2%;">협력사명</span>
                                    <input type="text" class="form-control" name="searchWord" style="display: inline-block; width: 80%;" >
                                </div>
                                <div class="col-xs-1">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span>상태</span></div>
                                <div class="col-xs-6">
                                    <label><input type="radio" class="minimal" name="searchUseYN" value="all" checked/>전체</label>
                                    <label><input type="radio" class="minimal" name="searchUseYN" value="Y"/>Y</label>
                                    <label><input type="radio" class="minimal" name="searchUseYN" value="N"/>N</label>
                                </div>
                            </div>
                        </form>

                        <h3 class="box-title totalCnt" style="margin-top:5px;"></h3>
                        <button type="button" class="btn btn-default" style="float:right;" data-toggle="modal" data-id="0" href="#partner_upload">협력사 등록</button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="table_list" class="table table-bordlered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="30%"/>
                                <col width="30%"/>
                                <col width="5%"/>
                                <col width="5%"/>
                                <col width="10%"/>
                                <col width="15%"/>
                            </colgroup>
                            <thead>
                                <th>No</th>
                                <th>협력사명</th>
                                <th>배너등록</th>
                                <th>수정</th>
                                <th>삭제</th>
                                <th>노출여부</th>
                                <th>등록일</th>
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

    <div class="modal fade" id="modal-partner">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">협력사 등록</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" style="margin-bottom : 5px;">
                        <form id="uploadForm" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id"/>
                            <table class="table table-bordered" style="margin-left:2%; vertical-align: middle!important;">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td colspan="2">배너의 사이즈는 000X0000입니다.</td>
                                    </tr>
                                    <tr>
                                        <th>협력사명</th>
                                        <td><input type="text" name="name" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <th>URL</th>
                                        <td><input type="text" name="banner_url" class="form-control" /></td>
                                    </tr>
                                    <tr>
                                        <th>협력사 배너</th>
                                        <td><input type="file" name="banner_img" class="form-control" accept=".png, .jpg"/> {{--<input type="text" id="banner_img" class="form-control" style="margin-top: 2px;" disabled/>--}}</td>
                                    </tr>
                                    <tr>
                                        <th>메인 노출여부</th>
                                        <td>
                                            <select name="use_yn" class="form-control" style="width:35%;">
                                                <option value="Y">Y</option>
                                                <option value="N">N</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center;">
                                            <button class="btn" onclick="javascript:savePartner(); return false;">저장</button>
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
    <script type="text/javascript" src="/admin/page/partner/page.partner.func.js" ></script>
    <script type="text/javascript" src="/admin/page/partner/page.partner.init.js" ></script>
    <script type="text/javascript" src="/admin/page/partner/page.partner.datatable.js" ></script>
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