/**
 * Add guide labels for each last data point
 * This will use the first data set
 */
AmCharts.addInitHandler(function(chart) {

  // get sata set
  var dataSet = chart.dataSets[0];

  // iterate through all of the panels
  for (var i = 0; i < chart.panels.length; i++) {

    // get panel
    var panel = chart.panels[i];

    // get last data point
    var dataPoint = dataSet.dataProvider[dataSet.dataProvider.length - 1];

    // init guides if necessary
    if (typeof panel.valueAxes[0].guides !== "object")
      panel.valueAxes[0].guides = [];

    // iterate through all graphs in chart
    for (var x = 0; x < panel.stockGraphs.length; x++) {

      // get graph
      var graph = panel.stockGraphs[x];

      // add separate field for label
      if (graph.type === undefined || graph.type === "line" || graph.type === "smoothedLine") {

        // add a guide at this value
        panel.valueAxes[0].guides.push({
          "value": dataPoint[graph.valueField],
          "inside": false,
          "label": dataPoint[graph.valueField],
          "lineAlpha": 0,
          "color": chart.colors[x]
        });

      }
    }
  }

}, ["stock"]);

var chart = AmCharts.makeChart("chartdiv", {
  "type": "stock",
  "theme": "light",

  "dataSets": [{
    "fieldMappings": [{
      "fromField": "value",
      "toField": "value"
    }, {
      "fromField": "value2",
      "toField": "value2"
    }],

    "dataProvider": generateChartData(),
    "categoryField": "date"
  }],

  "panels": [{
    "valueAxes": [{
      "position": "right"
    }],
    "stockGraphs": [{
      "valueField": "value",
      "lineThickness": 2,
      "type": "smoothedLine",
      "useDataSetColors": false
    }, {
      "valueField": "value2",
      "lineThickness": 2,
      "type": "smoothedLine",
      "useDataSetColors": false
    }]
  }],

  "chartCursorSettings": {
    "valueBalloonsEnabled": true
  },

  "valueAxesSettings": {
    "inside": false
  },
  
  "categoryAxesSettings": {
    "minPeriod": "mm",
    "equalSpacing": true,
    "startOnAxis": true
  },

  "panelsSettings": {
    "marginRight": 50
  }
});

function generateChartData() {
  var chartData = [];
  var firstDate = new Date(2012, 0, 1);
  firstDate.setDate(firstDate.getDate() - 50);
  firstDate.setHours(0, 0, 0, 0);

  for (var i = 0; i < 50; i++) {
    var newDate = new Date(firstDate);
    newDate.setHours(0, i, 0, 0);
    chartData.push({
      "date": newDate,
      "value": Math.round(Math.random() * (40 + i)) + 100 + i,
      "value2": Math.round(Math.random() * (40 - i)) + 100 + i
    });
  }
  return chartData;
}