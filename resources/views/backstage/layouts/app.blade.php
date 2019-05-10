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
<script>
    @if(session()->has('success'))
    swal({
        title: '操作成功',
        text: '{{session()->get('success')}}',
        type: 'success',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    @if(session()->has('errors'))
    swal({
        title: '操作失败',
        text: '{{implode('  ', $errors->all())}}',
        type: 'error',
        showConfirmButton: false
    });
    @endif

    function alertError(message){
        swal({
            title: '操作失败',
            text: message,
            type: 'error',
            showConfirmButton: false
        });
    }

    function confirmDelete() {
        swal({title: '是否确定删除？', showCancelButton: true}).then((res) => {if (res.value) $(this).parent().submit()});
    }

    let loadingElement = '<div class="card-disabled"><div class="card-portlets-loader"></div></div>';
    $('ul.pagination li.page-item').click(function () {
        if ($(this).hasClass('active') || $(this).hasClass('disabled')) {
            return;
        }

        let cardElement = $(this).parents('.card');
        cardElement.append(loadingElement);
    });

    function loading(mount) {
        mount.parents('.card').append(loadingElement);
    }

    function globalLoading() {
        $('#app').append(loadingElement);
    }

    function cleanLoading() {
        $('.card-disabled').remove();
    }
</script>
@yield('after_app_js')
</body>
</html>