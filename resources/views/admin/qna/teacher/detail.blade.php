@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <div class="col-md-12 m-b-30">
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="17%"/>
                                    <col width="83%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th>제목</th>
                                    <td><input type="text" class="form-control" name="title" value="{{$info->title}}" disabled></td>
                                </tr>
                                <tr>
                                    <th>등록일</th>
                                    <td>{{$info->created_at}}</td>
                                </tr>
                                <tr>
                                    <th>작성자</th>
                                    <td>{{$info->writer}}</td>
                                </tr>
                                <tr>
                                    <th>내용 *</th>
                                    <td>
                                        <textarea name="content" id="content" class="form-control" style="width:100%;" rows="10" disabled>{{$info->content}}</textarea>
                                    </td>
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
                        <h3 class="box-title">답변</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="form" method="post" action="/admin/qna/teacher/save" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info->id}}"/>
                            <div class="col-md-12 m-b-30">
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="17%"/>
                                        <col width="83%"/>
                                    </colgroup>
                                    <tbody>
                                    @if($info->res_admin_id!="")
                                        <tr>
                                            <th>답변일</th>
                                            <td>{{$info->responded_at}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>작성자</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>내용 *</th>
                                        <td>
                                            <textarea name="res_content" id="content" class="form-control" style="width:100%;" rows="10">{{$info->res_content}}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>첨부파일</th>
                                        <td><input type="file" name="res_content_img"/></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->

                    <div class="col-md-12 p-b-30 oh">
                        <ul class="pager wizard no-style">
                            <li class="pull-right">
                                <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">@if($info->id) 수정 @else 등록 @endif</button>
                            </li>
                        </ul>
                    </div>

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
    <script>

        $(document).ready(function() {
            $("#submit_btn").click(function() {
                $("#form").submit();
            });
        });
    </script>
@stop