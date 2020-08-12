<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ROF-1') }}</title>

        <!-- Fonts -->
    <!--
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    -->

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
        <link href="{{ asset('css/sort.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
        <link href="{{ asset('css/favicon.ico') }}" rel="shortcut icon">
        <style>
            html,body {
                background-color: #a5a5a5;
            }
        </style>
        <!-- Scripts -->

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md bg-info navbar-laravel navbar-dark">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a class="navbar-brand" href="{{ url('/home/56') }}" data-toggle="tooltip" title="На главную"><i class="fa fa-home mx-2"></i>РОФ-2</a>
                    @else
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link mx-2 px-2" href="{{ route('login') }}" data-toggle="tooltip" title="Вход в систему"><i class="fa fa-sign-in mx-2"></i></i>Войти</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link mx-2 px-2" href="{{ route('register') }}" data-toggle="tooltip" title="Желаете зарегистрироваться?"><i class="fa fa-file-text-o mx-2"></i>Регистрация</a>
                                </li>
                            @endif
                        </ul>
                    @endauth
                </div>
            @endif
        </nav>
        <main class="py-2">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/html5shiv.js') }}"></script>
    <script src="{{ asset('js/response.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>
