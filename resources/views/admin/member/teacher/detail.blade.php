@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="/admin/plugins/iCheck/all.css"/>
    <link rel="stylesheet" href="/admin/dist/css/custom.css"/>
    <link rel="stylesheet" href="/admin/bower_components/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="/admin/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
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

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <form method="post" id="form" action="/admin/member/teacher/update" enctype="multipart/form-data">
                    <div class="box">
                        <div class="box-body">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info->id}}"/>
                            <button type="button" class="btn btn-default" id="btn_contact" style="float:right; margin-right:3%;">프리미엄<br/>의뢰연결</button>
                            <div class="col-md-12 m-b-30">
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="15%"/>
                                        <col width="85%"/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>강사 QR 코드</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>등급</th>
                                            <td>
                                                <select class="form-control select2" style="width:20%;" name="grade">
                                                    <option value="">선택</option>
                                                    @foreach($teacherGrade as $item)
                                                        <option value="{{$item->id}}" @if($item->check_yn == 'Y') selected @endif>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>이메일 아이디</th>
                                            <td>{{$info->email}}</td>
                                        </tr>
                                        <tr>
                                            <th>이름</th>
                                            <td><input type="text" class="form-control" name="name" value="{{$info->name or old('name')}}" ></td>
                                        </tr>
                                        <tr>
                                            <th>연락처</th>
                                            <td><input type="text" class="form-control" name="phone" value="{{$info->phone or old('phone')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>생년월일</th>
                                            <td><input type="text" class="form-control" name="birth" value="{{$info->birth or old('birth')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>추천인</th>
                                            <td>
                                                <input type="hidden" name="recommend_user_id" value="{{$info->recommend_user_id}}"/>
                                                <input type="text" class="form-control" name="mentor_name" value="{{$info->mentor_name or old('mentor_name')}}" disabled style="width: 20%;"> <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-teacher">강사찾기</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>추천강사등록여부</th>
                                            <td>{{$info->recommend_teacher_yn}}</td>
                                        </tr>
                                        <tr>
                                            <th>멘티인원</th>
                                            <td><input type="text" name="mentee_max_num" class="form-control" value="{{$info->mentee_max_num or old('mentee_max_num')}}" style="width: 30%;"/>명 <button type="button" class="btn btn-default" onclick="getMenteeList({{$info->id}}); return false;">멘티보기</button></td>
                                        </tr>
                                        <tr>
                                            <th>성별</th>
                                            <td>
                                                <select class="form-control select2" style="width:20%;" name="gender">
                                                    <option value="G" @if($info->gender == 'G' || old('gender') == 'G') selected @endif>여자</option>
                                                    <option value="M" @if($info->gender == 'M' || old('gender') == 'M') selected @endif>남자</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>이메일수신동의</th>
                                            <td>
                                                <select class="form-control select2" style="width:20%;" name="send_email_agree">
                                                    <option value="Y" @if($info->send_email_agree == 'Y' || old('send_email_agree') == 'Y') selected @endif>Y</option>
                                                    <option value="N" @if($info->send_email_agree == 'N' || old('send_email_agree') == 'N') selected @endif>N</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>SMS수신동의</th>
                                            <td>
                                                <select class="form-control select2" style="width:20%;" name="send_sms_agree">
                                                    <option value="Y" @if($info->send_sms_agree == 'Y' || old('send_sms_agree') == 'Y') selected @endif>Y</option>
                                                    <option value="N" @if($info->send_sms_agree == 'N' || old('send_sms_agree') == 'N') selected @endif>N</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>가입일자</th>
                                            <td>{{$info->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <th>회원상태</th>
                                            <td>
                                                <select name="status" class="form-control select2" style="width:20%;">
                                                    @foreach(memberStatus() as $key => $value)
                                                        <option value="{{$key}}" @if($key == $info->status || old('status') == $key) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
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
                            <h3 class="box-title">강사정보</h3>
                        </div>
                        <!-- /.box-header -->
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
                                            <th>강의분야</th>
                                            <td colspan="3">
                                                <input type="hidden" name="lecture" id="choiceLectureCodeList" value="{{$teacherLecture->code}}"/>
                                                <input type="text" id="choiceLectureTitle" value="{{$teacherLecture->title}}" class="form-control" style="width:75%;" readonly/>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lecture">강의분야 선택</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>출강단체</th>
                                            <td colspan="3">
                                                <input type="checkbox" class="minimal all" id="group_all" name="group_all"/>
                                                <label for="group_all">전체</label>
                                                @foreach($teacherGroup as $key => $group)
                                                    <input type="checkbox" class="minimal check_group" name="group[]" value="{{$group->code}}" @if($group->check_yn == 'Y') checked @endif />
                                                    <label for="group">{{$group->title}}</label>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>출강업종</th>
                                            <td colspan="3">
                                                <input type="checkbox" class="minimal all" id="business_all"/>
                                                <label for="business_all">전체</label>
                                                @foreach($teacherBusiness as $key => $item)
                                                    <input type="checkbox" class="minimal check_business" name="business[]" value="{{$item->code}}" @if($item->check_yn == 'Y') checked @endif />
                                                    <label for="business">{{$item->title}}</label>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>대상직급</th>
                                            <td colspan="3">
                                                <input type="checkbox" class="minimal all" id="rank_all"/>
                                                <label for="rank_all">전체</label>
                                                @foreach($teacherRank as $key => $item)
                                                    <input type="checkbox" class="minimal check_rank" name="rank[]" value="{{$item->code}}" @if($item->check_yn == 'Y') checked @endif />
                                                    <label for="rank">{{$item->title}}</label>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>출강지역</th>
                                            <td colspan="3">
                                                <input type="checkbox" class="minimal all check_area" id="area_all"/>
                                                <label for="rank_all">전체</label>
                                                @foreach($teacherArea as $key => $item)
                                                    <input type="checkbox" class="minimal check_area" name="area[]" value="{{$item->code}}" @if($item->check_yn == 'Y') checked @endif />
                                                    <label for="area">{{$item->title}}</label>
                                                @endforeach
                                                <br/>
                                                <input type="checkbox" name="place_bargain" id="place_bargain" @if($info->place_bargain == 'Y' || old('place_bargain') == 'Y') checked @endif value="Y"/> <label for="place_bargain">협의 가능</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>강의료</th>
                                            <td colspan="3">시간당<input type="text" name="time_pay" class="form-control" value="{{$info->time_pay or old('time_pay')}}" style="width:20%; margin-left:1%;"/>만원
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp회당<input type="text" name="count_pay" class="form-control" value="{{$info->count_pay or old('count_pay')}}" style="width:20%; margin-left:1%;"/>만원</td>
                                        </tr>
                                        {{--<tr>
                                            <th>최종학위증명서</th>
                                            <td>
                                                @if($info->last_degree_url != "")
                                                    <button class="btn" onclick="fileDownload({{$info->id}}, '{{$info->last_degree}}', 'TEACHER_DEGREE'); return false;" style="margin-top:2px;">최종학위증명서 다운로드</button>
                                                @else
                                                    등록된 최종학위증명서가 없습니다.
                                                    <input type="file" name="last_degree"/>
                                                @endif
                                            </td>

                                            <th>통장사본</th>
                                            <td>
                                                @if($info->bank_copy_url != "")
                                                    <button class="btn" onclick="fileDownload({{$info->id}}, '{{$info->bank_copy}}', 'TEACHER_BANK'); return false;" style="margin-top:2px;">통장사본 다운로드</button>
                                                @else
                                                    등록된 통장사본이 없습니다.
                                                    <input type="file" name="bank_copy"/>
                                                @endif
                                            </td>
                                        </tr>--}}
                                        <tr>
                                            <th>계좌정보(은행/계좌번호/계좌주)</th>
                                            <td colspan="3"><input type="text" name="account_bank" value="{{$info->account_bank or old('account_bank')}}" class="form-control" style="width:20%; margin-right:1%;"/>
                                                / <input type="text" name="account_number" value="{{$info->account_number or old('account_number')}}" class="form-control" style="width:20%; margin-left:1%; margin-right:1%;"/>
                                                / <input type="text" name="account_host" value="{{$info->account_host or old('account_host')}}" class="form-control" style="width:20%; margin-left:1%; margin-right:1%;"/></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 p-b-30 oh">
                                <ul class="pager wizard no-style">
                                    <li class="pull-right">
                                        <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" id="submit_btn">@if($info) 수정 @else 등록 @endif</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </form>

                <!-- /.box -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">강사 스케줄</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="calendar"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <!-- /.box -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">강의 현황</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-body">
                            <table id="class_list" class="table table-bordlered table-hover" style="width:100%;">
                                <colgroup>
                                    <col width="5%"/>
                                    <col width="30%"/>
                                    <col width="15%"/>
                                    <col width="15%"/>
                                    <col width="10%"/>
                                    <col width="10%"/>
                                    <col width="20%"/>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>강의제목</th>
                                    <th>기업</th>
                                    <th>강사명</th>
                                    <th>강사아이디</th>
                                    <th>상태</th>
                                    <th>등록일</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">프리미엄 의뢰 현황</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="request_list" class="table table-bordered table-hover">
                            <colgroup>
                                <col width="5%"/>
                                <col width="25%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>의뢰제목</th>
                                <th>기업</th>
                                <th>요청마감기한</th>
                                <th>강사명</th>
                                <th>강사아이디</th>
                                <th>상태</th>
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
                        <h3 class="box-title">정산 현황</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="order_list" class="table table-bordered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="25%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>정산내용</th>
                                <th>의뢰처</th>
                                <th>강사명</th>
                                <th>강사아이디</th>
                                <th>강의료</th>
                                <th>강의일</th>
                                <th>기업평가</th>
                                <th>상태</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="col-md-12 p-b-30 oh">
                    <ul class="pager wizard no-style">
                        <li class="pull-right">
                            <button type="button" class="btn" onclick="location.href='/admin/member/teacher';">목록</button>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    {{--멘티 목록--}}
    <div class="modal fade" id="modal-mentee">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">멘티 리스트</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 m-b-30" style="margin-bottom : 5px;">
                        <table class="table table-bordered" style="margin-left:4%; width:96%;">
                            <colgroup>
                                <col width="10%"/>
                                <col width="30%"/>
                                <col width="30%"/>
                                <col width="30%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>강사명</th>
                                    <th>강사 이메일</th>
                                    <th>멘티 등록일</th>
                                </tr>
                            </thead>
                            <tbody id="resultMenteeList">
                            </tbody>
                        </table>
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

    {{--강사찾기--}}
    <div class="modal fade" id="modal-teacher">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">강사 찾기</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 m-b-30" style="margin-bottom : 5px;">
                        <select name="searchType" class="form-control" style="width: 20%; float:left;">
                            <option value="name">이름</option>
                            <option value="email">강사이메일</option>
                            <option value="phone">전화번호</option>
                        </select>
                        <input type="text" name="searchWord" class="form-control" style="width: 58%; float:left; margin-left: 1%; margin-right: 1%;"/>
                        <button type="button" class="btn btn-default" onclick="javascript:searchTeacher(); return false;" style="width: 20%;">검색</button>
                    </div>
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                        <table class="table table-bordered" style="margin-left:4%; width:96%;">
                            <colgroup>
                                <col width="23%"/>
                                <col width="52%"/>
                                <col width="25%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>이름</th>
                                <th>강사 이메일</th>
                                <th>전화번호</th>
                            </tr>
                            </thead>
                            <tbody id="resultSearchTeacher">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" id="btn_search_close" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    {{--프리미엄 의뢰 연결--}}
    <div class="modal fade" id="modal-contact">
        <div class="modal-dialog" style="width:75%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">프리미엄 의뢰 연결</h4>
                </div>
                <div class="modal-body">
                    {{--<div class="col-md-12 m-b-30">
                        <input type="text" name="searchLectureWord" class="form-control" style="width: 50%;"/>
                        <button type="button" class="btn btn-default" onclick="javascript:searchTeacher(); return false;" style="width: 20%;">검색</button>
                    </div>--}}

                    <div class="col-md-12 m-b-30">
                        <table id="table_list" class="table table-bordered table-hover">
                            <colgroup>
                                <col width="10%"/>
                                <col width="35%"/>
                                <col width="15%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="13%"/>
                                <col width="7%"/>
                            </colgroup>
                            <thead>
                                <th>No</th>
                                <th>의뢰 제목</th>
                                <th>의뢰처</th>
                                <th>요청마감기한</th>
                                <th>상태</th>
                                <th>등록일</th>
                                <th></th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" onclick="savePremiumRequest(); return false;">확인</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_close_contact">닫기</button>
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
@section("script")
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script src="/admin/bower_components/moment/moment.js"></script>
    <script src="/admin/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script type="text/javascript" src="/js/common.js" ></script>
    <script src="/dist/Common.js"></script>
    <script src="/admin/page/member/teacher/page.teacherMember.detail.init.js"></script>
    <script src="/admin/page/member/teacher/page.teacherMember.detail.func.js"></script>
    <script src="/admin/page/page.api.lecture.func.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script src="/admin/page/member/teacher/page.contactRequest.datatable.js"></script>
    <script type="text/javascript" src="/admin/page/member/teacher/page.teacher.class.datatable.js"></script>
    <script type="text/javascript" src="/admin/page/member/teacher/page.teacher.request.datatable.js"></script>
@stop