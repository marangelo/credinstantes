<script type="text/javascript">
    $(document).ready(function () { 
        


        $("#IdbtnFilter").click(function () {
            CalcIndicadores()
        });


        $('#dt-Ini,#dt-End').datetimepicker({
            format: 'DD/MM/YYYY'
        });


        CalcIndicadores();
 
      
    });

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


    function CalcIndicadores(){
    
        var vLabel = []
        var vData = []

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }


        var $salesChart = $('#sales-chart')


        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val();

        console.log(dtEnd,dtIni)
        
        dtEnd      = isValue(dtEnd,'N/D',true) 
        dtIni      = isValue(dtIni,'N/D',true)
    
        const [dt_Ini_, dt_End_] = [dtIni, dtEnd].map(dt => moment(dt, 'DD/MM/YYYY').format('YYYY-MM-DD'));

        
        
        var Opt   = $("#IdFilterByZone option:selected").val(); 
        
        Opt      = isValue(Opt,-1,true)       
        
        $("#IdCardTitle").text("Calculando . . . ") 


        

        $.getJSON("getDashboard/"+Opt, { dt_ini: dt_Ini_, dt_end: dt_End_ }, function(dataset) {
            var data =  [
                {
                "INGRESO_NETO": "",
                "CAPITAL": "",
                "UTILIDAD_BRUTA": "",
                "CREDITOS_ACTIVOS" : "",
                "SALDO_CARTERA": "",
                "MORA_ATRASADA" : "",
                "MORA_VENCIDA" : ""
                }
                ];
        

            var Ingreso = dataset['INGRESO'];
        
            Ingreso     = numeral(isValue(Ingreso,0,true)).format('0,00.00');
        
            $("#lblIngreso").text(Ingreso)    
            data[0]['INGRESO_NETO'] = Ingreso;
        
            var CAPITAL = dataset['CAPITAL'];
        
            CAPITAL     = numeral(isValue(CAPITAL,0,true)).format('0,00.00');
            data[0]['CAPITAL'] = CAPITAL;
        
            $("#lblCapital").text(CAPITAL)
        
            var INTERESES = dataset['INTERESES'];
        
            INTERESES     = numeral(isValue(INTERESES,0,true)).format('0,00.00');
        
            $("#lblInteres").text(INTERESES)

            data[0]['UTILIDAD_BRUTA'] = INTERESES;
        
            var Clientes = dataset['clientes_activos'];
            Clientes     = numeral(isValue(Clientes,0,true)).format('0,00');
            $("#lblClientes").text(Clientes)
            data[0]['CREDITOS_ACTIVOS'] = Clientes;
        
            var Cartera = dataset['SALDOS_CARTERA'];
            
            Cartera     = numeral(isValue(Cartera,0,true)).format('0,00.00');
            
            $("#id_saldos_cartera").text(Cartera)
            data[0]['SALDO_CARTERA'] = Cartera;
        
        
            var MoraAtrasada = dataset['MORA_ATRASADA'];    
            
            MoraAtrasada     = numeral(isValue(MoraAtrasada,0,true)).format('0,00.00');    
            $("#lblMoraAtrasada").text(MoraAtrasada)
            data[0]['MORA_ATRASADA'] = MoraAtrasada;
        
            var MoraVencida = dataset['MORA_VENCIDA'];    
            MoraVencida     = numeral(isValue(MoraVencida,0,true)).format('0,00.00');    
            $("#lblMoraVencida").text(MoraVencida)
            data[0]['MORA_VENCIDA'] = MoraVencida;
        
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
                    mode: 'index',
                    intersect: true
                },
                hover: {
                    mode: 'index',
                    intersect: true
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

        

            tbMetricas(data)
        
        });

        
    }

    function tbMetricas(ARRAY_METRICAS) {
        if ( $.fn.DataTable.isDataTable('#tbl_metrias_home') ) {
        var dataTable = $('#tbl_metrias_home').DataTable();
        dataTable.clear().destroy();
        $("tbl_metrias_home").empty();
        }

        $('#tbl_metrias_home').DataTable({
            "data": ARRAY_METRICAS,
            "paging": false,
            "destroy": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "zeroRecords": "NO HAY COINCIDENCIAS",
                "paginate": {
                    "first": "Primera",
                    "last": "Última ",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "lengthMenu": "MOSTRAR _MENU_",
                "emptyTable": "-",
                "search": "BUSCAR"
            },
            "dom": 'lBfrtip',
            buttons: [{extend: 'excelHtml5'}],
            'columns': [
                {"title": "INGRESO NETO","data"   : "INGRESO_NETO"},                
                {"title": "CAPITAL","data"    : "CAPITAL"},
                {"title": "UTILIDAD BRUTA","data"    : "UTILIDAD_BRUTA"},
                {"title": "CREDITOS ACTIVOS","data"   : "CREDITOS_ACTIVOS"},
                {"title": "SALDO DE CARTERA","data"   : "SALDO_CARTERA"},
                {"title": "MORA ATRASADA","data"   : "MORA_ATRASADA"},
                {"title": "MORA VENCIDA","data"   : "MORA_VENCIDA"},
            ],
        });  
        $("#tbl_metrias_home_length").hide();
        $("#tbl_metrias_home_filter").hide();
        
        $("#tbl_metrias_home").hide();
    }

    
</script>