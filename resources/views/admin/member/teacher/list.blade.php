@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
    <link rel="stylesheet" type="text/css" href="/plugins/datatables/datatables.css">
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- E: 검색 -->
                <div class="box">
                    <div class="box-header">
                        <form id="form" action="/admin/member/teacher">
                            <div class="row">
                                <div class="col-xs-1" style="margin-top:10px;"><span style="margin-left:20%;">검색</span></div>
                                <div class="col-xs-2">
                                    <select name="searchType" class="form-control select2" style="width: 100%;">
                                        @foreach(array('' => '전체', 'email' => '아이디', 'name' => '이름') as $key => $value)
                                            <option value="{{$key}}" @if(app('request')->input('searchType') == $key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" name="searchWord" value="{{app('request')->input('searchWord')}}">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn pull-left" id="btn_search">검색</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-xs-1"><span style="margin-left:20%;">상태</span></div>
                                <div class="col-xs-8">
                                    <input type="radio" class="minimal" name="searchStatus" value="" checked/><label>전체</label>
                                    <input type="radio" class="minimal" name="searchStatus" value="3"/><label>가입완료</label>
                                    <input type="radio" class="minimal" name="searchStatus" value="1"/><label>가입대기</label>
                                    <input type="radio" class="minimal" name="searchStatus" value="2"/><label>가입보류</label>
                                    <input type="radio" class="minimal" name="searchStatus" value="4"/><label>비활성화</label>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-6" style="margin-top:3%;">
                                <p style="margin-left:2%;">클래스이음의 강사회원들의 정보를 열람할 수 있습니다.</p>
                            </div>
                            <div class="col-sm-6">
                                <table id="scoreTable" class="table table-bordered table-hover" style="float:right; width:100%; margin : 15px;">
                                    <thead>
                                    <tr>
                                        <th>전체</th>
                                        <th>가입완료</th>
                                        <th>가입대기</th>
                                        <th>가입보류</th>
                                        <th>비활성화</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <h3 class="box-title totalCnt"></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="table_list" class="table table-bordered table-hover" style="width:100%;">
                            <colgroup>
                                <col width="5%"/>
                                <col width="7%"/>
                                <col width="13%"/>
                                <col width="10%"/>
                                <col width="20%"/>
                                <col width="15%"/>
                                <col width="10%"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>회원등급</th>
                                <th>이메일아이디</th>
                                <th>이름</th>
                                <th>연락처</th>
                                <th>추천강사 등록여부</th>
                                <th>회원상태</th>
                                <th>가입일자</th>
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
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section("script")
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.js" charset="utf8" ></script>
    <script type="text/javascript" src="/admin/page/member/teacher/page.teacherMember.datatable.js"></script>
    <script>
        $(document).ready(function() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });
        });
    </script>
@stop