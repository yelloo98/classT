@extends('admin.layouts.app')

@section('style')
    <link href="/css/Nwagon.css" rel="stylesheet">
    <link href="/admin/dist/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="/css/style_web.css" rel="stylesheet" type="text/css" />
@endsection
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
                                    <col width="15%"/>
                                    <col width="85%"/>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>강의명</th>
                                        <td>{{$info->class_title}}</td>
                                    </tr>
                                    <tr>
                                        <th>기업</th>
                                        <td>{{$info->company_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>강사</th>
                                        <td>{{$info->teacher_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>수강인원</th>
                                        <td>{{$info->etc_number}}명</td>
                                    </tr>
                                    <tr>
                                        <th>만족도</th>
                                        <td>{{$info->satisfaction}} / 5</td>
                                    </tr>
                                    <tr>
                                        <th>설문평점</th>
                                        <td>{{$info->rating}} / 5</td>
                                    </tr>
                                    <tr>
                                        <th>강의일자</th>
                                        <td>{{$info->class_start_dt}}</td>
                                    </tr>
                                    <tr>
                                        <th>내용</th>
                                        <td>
                                            <div class="graph_info">
                                                <!-- 20180702 추가수정 S :: div,dl 추가 -->
                                                <div class="score_wrap">
                                                    <dl class="sat_score">
                                                        <dt>강의만족도</dt>
                                                        <dd>{{$info->satisfaction}}</dd>
                                                    </dl>
                                                </div>

                                                <div class="graph" style="vertical-align:middle; width:33%;">
                                                    <div id="radarChart" style="margin-left: -100px;"></div>
                                                </div>
                                                <ul>
                                                    @foreach($score as $item)
                                                        <li >{{$item['question']}}: <b>{{$item['score_avg']}}점</b></li>
                                                    @endforeach
{{--                                                    <li ><i class="fa fa-laptop"></i> 강의전문성: <b>{{$info->q2}}점</b></li>
                                                    <li><i class="fa fa-edit"></i> 추천의향: <b>{{$info->q3}}점</b></li>
                                                    <li><i class="fa fa-meh-o"></i> 실무적용: <b>{{$info->q4}}점</b></li>
                                                    <li><i class="fa fa-keyboard-o"></i> 수강생호응도: <b>{{$info->q5}}점</b></li>
                                                    <li><i class="fa fa-heart-o"></i> 교안준비성: <b>{{$info->q6}}점</b></li>--}}
                                                </ul>
                                            </div>
                                            <div style="text-align: center;">
                                                <div id="genderChart" style="display:inline-block;"></div>
                                                <div id="ageChart" style="display:inline-block; margin-left:5%; margin-right:5%;"></div>
                                                <div id="posChart" style="display:inline-block;"></div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div style="margin-top:-50px;">
                    <ul class="pager wizard no-style">
                        <li class="pull-right">
                            <button type="button" class="btn btn-primary btn-block m-t-5 m-r-5 pull-right p-l-30 p-r-30" onclick="location.href='/admin/review/student'">목록</button>
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
    <script src="/js/Nwagon.js"></script>
    <script>
        var options = {
            'legend':{
                names: [
                @foreach($score as $item)
                        '{{$item['question']}}',
                @endforeach
{{--                    '강의 전문성',
                    '추천의향',
                    '실무적용',
                    '수강생호응도',
                    '교안준비성'--}}
                ]
            },
            'dataset': {
                values: [[
                    @foreach($score as $item)
                    {{ ((int)$item['score_avg']) * 20 }},
                    @endforeach
                            ]],
                bgColor: '#f9f9f9',
                fgColor: '#ce7cac'
            },
            'chartDiv': 'radarChart',
            'chartType': 'radar',
            'chartSize': { width: 500, height: 300 }
        };
        Nwagon.chart(options);

        //# 성별 차트
        var genderOptions = {
            'legend': {
                names: ['남자','여자'],
            },
            'dataset': {
                title: '성별',
                values: [{{$info->gender_m}},{{$info->gender_g}}],
                colorset: ['#CC0000','#0066CC']
            },
            'chartDiv': 'genderChart',
            'chartType': 'column',
            'chartSize': { width: 250, height: 300 },
//            'maxValue': 10,
            'increment': 5
        };
        Nwagon.chart(genderOptions);

        //# 나이 차트
        var ageOptions = {
            'legend': {
                names: ['20대','30대', '40대', '50대', '60대'],
            },
            'dataset': {
                title: '나이',
                values: [{{$info->age_2}},{{$info->age_3}},{{$info->age_4}},{{$info->age_5}},{{$info->age_6}}],
                colorset: ['#CC0000','#0066CC', '#ce7cac']
            },
            'chartDiv': 'ageChart',
            'chartType': 'column',
            'chartSize': { width: 250, height: 300 },
//            'maxValue': 10,
            'increment': 5
        };
        Nwagon.chart(ageOptions);

        //# 직급 차트
        var posOptions = {
            'legend': {
                names: ['사원','대리', '과장', '차장', '부장'],
            },
            'dataset': {
                title: '직급',
                values: [{{$info->pos_1}},{{$info->pos_2}},{{$info->pos_3}},{{$info->pos_4}},{{$info->pos_5}}],
                colorset: ['#DC143C', '#FF8C00', "#30a1ce"]
            },
            'chartDiv': 'posChart',
            'chartType': 'column',
            'chartSize': { width: 250, height: 300 },
//            'maxValue': 10,
            'increment': 5
        };
        Nwagon.chart(posOptions);

        var chart = document.getElementById("genderChart");
        var foreground = chart.getElementsByClassName("foreground")
        var circle1 = foreground[0].getElementsByTagName("circle");  // 첫번째 데이타 포인트 배열

        //TODO 차트 막대별로 색상 바꾸기
    </script>
@stop