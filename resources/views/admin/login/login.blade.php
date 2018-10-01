<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ClassEum | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/admin/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>Class</b>Eum</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">관리자 로그인</p>

        <form method="post" action="" id="admin_login">
            @csrf
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="email" value="{{Cookie::get("email")}}" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" value="{{Cookie::get("password")}}" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div>
                <label><input type="checkbox" name="remember" value="{{ old('remember', 1) }}" @if(Cookie::get("remember")) checked @endif> 아이디 저장</label>
                <label><input type="checkbox" name="auto_login" value="{{ old('auto_login', 1) }}" @if(Cookie::get("auto_login")) checked @endif> 자동 로그인</label>
            </div>
            <div class="row">
                <div class="col-xs-8">
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <input type="submit" class="btn btn-primary btn-block btn-flat" value="로그인"/>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/admin/plugins/iCheck/icheck.min.js"></script>

<!-- jQuery 2.2.3 -->
<script type="text/javascript" src="/plugins/jquery/jquery-2.2.3.min.js"></script>
<!-- jQueryUI 1.11.4 -->
<script type="text/javascript" src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Cookie 1.5.1 -->
<script type="text/javascript" src="/plugins/cookie/jquery.cookie.js"></script>


<!-- Plugin : Validation -->
<script type="text/javascript" src="/plugins/validation/jquery.validate.js"></script>
<script type="text/javascript" src="/js/validation.js"></script>
<!-- Plugin : InputMark -->
<script type="text/javascript" src="/plugins/input-mask/jquery.mask.js"></script>
<script type="text/javascript" src="/js/inputmark.js"></script>


{{-- page init --}}
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/common.ui.js"></script>

<script type="text/javascript" src="/admin/page/login/page.login.func.js"></script>
<script type="text/javascript" src="/admin/page/login/page.login.form.js"></script>
<script type="text/javascript" src="/admin/page/login/page.login.init.js"></script>


<style>
    /*####################################################################################################################*/
    /*##
    /*## >> Toast Message Box
    /*##
    /*####################################################################################################################*/
    #snackbar {
        visibility: hidden;
        min-width: 250px;
        margin-left: -230px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        left: 50%;
        bottom: 30px;
        font-size: 17px;
    }

    #snackbar.show {
        visibility: visible;
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }
</style>
{{-- Toast Message--}}
<div id="snackbar"></div>

@if(Session::has('flash_error'))
    <script>
        console.log('{{ Session::get('flash_error') }}')
        Toast('{{ Session::get('flash_error') }}')
    </script>
@endif


</body>
</html>
