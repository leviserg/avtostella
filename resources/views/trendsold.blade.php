@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4" style="width:93%">
        <div class="row">
            <div class="col-md-9 mb-2" id="chartdiv" style="height:800px; width:100%"></div>
            <div class="col-md-3 pr-4">
                <div class="card" style="height:800px; background:#F7F7F7">
                    <div class="card card-header text-center text-info" style="font-size:20px">Выбор графиков</div>
                    <div class="card card-body">
                        <div class="form-group">
                            <label for="conv" class="control-label text-info">Выбрать конвеер </label>
                            <select class="form-control  mb-2" name="conv" id="conv">
                                <option value="31" selected="selected">Конвеер O-3-1</option>
                                <option value="32">Конвеер O-3-2</option>
                                <option value="41">Конвеер O-4-1</option>
                                <option value="42">Конвеер O-4-2</option>
                            </select>
                            <label for="section" class="control-label text-info">Выбрать секцию</label>
                            <select class="form-control  mb-2" name="section" id="section">
                                @foreach($sectionlist as $section)
                                    @if($section->section == $firstsection->section)
                                        <option value="{{$section->section}}" selected="selected" >Секция {{$section->section}}</option>
                                    @else
                                        <option value="{{$section->section}}">Секция {{$section->section}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <hr class="mt-4 mb-4"/>
                            <p for="sensorslist" class="control-label text-info">Выбрать датчик</p>
                            <div class="container ml-3" id="sensorslist">
                                @foreach($axeslist as $axe)
                                    <label class="checkcontainer">Ось {{$axe->axeid}}. Датчик {{$axe->sensorid}}
                                        <input type="radio" name="sensors" value="{{$axe->sensorid}}">
                                        <span class="checkmark"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <hr/>
                        <!--
                            <h5 class="text-info">Добавить кривую из другой оси</h5>
                            <div class="form-group mt-2">
                                <label for="convaux" class="control-label text-info">Выбрать конвеер </label>
                                <select class="form-control mb-2" name="convaux" id="convaux">
                                    <option value="0" selected="selected" >Выбрать конвеер...</option>
                                    <option value="31">Конвеер O-3-1</option>
                                    <option value="32">Конвеер O-3-2</option>
                                    <option value="41">Конвеер O-4-1</option>
                                    <option value="42">Конвеер O-4-2</option>
                                </select>
                                <label for="sectionaux" class="control-label text-info">Выбрать секцию</label>
                                <select class="form-control  mb-2" name="sectionaux" id="sectionaux">
                                    <option value="0" selected="selected" class="text-black-50">Выбрать секцию...</option>
                                </select>
                                <label for="axeaux" class="control-label text-info">Выбрать ось</label>
                                <select class="form-control  mb-2" name="axeaux" id="axeaux">
                                    <option value="0" selected="selected" class="text-black-50">Выбрать ось...</option>
                                </select>
                                <button id="addline" class="btn btn-info mt-3 py-2" style="width:100%">Добавить</button>
                            </div>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        $(document).ready(function(){
            TrendsFirstLoad();
        });

        function TrendsFirstLoad() {
            let points = 7;
            var data = [];
            var length = 1;
            for(var i=0; i < points; i++){
                data[i] = {
                    "date":""
                };
                data[i]["date"] = new Date( 2019, 9, (Number(i)/2) + 1, (i%2) * 12, 0, 0, 0);
                for(var j = 0; j < length; j++){
                    var newItem = "value" + (Number(j)+1);
                    var newValue = parseInt(Math.random() * 100);
                    data[i][newItem] = newValue ;
                }
            }
            var el = document.getElementById('chartdiv');
            createStockChart(el, data, length);
        }

        $("select[name='conv']").bind("change",function(){
            var url = "/trends/getsections/" + this.value;
            var sel = {conv:this.value};
            var action = function(data){
                data = JSON.parse(data);
                    $("select[name='section']").empty();
                    $("#sensorslist").empty();
                    for(var id in data[0]){
                        $("select[name='section']").append($("<option value='"+data[0][id].section+"'>" + "Секция " + data[0][id].section + "</option>"));
                    }
                    for(var id in data[1]){
                        var sAppend = "<label class='checkcontainer' onclick='getSensData("+data[1][id].sensorid+")'>Ось " + data[1][id].axeid + ". Датчик "+data[1][id].sensorid;
                        sAppend += "<input type='radio' name='sensors' class='sensclass' value='"+data[1][id].sensorid+"'>";
                        sAppend += "<span class='checkmark'></span></label>";
                       $(sAppend).appendTo('#sensorslist');
                    }
                };
            $.get(url,sel,action);
        });

        $("select[name='section']").bind("change",function(){
            var url = "/trends/getaxis/" + $("select[name='conv']").val() +"/"+ this.value;
            var sel = {conv:this.value};
            var action = function(data){
                data = JSON.parse(data);
                    $("#sensorslist").empty();
                    for(var id in data){
                        var sAppend = "<label class='checkcontainer' onclick='getSensData("+data[id].sensorid+")'>Ось " + data[id].axeid + ". Датчик "+data[id].sensorid;
                        sAppend += "<input type='radio' name='sensors' class='sensclass' value='"+data[id].sensorid+"'>";
                        sAppend += "<span class='checkmark'></span></label>";
                        $(sAppend).appendTo('#sensorslist');
                       //$("#sensorslist").append($(sAppend));
                    }
                };
            $.get(url,sel,action);
        });

        $("input[name='sensors']").bind("click",function(){
            getSensData(parseInt(this.value));
        });

/*
        $("select[name='convaux']").bind("change",function(){
            if(this.value !=0){
                var url = "/trends/getsections/" + this.value;
                var sel = {conv:this.value};
                var action = function(data){
                    data = JSON.parse(data);
                        $("select[name='axeaux']").empty();
                        $("select[name='axeaux']").append($("<option value='0' selected='selected'>Выбрать ось...</option>"));
                        $("select[name='sectionaux']").empty();
                        $("select[name='sectionaux']").append($("<option value='0' selected='selected'>Выбрать секцию...</option>"));
                        for(var id in data[0]){
                            $("select[name='sectionaux']").append($("<option value='"+data[0][id].section+"'>" + "Секция " + data[0][id].section + "</option>"));
                        }
                    };
                $.get(url,sel,action);
            }else{
                $("select[name='axeaux']").empty();
                $("select[name='axeaux']").append($("<option value='0' selected='selected'>Выбрать ось...</option>"));
                $("select[name='sectionaux']").empty();
                $("select[name='sectionaux']").append($("<option value='0'>Выбрать секцию...</option>"));
            }
        });

        $("select[name='sectionaux']").bind("change",function(){
            if(this.value !=0){
                var url = "/trends/getaxis/" + $("select[name='convaux']").val() +"/"+ this.value;
                var sel = {conv:this.value};
                var action = function(data){
                    data = JSON.parse(data);
                        $("select[name='axeaux']").empty();
                        $("select[name='axeaux']").append($("<option value='0' selected='selected'>Выбрать ось...</option>"));
                        for(var id in data){
                            $("select[name='axeaux']").append($("<option value='"+data[id].sensorid+"'>Ось "+data[id].axeid+". Датчик "+data[id].sensorid+"</option>"));
                        }
                    };
                $.get(url,sel,action);
            }else{
                $("select[name='axeaux']").empty();
                $("select[name='axeaux']").append($("<option value='0'>Выбрать ось...</option>"));
            }
        });

        $("#addline").bind("click",function(){
            let conv = $("select[name='convaux']").val();
            let section = $("select[name='sectionaux']").val();
            let sensor = $("select[name='axeaux']").val();
            let prod = parseInt(conv) * parseInt(section) * parseInt(sensor);
            if(prod != 0){
                swal("Успех", "Данные для графика: Конвеер - " + conv + ". Секция - " + section + ". Датчик - " + sensor, "success");
            }
            else{
                if(sensor == 0)
                    swal("Ось", "Не выбрана ось.", "warning");
                else if(section == 0)
                    swal("Секция", "Не выбрана секция.", "warning");
                else
                    swal("Конвеер", "Не выбран конвеер.", "warning");
            }
        });
*/


        function createStockChart(elem, data, datalen) {

            $(elem.id).empty();
            var chart = new AmCharts.AmStockChart();
           // var col = ["#1F77B4","#FF7F0E","#2CA02C","#D62728","#9467BD","#FF00DC","#FFD200","#9B3B00","#000000","#9B3B00"];//#17A2B8
            var col = ["#17A2B8","#FF7F0E","#2CA02C","#D62728","#9467BD","#FF00DC","#FFD200","#9B3B00","#000000","#9B3B00"];
            var lineType = ["line", "smoothedLine", "column", "step", "candlestick", "ohlc"];
            var chartData = data;
            var categoryAxesSettings = new AmCharts.CategoryAxesSettings();
            categoryAxesSettings.minPeriod = "ss";
            chart.categoryAxesSettings = categoryAxesSettings;
            var valueAxesSettings = new AmCharts.ValueAxesSettings();
            valueAxesSettings.minimum = 0;
            valueAxesSettings.maximum = 150;
            chart.valueAxesSettings = valueAxesSettings;

            var dataSet = new AmCharts.DataSet();
            dataSet.dataProvider = chartData;
            dataSet.categoryField = "date";
            dataSet.fieldMappings = [
                { fromField: "value1", toField: "value1" },
                { fromField: "value2", toField: "value2" },
                { fromField: "value3", toField: "value3" },
                { fromField: "value4", toField: "value4" },
                { fromField: "value5", toField: "value5" },
                { fromField: "value6", toField: "value6" },
                { fromField: "value7", toField: "value7" },
                { fromField: "value8", toField: "value8" }
            ];

            chart.dataSets = [dataSet];

            var stockPanel1 = new AmCharts.StockPanel();
                stockPanel1.showCategoryAxis = true;
                stockPanel1.title = "Уровень, %";
                stockPanel1.percentHeight = 70;

            var graph = [];

            for(var i=1; i <=datalen; i++){
                graph[i] = new AmCharts.StockGraph();
                graph[i].title = "Датчик " + parseInt(i);
                graph[i].balloonText = "[[title]]:[[value]]%";
                graph[i].useDataSetColors = false;
                graph[i].lineColor = col[i-1];
                graph[i].valueField = "value" + parseInt(i);
                graph[i].type = lineType[1];
                graph[i].lineThickness = 3;
                graph[i].bullet = "round";
                graph[i].bulletSize = 6;
                graph[i].bulletBorderColor = "white";
                graph[i].bulletBorderAlpha = 1;
                graph[i].bulletBorderThickness = 2;
                graph[i].id = parseInt(i);
                stockPanel1.addStockGraph(graph[i]);
            }

            var stockLegend1 = new AmCharts.StockLegend();
                stockLegend1.valueTextRegular = " ";
                stockPanel1.stockLegend = stockLegend1;

                chart.panels = [stockPanel1];

            var scrollbarSettings = new AmCharts.ChartScrollbarSettings();
                scrollbarSettings.graph = graph[1];
                scrollbarSettings.usePeriod = "10mm";
                scrollbarSettings.updateOnReleaseOnly = false;
                scrollbarSettings.position = "bottom";
                chart.chartScrollbarSettings = scrollbarSettings;

            var cursorSettings = new AmCharts.ChartCursorSettings();
                cursorSettings.showNextAvailable = true;
                cursorSettings.cursorColor = "#17A2B8";
                cursorSettings.valueLineEnabled = false; // true
                cursorSettings.valueLineAlpha = 0.5;
                cursorSettings.categoryBalloonDateFormats = [
                    {period:"YYYY", format:"YYYY"},
                    {period:"MM", format:"MMM, YYYY"},
                    {period:"WW", format:"DD MMM YYYY"},
                    {period:"DD", format:"DD MMM YYYY"},
                    {period:"hh", format:"DD MMM, JJ:NN:SS"},
                    {period:"mm", format:"DD MMM, JJ:NN:SS"},
                    {period:"ss", format:"DD MMM, JJ:NN:SS"},
                    {period:"fff", format:"JJ:NN:SS"}]; // "fff"-milliseconds
                cursorSettings.valueBalloonsEnabled = true;
                cursorSettings.fullWidth = true;
                cursorSettings.cursorAlpha = 0.1;

                chart.chartCursorSettings = cursorSettings;

            var periodSelector = new AmCharts.PeriodSelector();
                periodSelector.position = "bottom";
                periodSelector.dateFormat = "YYYY-MM-DD JJ:NN:SS";
                periodSelector.inputFieldWidth = 180;
                periodSelector.periods = [
                    { period: "DD", count: 1, label: "1 день" },
                    { period: "DD", selected: true, count: 3, label: "3 дня" },
                    { period: "DD", count: 5, label: "5 дней" },
                    { period: "MM", count: 1, label: "1 месяц" },
                    { period: "YYYY", count: 1, label: "1 год" },
                    { period: "MAX", label: "Все" }
                ];

                chart.periodSelector = periodSelector;

            var panelsSettings = new AmCharts.PanelsSettings();
                panelsSettings.mouseWheelZoomEnabled = true;
                panelsSettings.usePrefixes = true;

            chart.panelsSettings = panelsSettings;
            chart.write(elem.id);
            chart.validateNow();
            $(elem.id).fadeIn(1000);
        }

        function getSensData(sensId){
            console.log(sensId);
        }

    </script>
@endpush
