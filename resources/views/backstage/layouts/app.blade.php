<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <link rel="shortcut icon" href="{{ asset('/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="https://at.alicdn.com/t/font_748879_24h86wxb86n.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Sebastian Kennedy">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '首页') - 后台管理系统</title>
    @yield('before_app_css')
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
    @yield('after_app_css')
</head>

<body>
<div id="app" class="wrapper">
    @include('backstage.layouts._left_sidebar')
    <div class="content-page">
        <div class="content">
            @include('backstage.layouts._header')
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        @include('backstage.layouts._footer')
    </div>
</div>

@yield('before_app.js')
<script src="{{ asset('js/app.min.js') }}"></script>
<script src="{{ asset('js/vendor/sweetalert2.min.js') }}"></script>
@yield('after_app_js')
</body>
</html>