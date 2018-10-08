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
                                        <th>분류</th>
                                        <td>{{$info->writer_type=='company' ? '기업' : '강사'}}</td>
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
                                        <th>내용</th>
                                        <td>
                                            <textarea name="content" id="content" class="form-control" style="width:100%;" rows="10" disabled>{{$info->content}}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>첨부파일</th>
                                        <td>
                                            @if($info->content_file!="")
                                                <a href="javascript:fileDownload({{$info->id}}, '{{$info->content_file}}', 'QNA')">{{$info->content_file}}</a>
                                            @endif
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
                        <form id="form" method="post" action="/admin/qna/save" enctype="multipart/form-data">
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
                                            <td>{{$info->res_admin_str}}</td>
                                        </tr>
                                        <tr>
                                            <th>답변 *</th>
                                            <td>
                                                <textarea name="res_content" id="res_content" class="form-control" style="width:100%;" rows="15">{{$info->res_content}}</textarea>
                                            </td>
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
                                <button type="button" class="btn" onclick="location.href='/admin/qna';">목록</button>
                            </li>
                            <li class="pull-right margin-r-5">
                                <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">답변등록</button>
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
    <!-- include summernote css/js -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    <script src="/dist/Common.js"></script>
    <script>
        $(document).ready(function() {
            $('#res_content').summernote({
                height: 300
            });

            $("#submit_btn").click(function() {
                $("#form").submit();
            });
        });
    </script>
@stop