@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="/admin/dist/css/custom.css" >
    <link rel="stylesheet" href="/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css">

    <style>
        .form-control {  display : inline-block !important; }

        .lecture-large-title {  display: inline-block; width:100px; border: solid 1px; font-weight: bold; margin-left: 1px; padding-left: 1px;}
        .large-1 .lecture-code-choice {  background-color:#0a6aa1; font-weight: bold; color:white; }
        .large-2 .lecture-code-choice {  background-color:#00a157; font-weight: bold; color:white; }
        .large-3 .lecture-code-choice {  background-color:#8b5b9f; font-weight: bold; color:white; }
        .large-4 .lecture-code-choice {  background-color:#8B98AB; font-weight: bold; color:white; }
        .col-mid-cate { display : inline-block;}

        .search-lecture { margin-right: 1%;}
        .search-lecture-choice { border:solid 1px; padding:3px 7px; }
    </style>
@endsection
@section('content')
    <section class="content" id="div_main">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="col-md-12 m-b-30">
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="85%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>상태</th>
                                        <td class="text-align-left">
                                            @foreach(recommendStatus() as $key => $value)
                                                @if($key == $info->status)
                                                    <b style="font-size: 1.3em">{{$value}}</b>
                                                @else
                                                    {{$value}}
                                                @endif
                                                @if($key != '7')
                                                    >
                                                @endif
                                            @endforeach
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
                        <h3 class="box-title">의뢰상세정보</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <form method="post" id="form" action="/admin/class/update">
                        <!-- /.box-header -->
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                        <div class="box-body">
                            <div class="col-md-12 m-b-30">
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="15%"/>
                                        <col width="35%"/>
                                        <col width="15%"/>
                                        <col width="35%"/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>기업명</th>
                                            <td>{{$info->company_name}}</td>
                                            <th>평가선택</th>
                                            <td>
                                                <input type="radio" name="evaluate_type" value="basic" @if($info->evaluate_type==null || $info->evaluate_type=='basic' || old('evaluate_type') == 'basic') checked @endif/>기본평가
                                                <input type="radio" name="evaluate_type" value="one" @if($info->evaluate_type=='one' || old('evaluate_type') == 'one') checked @endif/>일회성평가
                                        </tr>
                                        <tr>
                                            <th>강의명</th>
                                            <td colspan="3"><input type="text" class="form-control" name="class_title" value="{{$info->class_title}}"></td>
                                        </tr>
                                        <tr>
                                            <th>강사명</th>
                                            <td>{{$info->teacher_name}}</td>
                                            <th>강사아이디</th>
                                            <td>{{$info->teacher_email}}</td>
                                        </tr>
                                        <tr>
                                            <th>강의분야</th>
                                            <td colspan="3">
                                                <input type="hidden" name="lecture" id="choiceLectureCodeList" value="{{$requestLecture->code}}"/>
                                                <input type="text" id="choiceLectureTitle" value="{{$requestLecture->title}}" class="form-control" style="width:75%; display:inline-block;" readonly/>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lecture">강의분야 선택</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>강의장소</th>
                                            <td>
                                                <div class="form-inline">
                                                    <div class="form-group" style="width: 75%;">
                                                        <input type="hidden" name="etc_area" value="{{$info->etc_area or old('etc_area')}}"/>{{--강의지역코드--}}
                                                        <input type="text" class="form-control" style="width: 100%;" value="{{$info->class_place}}" name="class_place" readonly>
                                                    </div>
                                                    <button class="btn btn-default" onclick="searchAddress('class_place'); return false;">주소 검색</button>
                                                </div>
                                            </td>
                                            <th>강의시작시간</th>
                                            <td>
                                                <div class="form-inline bootstrap-timepicker">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control timepicker" name="class_start_time" value="{{$info->class_start_time}}">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>강의일</th>
                                            <td class="text-align-left">
                                                <div class="form-inline">
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="class_start_dt" autocomplete="off" />
                                                    </div>
                                                    ~
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="class_end_dt" autocomplete="off" />
                                                    </div>
                                                </div>
                                            </td>

                                            <th>강의시간</th>
                                            <td class="text-align-left">
                                                <select name="class_time" class="form-control">
                                                    @foreach(getClassTime() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->class_time or $key == old('class_time')) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
    {{--                                            <input type="text" class="col-xs-1" style="height: 32px; padding: 0; text-align: center;" name="class_time" value="{{$info->class_time or old('class_time')}}" maxlength="2">시간</td>--}}
                                        </tr>
                                        <tr>
                                            <th>직급</th>
                                            <td>
                                                <select name="etc_rank" class="form-control">
                                                    @foreach($requestRank as $item)
                                                        <option value="{{$item->code}}" @if($item->code == $info->etc_rank || $item->code == old('etc_rank')) selected @endif>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <th>인원</th>
                                            <td>
                                                <select class="form-control" name="etc_number">
                                                    @foreach(stdNumber() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->etc_number || $key == old('etc_number')) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>연령</th>
                                            <td>
                                                <select name="etc_age" class="form-control">
                                                    @foreach(stdAge() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->etc_age || $key == old('etc_age')) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            <th>강의료</th>
                                            <td><input type="text" class="form-control" value="{{$info->class_pay_type == 'time' ? number_format($info->class_time_pay) : number_format($info->class_count_pay)}}"></td>
                                        </tr>
                                        <tr>
                                            <th>추가문의/요청</th>
                                            <td colspan="3">
                                                <textarea class="form-control" name="etc_memo">{{$info->etc_memo or old('etc_memo')}}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>등록일</th>
                                            <td class="text-align-left" colspan="3">{{$info->created_at}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </form>
                </div>
                <!-- /.box -->

                <div class="box collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title">제안서</h3>
                        <div class="box-tools pull-right">
                            <button type="button" id="proposal_area" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="col-md-12 m-b-30">
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>강사명</th>
                                        <th>제안서요청상태</th>
                                        <th>제안서</th>
                                        <th>발송</th>
                                        <th>제안서 발송일</th>
                                        <th>강의요청일</th>
                                        <th>강의확정일</th>
                                    </tr>
                                </thead>
                                <tbody id="recommend_teacher_list"></tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title">결제내역</h3>
                        <div class="box-tools pull-right">
                            <button type="button" id="payment_area" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="col-md-12 m-b-30">
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                <colgroup>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th colspan="2">결제일</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">정산내용</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">강의료</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">카드수수료(3%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">수수료</th>
                                        <th>클래스이음(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>멘토(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">맨토 리워드</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">제안서</th>
                                        <th>클래스이음(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>멘토(%)</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">원천징수</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">송금액</th>
                                        <td></td>
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
                        <li class="pull-right" style="margin-left:5px;">
                            <button type="button" class="btn" id="list_btn" onclick="location.href='/admin/class'">목록</button>
                        </li>
                        <li class="pull-right">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">수정</button>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->


    {{--강의 분야 모달--}}
    <div class="modal fade" id="modal-lecture">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">강의분야 선택</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 m-b-30" style="margin-bottom : 5px;">
                        <input type="text" name="searchLectureWord" class="form-control" style="width: 60%;"/>
                        <button type="button" class="btn btn-default" onclick="javascript:searchLecture(); return false;" style="width: 15%;">검색</button>
                    </div>
                    <div class="col-md-12 m-b-30">
                        <div id="resultSearchLectureList"></div>
                        @foreach ($lectureLargeCode as $item)
                            <span class="lecture-large-title">{{$item->title}}</span>
                            <div class="{{'large-'.$item->large_cate}} col-mid-cate">
                                @foreach ($lectureSmallCode as $cateItem)
                                    @if( strpos($cateItem->code, $item->code) !== false )
                                        <span><button class='choiceLecture @if($cateItem->checkYN == 'Y') lecture-code-choice @endif' data-code="{{$cateItem->code}}" data-title="{{$cateItem->title}}" data-choice = "{{$cateItem->checkYN == 'Y' ? 1 : 0}}">{{$cateItem->title}}</button> </span>
                                    @endif
                                @endforeach
                            </div>
                            <br/>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" id="btn_close" data-dismiss="modal">확인</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section("plugins")
    <!-- Plugin : Validation -->
    <script type="text/javascript" src="/plugins/validation/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/validation.js"></script>
    <!-- Plugin : InputMark -->
    <script type="text/javascript" src="/plugins/input-mask/jquery.mask.js"></script>
    <script type="text/javascript" src="/js/inputmark.js"></script>
    <script type="text/javascript" src="/js/common.js" ></script>
    <script type="text/javascript" src="/dist/Common.js" ></script>
    <script src="/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/admin/bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
    <script type="text/javascript" src="/web/page/map/page.map.func.js" ></script>
    <script type="text/javascript" src="/admin/page/class/page.class.form.js" ></script>
    <script type="text/javascript" src="/admin/page/class/page.class.init.js" ></script>
    <script type="text/javascript" src="/admin/page/page.api.lecture.func.js" ></script>
    <script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
@endsection

@section("script")
    <script>
        $(document).ready(function() {
            $("#submit_btn").click(function() {
                $("#form").submit();
            });
            //Date picker
            $('#class_start_dt').datepicker({
                format: 'yyyy-mm-dd'

            });
            $('#class_end_dt').datepicker({
                format: 'yyyy-mm-dd'
            });

            $('input[name=class_start_time]').timepicker({
                showMeridian: false,
                showInputs: false,
                stepping : 1
            });
        });
    </script>
@stop