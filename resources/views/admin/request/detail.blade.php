@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="/admin/dist/css/custom.css" >
    <link rel="stylesheet" href="/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css">
    <style>
        .recommend, .refuse { margin-right: 1%;}
        .recommend-del { border:solid 1px; padding:3px 7px; }
        .recommend-btn { border:solid 1px; padding:3px 2px; background-color: #f4f4f4;}
       /* .modal-table > th, td {
            text-align: center;
        }*/

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
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-body" style="">
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
                                    <input type="hidden" id="status" value="{{$info->status}}"/>
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
                        <h3 class="box-title">의뢰정보</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12 m-b-30">
                            <form method="post" id="form" action="/admin/request/update">
                                {{csrf_field()}}
                                <input type="hidden" name="id" value="{{$info->id}}"/>
                                <input type="hidden" name="users_teacher_id" value="{{$info->users_teacher_id}}"/>
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="17%"/>
                                        <col width="83%"/>
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
                                            <td><input type="text" name="class_title" class="form-control" value="{{$info->class_title}}"/></td>
                                        </tr>
                                        <tr>
                                            <th>강의분야</th>
                                            <td>
                                                <input type="hidden" name="lecture" id="choiceLectureCodeList" value="{{$requestLecture->code}}"/>
                                                <input type="text" id="choiceLectureTitle" value="{{$requestLecture->title}}" class="form-control" style="width:75%; display:inline-block;" readonly/>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lecture">강의분야 선택</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>요청마감</th>
                                            <td>{{$info->request_deadline}}</td>
                                        </tr>
                                        <tr>
                                            <th>강의장소</th>
                                            <td>{{$info->class_place}}</td>
                                        </tr>
                                        <tr>
                                            <th>강의일</th>
                                            <td>
                                                <div class="form-inline">
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="class_start_dt" name="class_start_dt" value="{{$info->class_start_dt}}" autocomplete="off" />
                                                    </div>
                                                    ~
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="class_end_dt" name="class_end_dt" value="{{$info->class_end_dt}}" autocomplete="off" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>강의시간</th>
                                            <td>
                                                <select name="class_time" class="form-control" style="width:40%;">
                                                    @foreach(getClassTime() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->class_time or $key == old('class_time')) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
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
                                            <th>강의료</th>
                                            <td>{{$info->class_time_pay}}</td>
                                        </tr>
                                        <tr>
                                            <th>직급</th>
                                            <td>
                                                <select name="etc_rank" class="form-control" style="width:40%;">
                                                    @foreach($requestRank as $item)
                                                        <option value="{{$item->code}}" @if($item->code == $info->etc_rank || $item->code == old('etc_rank')) selected @endif>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>인원</th>
                                            <td>
                                                <select class="form-control" name="etc_number" style="width:40%;">
                                                    @foreach(stdNumber() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->etc_number || $key == old('etc_number')) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>연령</th>
                                            <td>
                                                <select name="etc_age" class="form-control" style="width:40%;">
                                                    @foreach(stdAge() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->etc_age || $key == old('etc_age')) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>추가문의/요청</th>
                                            <td>
                                                <textarea name="etc_memo" class="form-control">
                                                    {{$info->etc_memo}}
                                                </textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>강의의뢰일</th>
                                            <td>{{$info->created_at}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title">추천강사찾기 & 제안서</h3>
                        <div class="box-tools pull-right">
                            <button type="button" id="proposal_area" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="col-md-12 m-b-30">
                            <button type="button" class="btn btn-default" style="margin-bottom:1%;" onclick="mSearchTeacher(); return false;">강사찾기</button><br/>
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px; text-align: center; vertical-align: middle;">
                                <colgroup>
                                    <col width="10%"/>
                                    <col width="6%"/>
                                    <col width="10%"/>
                                    <col width="6%"/>
                                    <col width="14%"/>
                                    <col width="14%"/>
                                    <col width="6%"/>
                                    <col width="14%"/>
                                    <col width="14%"/>
                                    <col width="6%"/>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>강사명</th>
                                        <th>제안서<br/>요청상태</th>
                                        <th>제안서</th>
                                        <th>발송</th>
                                        <th>제안서 발송일</th>
                                        <th>강의요청일자</th>
                                        <th>출강요청</th>
                                        <th>출강요청일자</th>
                                        <th>강의확정일자</th>
                                        <th>강사 삭제</th>
                                    </tr>
                                </thead>
                                <tbody id="recommend_teacher_list">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                {{--상태값이 결제 완료인경우--}}
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">결제내역</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
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

                <div class="col-md-12 p-b-30 oh">
                    <ul class="pager wizard no-style">
                        <li class="pull-right" style="margin-left:5px;">
                            <button type="button" class="btn" id="list_btn" onclick="location.href='/admin/request'">목록</button>
                        </li>
                        <li class="pull-right">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">@if($info) 수정 @else 등록 @endif</button>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-teacher">
        <div class="modal-dialog" style="width:70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">강사 찾기</h4>
                </div>
                <div class="modal-body col-md-12 m-b-30">
                    <table class="table table-bordered modal-table" style="">
                        <colgroup>
                            <col width="10%"/>
                            <col width="20%"/>
                            <col width="30%"/>
                            <col width="20%"/>
                            <col width="20%"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>이름</th>
                                <th>강사 이메일</th>
                                <th>전화번호</th>
                                <th>추천강사등록</th>
                            </tr>
                        </thead>
                        <tbody id="resultSearchTeacher">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" id="btn_close" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
    <script src="/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/admin/bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
    <script type="text/javascript" src="/js/common.js" ></script>
    <script type="text/javascript" src="/dist/Common.js" ></script>
    <script type="text/javascript" src="/admin/page/request/page.request.func.js"></script>
    <script type="text/javascript" src="/admin/page/request/page.request.init.js"></script>
    <script type="text/javascript" src="/admin/page/page.api.lecture.func.js" ></script>
@endsection