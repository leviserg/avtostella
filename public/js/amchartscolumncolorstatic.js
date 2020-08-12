var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "dataProvider": [{
      "country": "USA",
      "visits": 3025,
      "color": "#FF0F00",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern1.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "China",
      "visits": 1882,
      "color": "#FF6600",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern1.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "Japan",
      "visits": 1809,
      "color": "#FF9E01",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern2.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "Germany",
      "visits": 1322,
      "color": "#FCD202",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern5.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "UK",
      "visits": 1122,
      "color": "#F8FF01",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern5.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "France",
      "visits": 1114,
      "color": "#B0DE09",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern5.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "India",
      "visits": 984,
      "color": "#04D215",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern8.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "Spain",
      "visits": 711,
      "color": "#0D8ECF",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern8.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "Netherlands",
      "visits": 665,
      "color": "#0D52D1",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern14.png",
        "width": 4,
        "height": 4
          }
    }, {
      "country": "Russia",
      "visits": 580,
      "color": "#2A0CD0"
    }, {
      "country": "South Korea",
      "visits": 443,
      "color": "#8A0CCF"
    }, {
      "country": "Canada",
      "visits": 441,
      "color": "#CD0D74"
    }],
    "valueAxes": [{
      "axisAlpha": 0,
      "position": "left",
      "title": "Visitors from country"
    }],
    "startDuration": 1,
    "graphs": [{
      "balloonText": "<b>[[category]]: [[value]]</b>",
      "fillColorsField": "color",
      "fillAlphas": 0.9,
      "lineAlpha": 0.2,
      "type": "column",
      "valueField": "visits",
      "patternField": "pattern",
      "pattern": {
        "url": "https://www.amcharts.com/lib/3/patterns/black/pattern19.png",
        "width": 4,
        "height": 4
                  }
    }, {
      "fillColorsField": "color",
      "fillAlphas": 0.5,
      "lineAlpha": 0.2,
      "type": "column",
      "valueField": "visits",
      "clustered": false,
      "showBalloon": false,
      "visibleInLegend": false
    }],
    "chartCursor": {
      "categoryBalloonEnabled": false,
      "cursorAlpha": 0,
      "zoomable": false
    },
    "categoryField": "country",
    "categoryAxis": {
      "gridPosition": "start",
      "labelRotation": 45
    }

  });
