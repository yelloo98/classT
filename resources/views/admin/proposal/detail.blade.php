@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">의뢰상세정보</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12 m-b-30">
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="17%"/>
                                    <col width="83%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th>기업명</th>
                                    <td>{{$info->company_name}}</td>
                                </tr>
                                <tr>
                                    <th>강의명</th>
                                    <td>{{$info->class_title}}</td>
                                </tr>
                                <tr>
                                    <th>강사명</th>
                                    <td>{{$info->teacher_name}}</td>
                                </tr>
                                <tr>
                                    <th>강의장소</th>
                                    <td>{{$info->class_place}}</td>
                                </tr>
                                <tr>
                                    <th>강의일</th>
                                    <td>{{$info->class_start_dt}}</td>
                                </tr>
                                <tr>
                                    <th>강의시간</th>
                                    <td>{{$info->class_time}}</td>
                                </tr>
                                <tr>
                                    <th>강의료</th>
                                    <td>{{number_format($info->class_pay)}}</td>
                                </tr>
                                <tr>
                                    <th>강의분야</th>
                                    <td>{{$info->class_field}}</td>
                                </tr>
                                <tr>
                                    <th>직급</th>
                                    <td>{{stdRank($info->etc_rank)}}</td>
                                </tr>
                                <tr>
                                    <th>연령</th>
                                    <td>{{($info->etc_age!="") ? stdAge($info->etc_age) : ''}}</td>
                                </tr>
                                <tr>
                                    <th>인원</th>
                                    <td>{{($info->etc_number!="") ? stdNumber($info->etc_number) : ''}}</td>
                                </tr>
                                <tr>
                                    <th>추가문의/요청</th>
                                    <td>{{$info->etc_memo}}</td>
                                </tr>
                                <tr>
                                    <th>제안서 요청일</th>
                                    <td>{{$info->request_dt}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">제안서 첨부</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" id="form" action="/admin/proposal/save" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info->id}}"/>
                            <div class="col-md-12 p-b-30 oh">
                                <table class="table table-bordered tableDetail" style="float:right; width:100% !important; margin : 15px;">
                                    <colgroup>
                                        <col width="17%"/>
                                        <col width="83%"/>
                                    </colgroup>
                                    <tbody>
                                        <tr class="proposal-upload" @if($info->pdf_name!="") style="display:none;" @endif >
                                            <th>제안서 첨부</th>
                                            <td><input type="file" name="pdf_name" accept="application/pdf"/></td>
                                        </tr>
                                        <tr class="proposal-download" @if($info->pdf_name=="") style="display:none;" @endif >
                                            <th>제안서</th>
                                            <td>
                                                <a href="javascript:fileDownload({{$info->id}}, '{{$info->pdf_name}}', 'PROPOSAL_REQUEST')">{{$info->pdf_name}}</a>
                                                <button class="btn" onclick="fileDownload({{$info->id}}, '{{$info->pdf_name}}', 'PROPOSAL_REQUEST'); return false;">제안서보기</button>
                                                <button class="btn" onclick="delProposalFile({{$info->id}}); return false;">제안서삭제</button>
                                            </td>
                                        </tr>
                                        <tr class="proposal-download" @if($info->pdf_name =="") style="display:none;" @endif>
                                            <th>제안서 전달일</th>
                                            <td>{{$info->response_dt}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="col-md-12 p-b-30 oh">
                    <ul class="pager wizard no-style">
                        <li class="pull-right" style="margin-left:5px;">
                            <button type="button" class="btn" id="list_btn" onclick="location.href='/admin/proposal'">목록</button>
                        </li>
                        <li class="pull-right" @if($info->pdf_name == "") style="display:block;" @endif class="btn-save">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">저장</button>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section("script")
    <script type="text/javascript" src="/js/common.js" ></script>
    <script type="text/javascript" src="/dist/Common.js" ></script>
    <script type="text/javascript" src="/admin/page/proposal/page.proposal.func.js"></script>
    <script type="text/javascript" src="/admin/page/proposal/page.proposal.init.js"></script>
    <script>

    </script>
@endsection

