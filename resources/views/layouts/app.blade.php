@if(!Request::is('login')&&!Request::is('cookie-policy')&&!Request::is('password/*')&&!Request::is('/'))
    <?php if(@!session('season')->name){ dd(1); Auth::logout(); return route('login'); } ?>
@endif
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel='manifest' href='{{asset('manifest.webmanifest')}}'>
    <meta name="theme-color" content="#34495E"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/fa.min.css')}}">
    <link rel="icon" type="image/png" sizes="512x512"  href="{{asset('img/icon.png')}}">
    <link rel="apple-touch-icon" href="{{asset('img/icon.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @stack('css-includes')

    <title>{{ config('app.name') }}</title>
</head>
<body>
<div id="load">
    <img class="pt-5 pb-5" src="{{asset('img/loader.gif')}}">
</div>
@include('inc.footer')
<div class="fixed-top">
    @can('NAV-view-admin')
        @include('inc.admin-nav')
    @endcan
    @include('inc.nav')
</div>
<div id="bg-icon">
    @guest <img src="{{asset('img/logo.png')}}" style="opacity:0.2"> @elseif(@session('centre')->img) <img src="/img/centres/{{session('centre')->img}}" class="centre_img"> @endguest
</div>
<div id="app">

    @include('inc.calendar')
    @can('NAV-view-admin')
        <div class="d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block" style="height:200px;"></div>
        <div class="d-xs-block d-sm-block d-md-none d-md-none d-lg-none d-xl-none" style="height:130px;"></div>
        @else
        <div class="d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block" style="height:140px;"></div>
        <div class="d-xs-block d-sm-block d-md-none d-md-none d-lg-none d-xl-none" style="height:70px;"></div>
    @endcan
    <div class="container">
        @include('inc.messages')
    </div>
    @yield('content')
</div>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/custom/jquery.ihavecookies.min.js')}}"></script>
@stack('js-includes')
@stack('scripting')
@if(!empty(session('api_token'))) <script>window.localStorage.setItem('api_token', '{{session('api_token')}}') </script> @endif
<br><br><br><br><br>
</body>
</html>

