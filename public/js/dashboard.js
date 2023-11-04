$(function () {
  'use strict'

  var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
  }

  function isValue(value, def, is_return) {
    if ( $.type(value) == 'null'
        || $.type(value) == 'undefined'
        || $.trim(value) == '(en blanco)'
        || $.trim(value) == ''
        || ($.type(value) == 'number' && !$.isNumeric(value))
        || ($.type(value) == 'array' && value.length == 0)
        || ($.type(value) == 'object' && $.isEmptyObject(value)) ) {
        return ($.type(def) != 'undefined') ? def : false;
    } else {
        return ($.type(is_return) == 'boolean' && is_return === true ? value : true);
    }
}

  var mode = 'index'
  var intersect = true

  var $salesChart = $('#sales-chart')
  

  var vLabel = []
  var vData = []

  $.getJSON("getDashboard", function(dataset) {

      var Ingreso = dataset['INGRESO'];
      Ingreso     = numeral(isValue(Ingreso,0,true)).format('0,00.00');
      $("#lblIngreso").text(Ingreso)

      

      var CAPITAL = dataset['CAPITAL'];
      CAPITAL     = numeral(isValue(CAPITAL,0,true)).format('0,00.00');
      $("#lblCapital").text(CAPITAL)

      var INTERESES = dataset['INTERESES'];
      INTERESES     = numeral(isValue(INTERESES,0,true)).format('0,00.00');
      $("#lblInteres").text(INTERESES)

      var Clientes = dataset['clientes_activos'];
      Clientes     = numeral(isValue(Clientes,0,true)).format('0,00');
      $("#lblClientes").text(Clientes)

      var Cartera = dataset['SALDOS_CARTERA'];
      Cartera     = numeral(isValue(Cartera,0,true)).format('0,00.00');
      
      $("#lblSaldosCartera").text(Cartera)

      $.each(dataset.Data, function(i, item) {
        vData.push(item);
      })

      $.each(dataset.label, function(i, item) {
        vLabel.push(item);
      })

      var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
        labels: vLabel,
        datasets: [{
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                data: vData
                },
            ]
        },
        options: {
        maintainAspectRatio: false,
        tooltips: {
            mode: mode,
            intersect: intersect
        },
        hover: {
            mode: mode,
            intersect: intersect
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
            // display: false,
            gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
            },
            ticks: $.extend({
                beginAtZero: true,
  
                // Include a dollar sign in the ticks
                callback: function (value) {
                if (value >= 1000) {
                    value /= 1000
                    value += 'k'
                }
  
                return 'C$' + value
                }
            }, ticksStyle)
            }],
            xAxes: [{
            display: true,
            gridLines: {
                display: false
            },
            ticks: ticksStyle
            }]
        }
        }
    })

  })




})