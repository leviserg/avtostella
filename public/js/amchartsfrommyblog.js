    var chartData = [
        {"views":"13","country":"Ukraine, Vinnytsia","visDate":"2019-07-29 09:13:35"},
        {"views":"7","country":"Poland, Lublin","visDate":"2019-06-11 11:42:57"},
        {"views":"6","country":"Ukraine, Vinnytsia","visDate":"2019-04-26 16:50:46"},
        {"views":"13","country":"Ukraine, Vinnytsia","visDate":"2019-04-19 14:11:04"},
        {"views":"20","country":"Ukraine, Vinnytsia","visDate":"2019-04-21 19:00:45"},
        {"views":"5","country":"Ukraine, Vinnytsia","visDate":"2019-07-15 15:41:16"},
        {"views":"6","country":"Canada, Mississauga","visDate":"2019-04-12 19:16:38"},
        {"views":"10","country":"Ukraine, Dnipro","visDate":"2019-04-04 20:51:22"},
        {"views":"10","country":"Ukraine, Kyiv","visDate":"2019-03-31 22:26:28"}
    ];

    AmCharts.ready(function () {
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "country";
                chart.startDuration = 1;

                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90;
                categoryAxis.gridPosition = "start";
                categoryAxis.title = "Site views statistic (5 & more views)";

                var graph = new AmCharts.AmGraph();
                graph.useDataSetColors = false;
                graph.title = "Some title";
                graph.valueField = "views";
                graph.fontSize = 6;
                graph.lineColor = '#007BFF';
                graph.balloonText = "[[category]]: <b>[[value]]</b>";
                graph.type = "column";
 //               graph.type = "candlestick";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                chart.addGraph(graph);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "top-right";

                chart.write("chartdiv");
            });
