@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/admin/bower_components/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="/admin/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
    <style>
        .box {
            border-top : white;
        }
        td{
            text-align: center;
        }

        td > label {
            font-size : 1.2em;
        }

        td > p {
            color : #3c8dbc;
            font-size : 2em;
        }
    </style>
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="col-md-12 m-b-30">
                            <table class="table table-bordered tableDetail" style="float:right; width:100%; text-align: center;">
                                <colgroup>
                                    <col width="25%"/>
                                    <col width="25%"/>
                                    <col width="25%"/>
                                    <col width="24%"/>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>
                                        <p>{{number_format($tbData['data_1_1'])}}</p>
                                        <label>강의의뢰건</label>
                                    </td>
                                    <td>
                                        <p>{{number_format($tbData['data_1_2'])}}</p>
                                        <label>제안서 미요청건</label>
                                    </td>
                                    <td>
                                        <p>{{number_format($tbData['data_1_3'])}}</p>
                                        <label>제안서 미수신건</label>
                                    </td>
                                    <td>
                                        <p>{{number_format($tbData['data_1_4'])}}</p>
                                        <label>제안서 미발송건</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>{{number_format($tbData['data_2_1'])}}</p>
                                        <label>출강 미요청건</label>
                                    </td>
                                    <td>
                                        <p>{{number_format($tbData['data_2_2'])}}</p>
                                        <label>출강 미 확정건</label>
                                    </td>
                                    <td>
                                        <p>{{number_format($tbData['data_2_3'])}}</p>
                                        <label>정산대기건</label>
                                    </td>
                                    <td>
                                        <p>{{number_format($tbData['data_2_4'])}}</p>
                                        <label>세금계산서 미발행건</label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="calendar"></div>
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
    <script src="/admin/bower_components/moment/moment.js"></script>
    <script src="/admin/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="/dist/Common.js"></script>
    <script>
        $(document).ready(function() {
            $(".datepicker-inline").remove();

            var date = new Date();
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear();
            console.log(new Date(y, m, 18));
            console.log(new Date('2018-08-13 00:00:00'));

            //# 켈린더 init
            CommCalendar.init("#calendar", null);

            //# 스케쥴 데이터 조회
            $.ajax({
                url: '/api/schedule/0',
                type: 'GET',
                success: function(result){
                    console.log(result);
                    CommCalendar.setData("#calendar", result, false);
                }
            });

        });
    </script>
@stop
