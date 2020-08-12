<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="no-cache">
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Загрузка бункеров РОФ-2</title>

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
    <link href="{{ asset('amcharts/style.css') }}" rel="stylesheet">
    <link href="{{ asset('amcharts/export.css') }}" rel="stylesheet">
    <link href="{{ asset('css/appstyles.css') }}" rel="stylesheet">

    <!-- Scripts -->

    <script src="{{ asset('js/html5shiv.js') }}"></script>
    <script src="{{ asset('js/response.js') }}"></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md bg-info navbar-laravel navbar-dark">
            <a class="navbar-brand" href="{{ url('/home/56') }}" data-toggle="tooltip" title="О5-6"><i class="fa fa-home mx-2"></i>РОФ-2</a>
            <button class="navbar-toggler navbar-toggler-right" type="button"
                data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarResponsive">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link mx-2 px-2" href="{{ url('/trends') }}" data-toggle="tooltip" title="Тренды"><i class="fa fa-bar-chart mx-2"></i>Тренды</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 px-2" href="{{ url('/alarms') }}" data-toggle="tooltip" title="Аварии"><i class="fa fa-bell mx-2"></i>Аварии</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 px-2" href="{{ url('/events') }}" data-toggle="tooltip" title="События"><i class="fa fa-list mx-2"></i>События</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-5 ml-auto" id="maintab">
                    <li class="nav-item mr-5 ml-auto">
                        <button class="btn btn-info mx-2 disabled">Отображать данные</button>
                        <div class="btn-group">
                            <button class="btn btn-info" id='hist' data-toggle="tooltip" title="За указанную дату и время">Предыдущие</button>
                            <button class="btn btn-info active" id='gotolast56' data-toggle="tooltip" title="Текущие он-лайн">Текущие</button>
                            <button class="btn btn-info" id='mills56' data-toggle="tooltip" title="Прогноз по устаревшим данным">Прогноз</button>
                            <span id="curconvid" style="display:none">56</span>
                        </div>
                    <li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <span class="nav-link" id="curtime"></span>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="py-2 px-2 mx-2">
            @yield('content')
        </main>
        <div class="bg-info almstrip">
            <p>Неквитированных 
                <a class="text-white" href="{{ url('/alarms') }}">
                    <b><span id="unacksum">...</span></b>
                </a> 
            </p>
        </div>        
        <a href="http://kck.ua" data-toggle="tooltip" title="CSC Automation Home Page" class="logo"></a>
        <a href="#" class="scrollup"></a>
    </div>

    <div class="mt-5 modal fade modal-fade" id="showhist" tabindex="2">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header mb-0 mt-0">
                    <h5 class="modal-title">Дата и время</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label for="dpicker" class="small">Выберите дату:</label>
                            <input class="form-control mx-2 text-center" type="text" id="dpicker" placeholder="гггг-мм-дд" maxlength="10"/>
                        </div>
                        <div class="form-group mt-1">
                            <label for="tpicker" class="small">Укажите время:</label>
                            <input class="form-control mx-2 text-center" type="text" id="tpicker" placeholder="чч:мм:сс" maxlength="8"/>
                        </div>
                        <hr/>
                        <input class="btn btn-info mt-1 mb-2 ml-2" id="goto56" style="width:45%" type="button" value="Перейти">
                        <button class="btn btn-outline-info mt-1 mb-2 ml-2" style="width:45%" type="button" data-dismiss="modal" aria-label="Close">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2 modal fade modal-fade" id="mills" tabindex="2">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header mb-0 mt-0">
                    <h5 class="modal-title">Текущий расход руды по мельницам</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 ml-4 text-center table-responsive" id="weight_table"></div>
                        </div>
                        <hr/>
                        <input class="btn btn-info mt-1 mb-2 ml-2" id="forecast56" style="width:48%" type="button" value="Дальше">
                        <button class="btn btn-outline-info mt-1 mb-2 ml-2" style="width:48%" type="button" data-dismiss="modal" aria-label="Close">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/jquery.xdomainrequest.min.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script> 
    <script src="{{ asset('js/jqueryDataTables.js') }}" defer ></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('amcharts/serial.js') }}"></script>
    <script src="{{ asset('amcharts/amstock.js') }}"></script>
    <script src="{{ asset('amcharts/export.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/jlinq.js') }}"></script>
    <script src="{{ asset('js/appscripts.js') }}"></script>
    @stack('scripts')
</body>
</html>
