@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12 p-b-30 oh">
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
                                        <th>수강인원</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>평점</th>
                                        <td>
                                            @for($i=1; $i<=5; $i++)
                                                @if($info->score+0.5<$i)
                                                    <i class="fa fa-star-o"></i>
                                                @elseif( ($info->score - 0.5 ) == ($i-1))
                                                    <i class="fa fa-star-half-empty"></i>
                                                @else
                                                    <i class="fa fa-star"></i>
                                                @endif
                                            @endfor
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>강의일자</th>
                                        <td>{{$info->class_start_dt}}</td>
                                    </tr>
                                    <tr>
                                        <th>내용</th>
                                        <td>
                                            <textarea class="form-control" style="width:100%;" rows="10" disabled>{{$info->content}}</textarea>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div>
                    <ul class="pager wizard no-style">
                        <li class="pull-right">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" onclick="location.href='/admin/review/company'">목록</button>
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
    <script>
        $(document).ready(function() {
            $("#submit_btn").click(function() {
                $("#form").submit();
            });
        });
    </script>
@stop