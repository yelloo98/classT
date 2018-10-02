@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{$title}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="form" method="post" action="/admin/program/save" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info->id or old('id')}}"/>
                            <input type="hidden" value="write" name="edit_type">
                            <div class="col-md-12 m-b-30">
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="17%"/>
                                        <col width="83%"/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>제목 *</th>
                                            <td><input type="text" class="form-control" name="title" value="{{$info->title or old('title')}}"></td>
                                        </tr>
                                        @if(!empty($info))
                                        <tr>
                                            <th>등록일</th>
                                            <td>{{$info->created_at or old('created_at')}}</td>
                                        </tr>
                                        <tr>
                                            <th>작성자</th>
                                            <td>{{$info->writer_name or old('writer_name')}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>내용 *</th>
                                            <td>
                                                <textarea id="content" name="content">{{$info->content or old('content')}}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>짧은 소개글</th>
                                            <td>
                                                <textarea name="program_topic">{{$info->program_topic or old('program_topic')}}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>썸네일 이미지</th>
                                            <td>
                                                {{--@if($info->thumbnail_name != null)--}}
                                                    {{--<a href="/api/download/{{$info->id}}/{{$info->thumbnail_name}}/PROGRAM_THUMBNAIL">{{$info->thumbnail_name}}</a>--}}
                                                {{--@else--}}
                                                    {{--<input type="file" name="thumbnail_name"/>--}}
                                                {{--@endif--}}
                                            </td>
                                        </tr>
                                        {{--<tr>
                                            <th>링크1</th>
                                            <td><input name="etc_link_1" type="text" class="form-control" value="{{$info->etc_link_1 or old('etc_link_1')}}"/></td>
                                        </tr>
                                        <tr>
                                            <th>링크2</th>
                                            <td><input name="etc_link_2" type="text" class="form-control" value="{{$info->etc_link_2 or old('etc_link_2')}}"/></td>
                                        </tr>
                                        <tr>
                                            <th>링크3</th>
                                            <td><input name="etc_link_3" type="text" class="form-control" value="{{$info->etc_link_3 or old('etc_link_3')}}"/></td>
                                        </tr>--}}
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div>
                </div>
                <!-- /.box -->
                <div class="col-md-12 p-b-30 oh">
                    <ul class="pager wizard no-style">
                        <li class="pull-right">
                            <button type="button" class="btn" onclick="location.href='/admin/program';">목록</button>
                        </li>
                        @if(!empty($info))
                            <li class="pull-right margin-r-5">
                                <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="delete_btn">삭제</button>
                            </li>
                        @endif
                        <li class="pull-right margin-r-5">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn"> @if(!empty($info)) 수정 @else 등록 @endif</button>
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

@section("plugins")

    <!-- include libraries(jQuery, bootstrap) -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

    <!-- include summernote css/js -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

    <!-- Plugin : Validation -->
    <script type="text/javascript" src="/plugins/validation/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/validation.js"></script>
    <!-- Plugin : InputMark -->
    <script type="text/javascript" src="/plugins/input-mask/jquery.mask.js"></script>
    <script type="text/javascript" src="/js/inputmark.js"></script>

@endsection
@section("script")

    <!-- page function -->
    <script type="text/javascript" src="/js/common.js" ></script>
    <script type="text/javascript" src="/admin/page/program/page.program.func.js" ></script>
    <script type="text/javascript" src="/admin/page/program/page.program.form.js" ></script>
    <script type="text/javascript" src="/admin/page/program/page.program.init.js" ></script>

    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 300
            });

            $("#submit_btn").click(function(){
               $("#form").submit();
            });

            $("#delete_btn").click(function(){
                if(confirm("정말로 삭제하시겠습니까?")) {
                    $("input[name=edit_type]").val("delete");
                    $("#form").submit();
                }
            });
        });
    </script>
@stop