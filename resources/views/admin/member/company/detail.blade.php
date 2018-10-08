@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{--{{$title}}--}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" id="form" action="/admin/member/company/save" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info->id}}"/>
                            <div class="col-md-12 m-b-30">
                                <table class="table table-bordered tableDetail" style="float:right; width:100%; margin : 15px;">
                                    <colgroup>
                                        <col width="17%"/>
                                        <col width="83%"/>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>이메일 아이디</th>
                                            <td>{{$info->email}}</td>
                                        </tr>
                                        <tr>
                                            <th>이름</th>
                                            <td><input type="text" name="name" class="form-control" value="{{$info->name or old('name')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>연락처</th>
                                            <td><input type="text" name="phone" class="form-control" value="{{$info->phone or old('phone')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>생년월일</th>
                                            <td><input type="text" name="birth" class="form-control" value="{{$info->birth or old('birth')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>이메일수신동의</th>
                                            <td>
                                                <select name="send_email_agree" class="form-control select2" style="width:20%;">
                                                    <option value="Y" @if($info->send_email_agree == 'Y' || old('send_email_agree') == 'Y') selected @endif>Y</option>
                                                    <option value="N" @if($info->send_email_agree == 'N' || old('send_email_agree') == 'N') selected @endif>N</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>SMS수신동의</th>
                                            <td>
                                                <select name="send_sms_agree" class="form-control select2" style="width:20%;">
                                                    <option value="Y" @if($info->send_sms_agree == 'Y' || old('send_sms_agree') == 'Y') selected @endif>Y</option>
                                                    <option value="N" @if($info->send_sms_agree == 'N' || old('send_sms_agree') == 'N') selected @endif>N</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>회사명</th>
                                            <td><input type="text" class="form-control" name = "company_name" value="{{$info->company_name or old('company_name')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>사업자등록번호</th>
                                            <td><input type="text" class="form-control" name="company_number" value="{{$info->company_number or old('company_number')}}"></td>
                                        </tr>
                                        <tr>
                                            <th>사업자등록증</th>
                                            <td>
                                                @if( $info->business_license != "" )
                                                    <button class="btn" onclick="fileDownload({{$info->id}}, '{{$info->business_license}}', 'COMPANY_LICENSE'); return false;" style="margin-top:2px;">사업자 등록증 다운로드</button>
                                                @else
                                                    등록된 사업자등록증이 없습니다.
                                                    <input type="file" name="business_license"/>
                                                @endif
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
                                                        <option value="{{$key}}" @if($key == $info->status) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
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
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>

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
                        <table id="request_list" class="table table-bordered table-hover" style="width:100%;">
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
                            <button type="button" class="btn" onclick="location.href='/admin/member/company';">목록</button>
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
    <script src="/dist/Common.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/member/company/page.company.class.datatable.js"></script>
    <script type="text/javascript" src="/admin/page/member/company/page.company.request.datatable.js"></script>
    <script>
        $(document).ready(function() {
            $("#submit_btn").click(function() {
                $("#form").submit();
            });
        });
    </script>
@stop
