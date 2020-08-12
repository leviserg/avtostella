    $(document).ready(function(){
        $("input[name=sensors][value=" + 1 + "]").prop('checked', true);
        getSensData($("select[name='conv']").val(), $("input[name='sensors']").val(),$("input[name='sensors']").data("id"));
        $("#maintab").hide();
    });

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
                    var sAppend = "<label class='checkcontainer' onclick='getSensData("+$("select[name='conv']").val()+","+data[1][id].sensorid+","+data[1][id].axeid+")'>Ось " + data[1][id].axeid + ". ID метки "+data[1][id].sensorid;
                    sAppend += "<input type='radio' name='sensors' class='sensclass' value='"+data[1][id].sensorid+"' data-id='"+data[1][id].axeid+"'>";
                    sAppend += "<span class='checkmark'></span></label>";
                    $(sAppend).appendTo('#sensorslist');
                }
                swal("Отобразить первую кривую из выбранного списка?", {
                    buttons: ["Нет", "Да"],
                    icon: "success",
                })
                .then(function(result){
                    if (result) {
                        getSensData($("select[name='conv']").val(), data[1][0].sensorid, data[1][0].axeid);
                    }
                });
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
                    var sAppend = "<label class='checkcontainer' onclick='getSensData("+$("select[name='conv']").val()+","+data[id].sensorid+","+data[id].axeid+")'>Ось " + data[id].axeid +". ID метки "+data[id].sensorid;
                    sAppend += "<input type='radio' name='sensors' class='sensclass' value='"+data[id].sensorid+"' data-id='"+data[id].axeid+"'>";
                    sAppend += "<span class='checkmark'></span></label>";
                    $(sAppend).appendTo('#sensorslist');
                }
                swal("Отобразить первую кривую из выбранного списка?", {
                    buttons: ["Нет", "Да"],
                    icon: "success",
                })
                .then(function(result){
                    if (result) {
                        getSensData($("select[name='conv']").val(), data[0].sensorid, data[0].axeid);
                    }
                });
            };
        $.get(url,sel,action);
    });

    $("input[name='sensors']").bind("click",function(){
        getSensData($("select[name='conv']").val(), parseInt(this.value), $(this).data("id"));
    });

    function getSensData(convId, sensId, axeTitle){
        $.ajax({
                type:'GET',
                url: "/trends/getsensor/" + convId + "/" + sensId,
                cache: false,
                crossDomain: true,
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                data:{
                    sens:this.value
                },
                success: function(data){
                    var trendData = [];
                    var length = 1;
                    trendData = data;
                    for(var id in data){
                        trendData[id].recdate = GetDateFromStr(data[id].recdate);
                    }
                    var el = document.getElementById('chartdiv');
                    createStockChart(el, trendData, length, "Ось " + axeTitle + ". ID метки " + sensId);
                },
                error: function(err){
                    console.log(err);
                }
            });
    }

    function createStockChart(elem, chartData, datalen, sGraphTitle) {

        $(elem.id).empty();

        var chart = new AmCharts.AmStockChart();

        var col = ["#17A2B8","#FF7F0E","#2CA02C","#D62728","#9467BD","#FF00DC","#FFD200","#9B3B00","#000000","#9B3B00"];
        var lineType = ["line", "smoothedLine", "step", "column", "candlestick", "ohlc"];

        var categoryAxesSettings = new AmCharts.CategoryAxesSettings();
            categoryAxesSettings.minPeriod = "10SS";
            categoryAxesSettings.equalSpacing = true;
            categoryAxesSettings.parseDates = true;
        chart.categoryAxesSettings = categoryAxesSettings;

        var valueAxesSettings = new AmCharts.ValueAxesSettings();
            valueAxesSettings.minimum = 0;
            valueAxesSettings.maximum = 100;
        chart.valueAxesSettings = valueAxesSettings;

        var dataSet = new AmCharts.DataSet();
            dataSet.dataProvider = chartData;
            dataSet.categoryField = "recdate";
            dataSet.fieldMappings = [
                { fromField: "value", toField: "value1" },
            ];
        chart.dataSets = [dataSet];

        var stockPanel1 = new AmCharts.StockPanel();
            stockPanel1.showCategoryAxis = true;
            stockPanel1.title = "Уровень, %";
            stockPanel1.percentHeight = 100;//70

        var graph = [];

        for(var i=1; i <=datalen; i++){
            graph[i] = new AmCharts.StockGraph();
            graph[i].title = sGraphTitle;
            graph[i].balloonText = "[[value]]%";
            graph[i].useDataSetColors = false;
            graph[i].lineColor = col[i-1];
            graph[i].valueField = "value" + parseInt(i);
            graph[i].type = lineType[$("select[name='trtype']").val()]; // 0 - line, 1 - smoothedline, 2 - step, 3 - column,  4 - candlestick, 5 - ohlc
            graph[i].lineThickness = 3;
            graph[i].bullet = "round";
            graph[i].noStepRisers = true;
            graph[i].bulletSize = 6;
            graph[i].bulletBorderColor = "white";
            graph[i].bulletBorderAlpha = 1;
            graph[i].bulletBorderThickness = 2;
            graph[i].id = parseInt(i);
            stockPanel1.addStockGraph(graph[i]);
            if(lineType[$("select[name='trtype']").val()]==2){
                graph[i].stepDirection = "right";
            }
        }

        var stockLegend1 = new AmCharts.StockLegend();
            stockLegend1.valueTextRegular = " ";
            stockPanel1.stockLegend = stockLegend1;

            chart.panels = [stockPanel1];

        var scrollbarSettings = new AmCharts.ChartScrollbarSettings();
            scrollbarSettings.graph = graph[1];
            scrollbarSettings.usePeriod = "10ss";//10mm
            scrollbarSettings.updateOnReleaseOnly = false;
            scrollbarSettings.position = "bottom";
            scrollbarSettings.graphType = "line";
            scrollbarSettings.autoGridCount = true;
            
            chart.chartScrollbarSettings = scrollbarSettings;

        var cursorSettings = new AmCharts.ChartCursorSettings();
            cursorSettings.showNextAvailable = true;
            cursorSettings.cursorColor = "#17A2B8";
            cursorSettings.valueLineEnabled = false; // true
            cursorSettings.valueLineAlpha = 0.5;

            cursorSettings.categoryBalloonDateFormats = [
                {period:"YYYY", format:"YYYY"},
                {period:"MM", format:"DD MMM YYYY JJ:NN:SS"},
                {period:"WW", format:"DD MMM JJ:NN:SS"},
                {period:"DD", format:"DD MMM JJ:NN:SS"},
                {period:"hh", format:"DD MMM JJ:NN:SS"},
                {period:"mm", format:"DD MMM JJ:NN:SS"},
                {period:"ss", format:"DD MMM JJ:NN:SS"},
                {period:"fff", format:"DD MMM JJ:NN:SS"}
            ]; // "fff"-milliseconds
            
            cursorSettings.valueBalloonsEnabled = true;
            cursorSettings.fullWidth = false;
            cursorSettings.cursorAlpha = 0.1;

        chart.chartCursorSettings = cursorSettings;
        
        var periodSelector = new AmCharts.PeriodSelector();
            periodSelector.position = "bottom";
            periodSelector.dateFormat = "YYYY-MM-DD JJ:NN:SS";
            periodSelector.inputFieldWidth = 150;
            periodSelector.periods = [
                { period: "DD", count: 1, label: "1 день" },
                { period: "DD", selected: true, count: 5, label: "5 дней" },
                { period: "MM", count: 1, label: "1 месяц" },
                { period: "YYYY", count: 1, label: "1 год" },
                { period: "MAX", label: "Все" }
            ];

        chart.periodSelector = periodSelector;
        
        chart.dataDateFormat = "YYYY-MM-DD JJ:NN:SS";

        var panelsSettings = new AmCharts.PanelsSettings();
            panelsSettings.mouseWheelZoomEnabled = false;
            panelsSettings.usePrefixes = true;

        chart.panelsSettings = panelsSettings;

        chart.write(elem.id);
        chart.validateNow();
        $(elem.id).fadeIn(1000);
    }

    function GetDateFromStr(str){
        return new Date(str.substring(0,10) + "T" + str.substring(11,19) + "");
    }
