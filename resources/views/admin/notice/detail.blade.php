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
                        <form id="form" method="post" action="/admin/notice/save">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info->id}}"/>
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
                                        @if($info->id!="")
                                        <tr>
                                            <th>등록일</th>
                                            <td>{{$info->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <th>작성자</th>
                                            <td>{{$info->writer or ''}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>내용 *</th>
                                            <td>
                                                <textarea name="content" id="content" class="form-control" style="width:100%;" rows="15">{{$info->content}}</textarea>
                                            </td>
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
                        <li class="pull-right">
                            <button type="button" class="btn" onclick="location.href='/admin/notice';">목록</button>
                        </li>
                        @if($info->id)
                            <li class="pull-right margin-r-5">
                                <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="delete_btn">삭제</button>
                            </li>
                        @endif
                        <li class="pull-right margin-r-5">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">@if($info->id) 수정 @else 등록 @endif</button>
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
    <script type="text/javascript" src="/admin/page/notice/page.notice.func.js" ></script>
    <script type="text/javascript" src="/admin/page/notice/page.notice.form.js" ></script>
    <script type="text/javascript" src="/admin/page/notice/page.notice.init.js" ></script>

    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 300
            });

            $("#submit_btn").click(function(){
                $("input[name=edit_type]").val("write");
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