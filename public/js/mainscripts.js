    var updatePeriod = 10; // 15 sec

    var conveyors = document.getElementById('conveyors_id').innerHTML.slice(0, -1).split(",");
    var convindex = conveyors[0].toString()[0] + conveyors[1].toString()[0];

    $(document).ready(function(){
        $("#maintab").show();
        for(var i = 0; i < conveyors.length; i++){
            var sTitleName = "#chart_" + i + "_title";
            var ind = '';
            parseInt(conveyors[i].toString()[1]) == 2 ? ind = 'Б' : ind = 'A';
            //$(sTitleName).text("Конвеер О-" + conveyors[i].toString()[0] + "-" + conveyors[i].toString()[1]);
            $(sTitleName).text("О-" + conveyors[i].toString()[0] + "" + ind);
        }

        $('#goto' + convindex).on('click', function(){
            $('.navbar-collapse').collapse('hide');
        });
        $('#gotolast' + convindex).on('click', function(){
            $('.navbar-collapse').collapse('hide');
        });
        showLastData(convindex);
    });

    $("#hist").bind("click",function(elem){
        $('#showhist').modal("show");
    });

    $("#mills56").bind("click",function(elem){
        GetMillsWeight(convindex);
        $('#mills').modal("show");
    });

    var dt = setInterval("showLastData("+convindex+")", updatePeriod*1000);

    $("#forecast"+convindex).click(function(){
        
        clearInterval(dt);
        $('#mills').modal("hide");
        $('#mills'+convindex).tooltip('hide');
        $('#hist').removeClass('active');
        $('#gotolast'+convindex).removeClass('active');
        $('#mills'+convindex).addClass('active');
        ForeDataGraph(convindex);
        $("#titletext").text("Прогнозный уровень ");
    });

    $("#goto"+convindex).click(function(){
        $('#goto'+convindex).tooltip('hide');
        clearInterval(dt);
        $('#showhist').modal("hide");
        $('#hist').addClass('active');
        $('#gotolast'+convindex).removeClass('active');
        $('#mills'+convindex).removeClass('active');            
        showSelectedData(convindex);
        $("#titletext").text("Прошлый уровень ");
    });

    $("#gotolast"+convindex).click(function(){
        $('#gotolast'+convindex).tooltip('hide');
        showLastData(convindex);
        $('#hist').removeClass('active');
        $('#gotolast'+convindex).addClass('active');
        $('#mills'+convindex).removeClass('active');
        $("#titletext").text("Текущий уровень ");
        dt = setInterval("showLastData("+convindex+")", updatePeriod*1000);
    });

    function showSelectedData(param){
        
        var selTime = $('#tpicker').val();
        if(selTime.length < 5){
            selTime += "00:00:00";
        }
        else if(selTime.length <= 7){
            selTime += ":00";
        }

        var strDate = $('#dpicker').val().toString();

        $('#seldate').text(strDate.slice(-2) + "." + strDate.slice(5, 7) + "." + strDate.substring(0, 4) + " " + selTime);

        var gotodate = $('#dpicker').val() + " " + selTime;

        DataGraph(gotodate, param, false);
    }

    function showLastData(param){

        var date = new Date();
        var year = date.getFullYear();
        var month = r2time(parseInt(date.getMonth()) + 1);
        var day = r2time(date.getDate());
        var hour = r2time(date.getHours());
        var minutes = r2time(date.getMinutes());
        var seconds = r2time(date.getSeconds());

        var lastcordate = year + "-" + month + "-" + day + " " + hour + ":" + minutes + ":" + seconds;

        $('#seldate').text(day+ "." + month + "." + year + " " + hour + ":" + minutes + ":" + seconds);
        DataGraph(lastcordate, param, true);

        updateActUnackCount();
    }

    function DataGraph(seldate, param, cur){
        setTableData(param, seldate);
        setStellaData(param, seldate, cur);
        setConvData(param, seldate);
    }

    function ColumnColor(hours){
        //return "hsl("+(Number(Math.round(hours*6.25))+Number(120))+",100%,40%)";
        var ret;
        if (hours < 0){
            ret="#FFB27F"; // orange
        }
        else if(hours <= 3){
            //return '#00FE3B'; // lightgreen
            ret="hsl(130, 100%," + Number(50 - Math.round(hours*3.33)) + "%)"; // 40-30
        }
        else if(hours <= 6){
            //return '#00A733'; // green
            ret="hsl(150, 100%," + Number(50 - Math.round(hours*1.6)) + "%)"; // 30-20
        }
        else if(hours <= 9){
            //return '#006F13'; // darkgreen
            ret="hsl("+ Number(160 + Math.round(hours*3))+", 100%,40%)"; // 160-190
        }
        else if(hours <= 12){
            //return '#00C1D0'; // lightblue
            ret="hsl("+ Number(190 + Math.round(hours*1.6))+", 100%,40%)"; // 190-210
        }
        else if(hours <= 15){
            //return '#4448CF'; //  blue
            ret="hsl("+ Number(210 + Math.round(hours))+", 100%,40%)"; // 210-225
        }
        else if(hours <= 18){
            //return '#4600B1'; //  darkblue
            ret="hsl("+ Number(250 + Math.round(hours))+", 100%,30%)"; // 210-225
        }
        else if(hours <= 21){
            //return '#500079';  // viol
            ret="hsl(280, 100%," + Number(46 - Math.round(hours)) + "%)"; // 210-225
        }
        else{
            ret="#878787";
        }
        return ret;
    }

    function MyChart(divid, data){
        var chart = AmCharts.makeChart(divid, {
            "type": "serial",
            "theme": "light",
            "dataProvider": data,
            /*"startDuration": 0.5,*/
            "graphs": [
                {
                    "fillColorsField": "color",
                    "fillAlphas": 1.0,
                    "lineAlpha": 0.5,
		    "lineColor":"#606060",
                    "type": "column",
                    "columnWidth":1.0,
                    "valueField": "value",
                    "clustered": false,
                    "showBalloon": false,
                    "visibleInLegend": false
                  },
                {
                    "balloonText": "Ось [[axeid]] : <b>[[value]]%</b>.<br/><small>[[recdate]]</small>",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.5,
		    "lineColor":"#606060",
                    "valueField": "value",
                    "fillColorsField": "color",
                    "type": "column",
                    "columnWidth":1.0,
                    "clustered": false,
                    "showBalloon": true,
                    "visibleInLegend": true,
                    "patternField" : "pattern"
                },
                {
                    "balloonText": "Секция [[msection]]. <b>[[meanVal]]%</b>",
                    "type": "step",
                    "stepDirection":"right",
                    "lineColor" : "#FF6A00",
                    "valueField": "meanVal",
                    "lineThickness" : 3,
                    "showBalloon": true,
                    "visibleInLegend": true
                }
            ],
            "valueAxes": [
                {
                    "minimum": "0",
                    "maximum": "100"
                },
                {
                    "minimum": "0",
                    "maximum": "100"
                }
            ],
            "chartCursor": {
            "categoryBalloonEnabled": true,
            "cursorAlpha": 0,
            "zoomable": false
            },
            "categoryField": "axeid",
            "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 0
            }
        });
        return chart;
    }

    function r2time(param){
        return ('0' + param).toString().slice(-2);
    }

    function DateToStr(date){
        var cdate = r2time(date.getDate());
        var cmonth = r2time(date.getMonth() + Number(1));
        var cyear = date.getFullYear()
        var chour = r2time(date.getHours());
        var cmin = r2time(date.getMinutes());
        var csec = r2time(date.getSeconds());
        return cdate + '.'+cmonth+'.'+cyear + ' ' + chour + ':' + cmin + ':' + csec;
    }

    function addDays(date, days) {
        var copy = new Date(Number(date));
        copy.setDate(date.getDate() + Number(days));
        return copy;
    }

    function backDays(date, days) {
        var copy = new Date(Number(date));
        copy.setDate(date.getDate() - Number(days));
        return copy;
    }

    function ForeDataGraph(param){
        $.ajax({
            type:'GET',
            url: "../home/"+param+"/forecast",
            cache: false,
            crossDomain: true,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data){

                $('.row, .chartdiv, .t1').removeClass('anim');
                $('.row, .chartdiv, .t0').removeClass('anim');
                var jsonDataArray = [];
                for(var i in data)
                    jsonDataArray.push(data[i]);

                var groupData = [];
                var sensData = [];
                var meanValues = [];
                var chartData = [];
                var charts = [];

                for(var i = 0; i < conveyors.length; i++){
                    sensData[i] = [];
                    meanValues[i] = [];
                    var sChartDivName = "chart_";

                    groupData[i] = jLinq.from(jsonDataArray)
                        .equals("conveyor", parseInt(conveyors[i]))
                        .group("mill");
                        //.group("msection");

                    for(var j in groupData[i]){
                        if(groupData[i][j]!=null){
                            var mVal = 0.0;
                            var mLen = groupData[i][j].length;
                            for(var k in groupData[i][j]){
                                mVal += parseFloat(groupData[i][j][k].value);
                            }
                            var meanValue = Math.round(mVal/mLen*10)/10;// *100)/100 -> 0.01
                            var minaxeid = jLinq.from(groupData[i][j]).min('axeid').axeid;
                            var maxaxeid = jLinq.from(groupData[i][j]).max('axeid').axeid;
                            meanValues[i].push({
                                value: meanValue,
                                msection:groupData[i][j][0].msection,
                                mill:groupData[i][j][0].mill,
                                minaxeid:minaxeid,
                                maxaxeid:maxaxeid
                            });
                            for(var k in groupData[i][j]){
                                groupData[i][j][k].meanVal = meanValue;
                                sensData[i].push(groupData[i][j][k]);
                            }
                        }
                    }

                    var sTableName = "#chart_" + i + "_table";
                    $(sTableName).empty();
                    var sTableText = "<table class='table table-bordered' style='width:100%;'>";

                    var ConvSumValue = 0.0;

                    sTableText += "<tr class='py-0 my-0'>";
                    for(var j = 0; j < meanValues[i].length; j++){
                        sTableText += "<td class='text-center py-0 my-0 px-0 mx-0'>";
                        sTableText += "<small class='py-0 my-0 px-0 mx-0 small-font'>М" + meanValues[i][j].mill + " ["+ meanValues[i][j].minaxeid + "-" + meanValues[i][j].maxaxeid + "]</small>";
                        /*sTableText += "<small class='py-0 my-0 px-0 mx-0 '><b>М" + meanValues[i][j].mill + "</b> <small>["+ meanValues[i][j].minaxeid + "-" + meanValues[i][j].maxaxeid + "]</small></small>";*/
                        sTableText += "</td>";                      
                    }
                    sTableText += "</tr>";

                    sTableText += "<tr>";
                    for(var j = 0; j < meanValues[i].length; j++){
                       var str = meanValues[i][j].value.toString();
                       if(str.indexOf('.') == -1){
                            str += '.0';
                       }
                       sTableText += "<td class='text-center py-0 my-0'>";
                       sTableText += str + "%";
                       sTableText += "</td>";
                        ConvSumValue += parseFloat(meanValues[i][j].value);
                    }

                    var ConvMeanValue = ConvSumValue / parseFloat(meanValues[i].length);

                    sTableText += "</tr>";
                    sTableText += "</table>";
                    $(sTableName).append(sTableText);

                    var sLocMeanId = "#chart_" + i + "_title";
                    var curTitle = $(sLocMeanId).text().substring(0,4);
                    var newTitle = curTitle + " " + Math.round(ConvMeanValue*10)/10 + "%";
                    $(sLocMeanId).text(newTitle);

                    chartData[i] = jLinq.from(sensData[i])
                        .select(function(rec) {
			var pattern = null;
                            return {
                                axeid       : rec.axeid,
                                sensorid    : rec.sensorid,
                                recdate     : rec.recdate,
                                value       : rec.value,
                                msection    : rec.msection,
                                color       : ColumnColor(rec.diff),
                                meanVal     : rec.meanVal,
                                validity    : rec.validity,
                            	pattern     : pattern
                            };
                        });
                    charts[i] = MyChart(sChartDivName + i, chartData[i]);
                    $('.row, .chartdiv, .t1').addClass('anim');
                    $('.row, .chartdiv, .t0').addClass('anim');
                }
                swal("Данные прогноза", "Готово...", "success");
		data = null;
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function setStellaData(param, seldate, cur){
        $.ajax({
            type:'GET',
            url:"../home/"+param+"/stella/" + seldate ,
            cache: false,
            crossDomain: true,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data){
                for(var id in data){
                    var sImgId = '#stella';
                    switch(data[id].conveyor) {
                        case 31: sImgId += "_0"; break;
                        case 51: sImgId += "_0"; break;
                        case 41: sImgId += "_1"; break;
                        case 61: sImgId += "_1"; break;
                        case 32: sImgId += "_2"; break;
                        case 52: sImgId += "_2"; break;
                        case 42: sImgId += "_3"; break;
                        case 62: sImgId += "_3"; break;
                        default: ;
					}
                    sDivId = '#pos';
                    switch(data[id].conveyor) {
                        case 31: sDivId += "_0"; break;
                        case 51: sDivId += "_0"; break;
                        case 41: sDivId += "_1"; break;
                        case 61: sDivId += "_1"; break;
                        case 32: sDivId += "_2"; break;
                        case 52: sDivId += "_2"; break;
                        case 42: sDivId += "_3"; break;
                        case 62: sDivId += "_3"; break;
                        default: ;
					}
                    var sSpanId = '#pos';
                    switch(data[id].conveyor) {
                        case 31: sSpanId += "_0_txt"; break;
                        case 51: sSpanId += "_0_txt"; break;
                        case 41: sSpanId += "_1_txt"; break;
                        case 61: sSpanId += "_1_txt"; break;
                        case 32: sSpanId += "_2_txt"; break;
                        case 52: sSpanId += "_2_txt"; break;
                        case 42: sSpanId += "_3_txt"; break;
                        case 62: sSpanId += "_3_txt"; break;
                        default: ;
                    }

                    $(sSpanId).text(data[id].axeid);
                    sSpanId = sSpanId.replace('#pos', '#lev');
                    $(sSpanId).text(Math.round(data[id].level) + '%');    
                                    
                    var sImgSrc = '../public/css/'; // 0-Off,1-Fwd,2-Rev,3-Error
                    switch(data[id].status) {
                        case 1: sImgSrc += "avt_fwd.png"; break;
                        case 2: sImgSrc += "avt_rev.png"; break;
                        case 3: sImgSrc += "avt_err.png"; break;
                        case 4: sImgSrc += "avt_off.png"; break;
                        default:
                            sImgSrc += "avt_on.png"
                        ;
                    }
                    //console.log(data[id]);
                    var sToolTip = 'Ось : ' + data[id].axeid + '\n';
                    //sToolTip += 'Позиция : ~' + Math.round((data[id].position)*100/90) + '%\n';
		            //sToolTip += 'Время : ' + data[id].recdate + '\n';
                    if(cur){
                        sToolTip += 'Время : ' + data[id].recdate + '\n';
                        sToolTip += 'Мгновенный уровень : ' + data[id].level + '%\n';
                        sToolTip += 'АЦП : ' + data[id].ai;
                    }
                    else{
                        sToolTip += 'Время : ' + data[id].created_at + '\n';
                    }
                    
                    //sToolTip += 'Мгновенный уровень : ' + data[id].level + '%\n';
                    //sToolTip += 'АЦП : ' + data[id].ai;
                    $(sImgId).css("width","70px");
                    $(sImgId).css("height","46px");
                    $(sImgId).attr("src", sImgSrc);

                    $(sDivId).attr('title', sToolTip);
		    $(sDivId).css("left", Number(data[id].position)*1.049 + "%")
                    if(data[id].conveyor%2 == 0)
                         $(sDivId).css("left", Number(data[id].position - 2.5)*0.99 + "%");
                    if(data[id].conveyor == 51)
                         $(sDivId).css("left", Number(data[id].position)*1.018 + "%");

                    //$(sSpanId).css("left", data[id].position + "%");
                }
		data = null;
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function setTableData(param, seldate){
        //$('.row, .chartdiv, .t1').removeClass('anim');
        //$('.row, .chartdiv, .t0').removeClass('anim');
        $.ajax({
            type:'GET',
            url:"../home/"+param+"/goto/" + seldate,
            cache: false,
            crossDomain: true,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data){

                var jsonDataArray = [];
                for(var i in data){
                    jsonDataArray.push(data[i]);
                }

                var groupData = [];
                var sensData = [];
                var meanValues = [];
                var chartData = [];
                var charts = [];

                for(var i = 0; i < conveyors.length; i++){
                    sensData[i] = [];
                    meanValues[i] = [];
                    var sChartDivName = "chart_";

                    groupData[i] = jLinq.from(jsonDataArray)
                        .equals("conveyor", parseInt(conveyors[i]))
                        .group("mill");
                        //.group("msection");

                    for(var j in groupData[i]){
                        if(groupData[i][j]!=null){
                            var mVal = 0.0;
                            var mLen = groupData[i][j].length;
                            for(var k in groupData[i][j]){
                                mVal += parseFloat(groupData[i][j][k].value);
                            }
                            var minaxeid = jLinq.from(groupData[i][j]).min('axeid').axeid;
                            var maxaxeid = jLinq.from(groupData[i][j]).max('axeid').axeid;

                            var meanValue = Math.round(mVal/mLen*10)/10;// *100)/100 -> 0.01
                            meanValues[i].push({
                                value: meanValue,
                                msection:groupData[i][j][0].msection,
                                mill:groupData[i][j][0].mill,
                                minaxeid:minaxeid,
                                maxaxeid:maxaxeid
                            });
                            for(var k in groupData[i][j]){
                                groupData[i][j][k].meanVal = meanValue;
                                sensData[i].push(groupData[i][j][k]);
                            }
                        }
                    }

                    var sTableName = "#chart_" + i + "_table";
                    $(sTableName).empty();
                    var sTableText = "<table class='table table-bordered' style='width:100%;'>";
                    var ConvSumValue = 0.0;

                    sTableText += "<tr class='py-0 my-0'>";
                    for(var j = 0; j < meanValues[i].length; j++){
                        sTableText += "<td class='text-center py-0 my-0 px-0 mx-0'>";
                        sTableText += "<small class='py-0 my-0 px-0 mx-0 '><b>М" + meanValues[i][j].mill + "</b> <small>["+ meanValues[i][j].minaxeid + "-" + meanValues[i][j].maxaxeid + "]</small></small>";
                       
                        sTableText += "</td>";                      
                    }
                    sTableText += "</tr>";

                    sTableText += "<tr>";
                    for(var j = 0; j < meanValues[i].length; j++){
                        var str = meanValues[i][j].value.toString();
                        if(str.indexOf('.') == -1){
                            str += '.0';
                        }
                        sTableText += "<td class='text-center py-0 my-0'>";
                        sTableText += str + "%";
                        sTableText += "</td>";
                        ConvSumValue += parseFloat(meanValues[i][j].value);
                    }

                    var ConvMeanValue = ConvSumValue / parseFloat(meanValues[i].length);

                    sTableText += "</tr>";
                    sTableText += "</table>";
                    $(sTableName).append(sTableText);

                    var sLocMeanId = "#chart_" + i + "_title";
                    var curTitle = $(sLocMeanId).text().substring(0,4);
                    var newTitle = curTitle + " " + Math.round(ConvMeanValue*10)/10 + "%";
                    $(sLocMeanId).text(newTitle);

                    chartData[i] = jLinq.from(sensData[i])
                        .select(function(rec) {
                            var pattern;
                            var path = "/avtostella/public/css/";
                                path += "nonvalidpattern.png";
                            if(rec.validity == 1){
                                pattern = {
                                    "url": path,
                                    "width": 4,
                                    "height": 4
                                }
                            }
                            else{
                                pattern = null                            
                            }
                        return {
                            axeid       : rec.axeid,
                            sensorid    : rec.sensorid,
                            recdate     : rec.recdate,
                            value       : rec.value,
                            msection    : rec.msection,
                            color       : ColumnColor(rec.diff),
                            timediff    : rec.diff,
                            meanVal     : rec.meanVal,
                            pattern     : pattern
                        };
                    });
                    charts[i] = MyChart(sChartDivName + i, chartData[i]);

                    if(!$('.row, .chartdiv, .t1').hasClass('anim'))
                        $('.row, .chartdiv, .t1').addClass('anim');
                    if(!$('.row, .chartdiv, .t0').hasClass('anim'))
                        $('.row, .chartdiv, .t0').addClass('anim');

                }
		data = null;
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function setConvData(param, seldate){

        var nowDate = new Date();
        var selectedDate = StrToDate(seldate);
        var hourDiff = Math.round((nowDate - selectedDate)/3600000);
        if(hourDiff<3){
            $.ajax({
                type:'GET',
                url:"../home/"+param+"/feed/" + seldate,
                cache: false,
                crossDomain: true,
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(data){
                    var sImgSrc = '../public/css/';
                    var convtitle = ['O-5А', 'O-6А','O-5Б', 'O-6Б'];
                    if(param < 50)
                        convtitle = ['O-3А', 'O-4А','O-3Б', 'O-4Б'];
                    for(var id in data){
                        var curNum = Number(id);
                        if(curNum == 1){
                            curNum++;
                        }
                        else if(curNum == 2){
                            curNum--;
                        }
                        if(data[id].status == 1){
                            $("#conv_" + curNum).attr("src", sImgSrc + "conv_on.png");
                        }
                        else if(data[id].status == 2){
                            $("#conv_" + curNum).attr("src", sImgSrc + "conv_err.png");
                        }
                        else{
                            $("#conv_" + curNum).attr("src", sImgSrc + "conv_off.png");
                        }
                        $("#convflow_" + curNum).text(convtitle[curNum]+ ":" + data[id].weight +"т/ч"); // "т/ч"
                        $("#convflow_" + curNum).addClass('text-secondary');
                        $("#convflow_" + curNum).addClass('small');
                        $("#convflow_" + curNum).addClass('ml-0 mr-0');
                    }
		    data = null;
                },
                error: function(err){
                    console.log(err);
                }
            });
        }
        else{
            var sImgSrc = '../public/css/';
            var convtitle = ['O-5А', 'O-6А','O-5Б', 'O-6Б'];
            if(param < 50)
                convtitle = ['O-3А', 'O-4А','O-3Б', 'O-4Б'];
            for(var i = 0; i < 4; i++){
                var curNum = Number(i);
                if(curNum == 1){
                    curNum++;
                }
                else if(curNum == 2){
                    curNum--;
                }
                $("#conv_" + curNum).attr("src", sImgSrc + "conv_off.png");
                $("#convflow_" + curNum).text(convtitle[curNum]+ " : нет данн"); // "т/ч"
                $("#convflow_" + curNum).addClass('text-secondary');
                $("#convflow_" + curNum).addClass('small');
                $("#convflow_" + curNum).addClass('ml-0 mr-0');
            }            
        }
    }

    function StrToDate(str){
        return new Date(str.substring(0,10) + "T" + str.substring(11,19) + "");
     }

     function GetMillsWeight(param){
        $.ajax({
            type:'GET',
            url: "../home/"+param+"/weight",
            cache: false,
            crossDomain: true,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data){

                var sTableName = "#weight_table";
                $(sTableName).empty();

                var sTableText = "<table class='table table-bordered' style='width:90%;'>";
                    sTableText += "<tr>";
                    sTableText += "<td class='fit-wide text-center py-0 my-0'><b>";
                    sTableText += "Мельница";
                    sTableText += "</b></td>";
                    sTableText += "<td class='fit-wide text-center py-0 my-0'><b>";
                    sTableText += "Расход, т/ч";
                    sTableText += "</b></td>";
                    sTableText += "<td class='fit-wide text-center py-0 my-0'><b>";
                    sTableText += "Конвеер";
                    sTableText += "</b></td>";
                    sTableText += "<td class='fit-wide text-center py-0 my-0'><b>";
                    sTableText += "Кол-во бункеров";
                    sTableText += "</b></td>";
                    sTableText += "<td class='fit-wide text-center py-0 my-0'><b>";
                    sTableText += "Расход на 1 бункер";
                    sTableText += "</b></td>";
                    sTableText += "</tr>";

                    for(var i in data){
                        sTableText += "<tr>";
                            sTableText += "<td class='text-center py-0 my-0'>";
                            sTableText += data[i].mill;
                            sTableText += "</td>";
                            sTableText += "<td class='text-center py-0 my-0'>";
                            sTableText += data[i].weight;
                            sTableText += "</td>";
                            sTableText += "<td class='text-center py-0 my-0'>";
                                if(data[i].convid == 51){
                                    sTableText += "O-5A";
                                }
                                else if(data[i].convid == 61){
                                    sTableText += "O-6A";
                                }
                                else{
                                    sTableText += "O-5Б, O-6Б";
                                }
                            sTableText += "</td>";
                            sTableText += "<td class='text-center py-0 my-0'>";
                            sTableText += data[i].axes;
                            sTableText += "</td>";
                            sTableText += "<td class='text-center py-0 my-0'>";
                            sTableText += data[i].axweight;
                            sTableText += "</td>";
                        sTableText += "</tr>";
                    }
                    sTableText += "</table>";
                    $(sTableName).append(sTableText);
		    data = null;
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function updateActUnackCount(){
        $.ajax({
            type:'GET',
            url:"../alarms/getactunack",
            cache: false,
            crossDomain: true,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data){
                var unack = data;
                $('#unacksum').text(unack);
		data = null;
            },
            error: function(err){
                console.log(err);
            }
        });
    }    