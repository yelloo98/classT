<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="_csrf" content="{{ csrf_token() }}">

    <title>Class Eum</title>


    <link rel="stylesheet" href="/admin/bower_components/bootstrap/dist/css/bootstrap.min.css"> <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/admin/bower_components/font-awesome/css/font-awesome.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin/bower_components/Ionicons/css/ionicons.min.css"> <!-- Ionicons -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css"> <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">     <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/css/common.css">

    @yield('stylesheet'){{-- CSS : 페이지별 스타일 시트 --}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    @yield('style')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    @yield('prepend')
        @include('admin.layouts.top')
        @include('admin.layouts.side')
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    {{$navMenu}}
                    <small>{{$navSubMenu}}</small>
                </h1>
            </section>
            @yield('content'){{-- 컨텐츠 --}}
        </div>
        @include('admin.layouts.footer')

    @yield('append')

    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <script type="text/javascript" src="/plugins/jquery/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="/admin/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/admin/dist/js/adminlte.min.js"></script>

    <!-- common Function-->
    <script type="text/javascript" src="/js/common.js"></script>

    @include('admin.layouts.error')
    @yield('plugins'){{-- Script : 플러그인 스크립트 --}}
    @yield('script'){{-- Script : 페이지별 스크립트 --}}
</div>
</body>
</html>

