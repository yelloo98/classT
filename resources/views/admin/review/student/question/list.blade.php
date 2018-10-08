@extends('admin.layouts.app')

@section("style")
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <button id="btn_search" style="display:none;">검색</button>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">기본 평가항목</h3>
                        <button type="button" class="btn btn-default" style="float:right;" data-toggle="modal" data-id="0" href="#question_upload" onclick="questionUpload('basic'); return false;">등록</button>
                    </div>

                    <div class="box-body">
                        <table id="table_list" class="table table-bordlered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="30%"/>
                                <col width="15%"/>
                                <col width="15%"/>
                                <col width="10%"/>
                                <col width="5%"/>
                                <col width="5%"/>
                                <col width="15%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>질문</th>
                                    <th>질문주제</th>
                                    <th>답변형태</th>
                                    <th>점수</th>
                                    <th>수정</th>
                                    <th>삭제</th>
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

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">일회성 평가항목</h3>
                        <button type="button" class="btn btn-default" style="float:right;" data-toggle="modal" data-id="0" href="#question_upload" onclick="questionUpload('one'); return false;">등록</button>
                    </div>

                    <div class="box-body">
                        <table id="one_table_list" class="table table-bordlered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="35%"/>
                                <col width="25%"/>
                                <col width="10%"/>
                                <col width="5%"/>
                                <col width="5%"/>
                                <col width="15%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>질문</th>
                                    <th>답변형태</th>
                                    <th>점수</th>
                                    <th>수정</th>
                                    <th>삭제</th>
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

    <div class="modal fade" id="modal-question">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">평가항목 등록</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" style="margin-bottom : 5px;">
                        <form id="uploadForm">
                            {{csrf_field()}}
                            <input type="hidden" name="id"/>
                            <input type="hidden" name="evaluate_type"/>
                            <table class="table table-bordered" style="margin-left:2%;">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th>평가문항</th>
                                    <td><input type="text" name="question" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>질문 주제</th>
                                    <td><input type="text" name="question_topic" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>문항 형태</th>
                                    <td>
                                        <select name="answer_type" class="form-control" style="width:35%;">
                                            <option value="NUM">객관식</option>
                                            {{--<option value="STAR">별</option>--}}
                                            <option value="CONTENT">문자열</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>점수</th>
                                    <td><input type="number" name="answer_score" class="form-control"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:center;">
                                        <button class="btn" onclick="javascript:saveQuestion(); return false;">저장</button>
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
    <script type="text/javascript" src="/admin/page/question/page.question.func.js" ></script>
    <script type="text/javascript" src="/admin/page/question/page.question.init.js" ></script>
    <script type="text/javascript" src="/admin/page/question/page.question.datatable.js" ></script>
    <script type="text/javascript" src="/admin/page/question/page.oneQuestion.datatable.js" ></script>
@endsection