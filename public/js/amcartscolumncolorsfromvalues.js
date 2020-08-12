/**
 * AmCharts plugin: Auto-calculate color based on value
 * The plugin relies on custom chart propety: `colorRanges`
 */
AmCharts.addInitHandler(function(chart) {

  var dataProvider = chart.dataProvider;
  var colorRanges = chart.colorRanges;

  // Based on https://www.sitepoint.com/javascript-generate-lighter-darker-color/
  function ColorLuminance(hex, lum) {

    // validate hex string
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
      hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    lum = lum || 0;

    // convert to decimal and change luminosity
    var rgb = "#",
      c, i;
    for (i = 0; i < 3; i++) {
      c = parseInt(hex.substr(i * 2, 2), 16);
      c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
      rgb += ("00" + c).substr(c.length);
    }

    return rgb;
  }

  if (colorRanges) {

    var item;
    var range;
    var valueProperty;
    var value;
    var average;
    var variation;
    for (var i = 0, iLen = dataProvider.length; i < iLen; i++) {

      item = dataProvider[i];

      for (var x = 0, xLen = colorRanges.length; x < xLen; x++) {

        range = colorRanges[x];
        valueProperty = range.valueProperty;
        value = item[valueProperty];

        if (value >= range.start && value <= range.end) {
          average = (range.start - range.end) / 2;

          if (value <= average)
            variation = (range.variation * -1) / value * average;
          else if (value > average)
            variation = range.variation / value * average;

          item[range.colorProperty] = ColorLuminance(range.color, variation.toFixed(2));
        }
      }
    }
  }

}, ["serial"]);

var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "light",
  "colorRanges": [{
    "start": 0,
    "end": 800,
    "color": "#0080FF",
    "variation": 0.6,
    "valueProperty": "visits",
    "colorProperty": "color"
  }, {
    "start": 801,
    "end": 1600,
    "color": "#FF2626",
    "variation": 0.6,
    "valueProperty": "visits",
    "colorProperty": "color"
  }, {
    "start": 1601,
    "end": 10000,
    "color": "#00B22D",
    "variation": 0.4,
    "valueProperty": "visits",
    "colorProperty": "color"
  }],
  "dataProvider": [{
    "country": "USA",
    "visits": 2025
  }, {
    "country": "China",
    "visits": 1882
  }, {
    "country": "Japan",
    "visits": 1809
  }, {
    "country": "Germany",
    "visits": 1322
  }, {
    "country": "UK",
    "visits": 1122
  }, {
    "country": "France",
    "visits": 1114
  }, {
    "country": "India",
    "visits": 984
  }, {
    "country": "Spain",
    "visits": 711
  }, {
    "country": "Netherlands",
    "visits": 665
  }, {
    "country": "Russia",
    "visits": 580
  }, {
    "country": "South Korea",
    "visits": 443
  }, {
    "country": "Canada",
    "visits": 441
  }, {
    "country": "Brazil",
    "visits": 395
  }],
  "valueAxes": [{
    "gridColor": "#FFFFFF",
    "gridAlpha": 0.2,
    "dashLength": 0
  }],
  "gridAboveGraphs": true,
  "startDuration": 1,
  "graphs": [{
    "balloonText": "[[category]]: <b>[[value]]</b>",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "visits",
    "colorField": "color"
  }],
  "chartCursor": {
    "categoryBalloonEnabled": false,
    "cursorAlpha": 0,
    "zoomable": false
  },
  "categoryField": "country",
  "categoryAxis": {
    "gridPosition": "start",
    "gridAlpha": 0,
    "tickPosition": "start",
    "tickLength": 20
  },
  "export": {
    "enabled": true
  }

});