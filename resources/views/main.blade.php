@extends('layouts.app')
@section('content')
    <!-- <div class="colorPalette"></div> -->
    <div class="container-fluid" style="width:96%">
        <div id="siteheader" style="display:none"></div>
        <div class="row mt-2 mb-0">
            <div class="col-md-9 text-center">
                <h5><b><span id="titletext">Текущий уровень </span>руды в бункерах РОФ-2<span id="seldate" class="ml-4"></span></b></h5>
            </div>            
            <div class="col-md-3">
                <img class="ml-4" src="{{ asset('css/timescale.png') }}">
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-8 my-0 py-0 px-1">
                <div class="row my-0 py-0">
                    <div class="col-md-2">
                        <div class="row "><span id="convflow_0" class="text-center align-top"></span></div>
                        <div class="row text-center align-top"><img id="conv_0" src="{{ asset('css/conv_off.png') }}"/></div>
                    </div>
                    <div class="col-md-9 text-center">
                        <h5 id="chart_0_title"></h5>
                    </div>
                </div>
                <div class="row mt-0 mb-0">                   
                    <div id="pos_0" class="stellapos" data-toggle="tooltip" title="">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-7">
                                    <img id="stella_0" src="{{ asset('css/avt_off.png') }}"/>
                                </div>
                                <div class="col-sm-5 align-bottom">
                                    <small>
                                        <span id="pos_0_txt" class="lines mt-3 pt-2"></span>
                                        <span id="lev_0_txt" class="lines pt-1 align-bottom"></span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12 text-center chartdiv t1" id="chart_0"></div></div>
                <div class="row"><div class="col-md-12 ml-4 text-center table-responsive" id="chart_0_table"></div></div>
            </div>
            <div class="col-lg-4 my-0 py-0 px-4">
                <div class="row">
                    <div class="col-md-2">
                        <div class="row "><span id="convflow_2" class="text-center align-top"></span></div>
                        <div class="row text-center align-top"><img id="conv_2" src="{{ asset('css/conv_off.png') }}"/></div>
                    </div>
                    <div class="col-md-9 text-center">
                        <h5 id="chart_2_title"></h5>
                    </div>
                </div>
                <div class="row mt-0 mb-0">
                    <div class="col-md-12 mr-4">
                        <div id="pos_2" class="stellapos" data-toggle="tooltip" title="">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <img id="stella_2" src="{{ asset('css/avt_off.png') }}"/>
                                    </div>
                                    <div class="col-sm-5 align-bottom">
                                        <small>
                                            <span id="pos_2_txt" class="lines mt-3 pt-2"></span>
                                            <span id="lev_2_txt" class="lines pt-1 align-bottom"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12 text-center chartdiv t0" id="chart_2"></div></div>
                <div class="row"><div class="col-md-12 ml-4 text-center table-responsive" id="chart_2_table"></div></div>
            </div>
        </div>

        <div class="row mt-0">
            <div class="col-lg-8 my-0 py-0 px-1">
                <div class="row my-0">
                    <div class="col-md-2 my-0">
                        <div class="row my-0"><span id="convflow_1" class="text-center align-top"></span></div>
                        <div class="row text-center align-top"><img id="conv_1" src="{{ asset('css/conv_off.png') }}"/></div>
                    </div> 
                    <div class="col-md-9 text-center">
                        <h5 id="chart_1_title"></h5>
                    </div>
                </div>
                <div class="row mt-0 mb-0">                  
                    <div class="col-md-12">
                        <div id="pos_1" class="stellapos" data-toggle="tooltip" title="">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <img id="stella_1" src="{{ asset('css/avt_off.png') }}"/>
                                    </div>
                                    <div class="col-sm-5 align-bottom">
                                        <small>
                                            <span id="pos_1_txt" class="lines mt-3 pt-2"></span>
                                            <span id="lev_1_txt" class="lines pt-1 align-bottom"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12 pr-3 text-center chartdiv t1" id="chart_1"></div></div>
                <div class="row"><div class="col-md-12 ml-4 text-center table-responsive" id="chart_1_table"></div></div>
            </div>
            <div class="col-lg-4 my-0 py-0 px-4">
                <div class="row">
                    <div class="col-md-2">
                        <div class="row "><span id="convflow_3" class="text-center align-top"></span></div>
                        <div class="row text-center align-top"><img id="conv_3" src="{{ asset('css/conv_off.png') }}"/></div>
                    </div> 
                    <div class="col-md-9 text-center">
                        <h5 id="chart_3_title"></h5>
                    </div>
                </div>
                <div class="row mt-0 mb-0">
                    <div class="col-md-12 mr-4">
                        <div id="pos_3" class="stellapos" data-toggle="tooltip" title="">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <img id="stella_3" src="{{ asset('css/avt_off.png') }}"/>
                                    </div>
                                    <div class="col-sm-5 text-right align-bottom">
                                        <small>
                                            <span id="pos_3_txt" class="lines mt-3 pt-2"></span>
                                            <span id="lev_3_txt" class="lines pt-1 align-bottom"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12 text-center chartdiv t0" id="chart_3"></div></div>
                <div class="row"><div class="col-md-12 ml-4 text-center table-responsive" id="chart_3_table"></div></div>
            </div>
        </div>
        <!-- <div id="pagedata"></div> -->
        <div style="display:none">
            <span style="display:none" id="sections">{{$axesdata['sections']}}</span>
            <span style="display:none" id="axes">{{$axesdata['axes']}}</span>
            <span style="display:none" id="sensors">{{$axesdata['sensors']}}</span>
            <span style="display:none" id="conveyors_id">@foreach($axesdata['conveyors'] as $conveyor){{$conveyor->conveyor}},@endforeach</span>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('js/mainscripts.js') }}"></script>
@endpush
