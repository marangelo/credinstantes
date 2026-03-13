<script type="text/javascript">

    $(document).ready(function () {
        
        $('[data-mask]').inputmask();
        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $('#tbl_cliente_promotor_buscar').on('keyup', function() {        
            var vTableFavorito = $('#tbl_cliente_promotor').DataTable();
            vTableFavorito.search(this.value).draw();
        });
        

        $("#IdFilterByZone").change(function() {    
            CalcIndicadores();
        });


        $('input[name="dt_range"]').daterangepicker({
            "autoApply": true,
                ranges: {
                'Hoy': [moment(), moment()],
                'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
                'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
                
                'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
                'Semana Anterior': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                
                'Este Mes': [moment().startOf('month'), moment()],
                'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                },
            "showCustomRangeLabel": false,
            "alwaysShowCalendars": true,
            "startDate": moment().startOf('month').format('D MMM. YYYY'),
            "endDate":moment().format('D MMM. YYYY'),
            opens: 'left',
            locale: {
                //format: "DD/MM/YYYY",
                format: "D MMM. YYYY",   // Ejemplo: 1 ago. 2025
                separator: " - ",
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                fromLabel: "Desde",
                toLabel: "Hasta",
                customRangeLabel: "Personalizado",
                weekLabel: "S",
                daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
                monthNames: [
                    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ],
                firstDay: 1
            }
        }, function(start, end, label) {
            CalcIndicadores();
        });

        CalcIndicadores();
        
    });

    function CalcIndicadores(){
    
        var vLabel = []
        var vData = []

        var Opt   = $("#IdFilterByZone option:selected").val(); 


        var picker = $('input[name="dt_range"]').data('daterangepicker');
        var dt_Ini  = picker.startDate.format('YYYY-MM-DD');
        var dt_End  = picker.endDate.format('YYYY-MM-DD');  

        var dtIniLbl  = picker.startDate.format('ddd, MMM DD, YYYY');
        var dtEndLbl  = picker.endDate.format('ddd, MMM DD, YYYY');  
        var lbl_titulo_reporte = 'Periodo del ' + dtIniLbl + ' al ' + dtEndLbl;
        
        Opt      = isValue(Opt,-1,true)       
        
        $("#lbl_titulo").text(lbl_titulo_reporte) 

        $.ajax({
            url: "getMetricasPromotor/",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                Prom: Opt,
                dtIni: dt_Ini,
                dtEnd: dt_End
            },
            dataType: "json",
            success: function(dataset) {
                var CLIENTES_NUEVO = dataset['CLIENTES_NUEVO'];

                CLIENTES_NUEVO     = numeral(isValue(CLIENTES_NUEVO,0,true)).format('0.00');
            
                $("#lblClientesNuevos").text(CLIENTES_NUEVO);

                var RE_PRESTAMOS = dataset['RE_PRESTAMOS'];
                RE_PRESTAMOS     = numeral(isValue(RE_PRESTAMOS,0,true)).format('0.00');
                $("#lblRePrestamo").text(RE_PRESTAMOS);
            
                var SALDOS_COLOCADOS = dataset['SALDOS_COLOCADOS'];
                SALDOS_COLOCADOS     = numeral(isValue(SALDOS_COLOCADOS,0,true)).format('0,00.00');
                $("#lblSaldosColocados").text(SALDOS_COLOCADOS);
                
                var COUNT_REACT = dataset['COUNT_REACT'];
                
                $("#lblCountReact").text(COUNT_REACT);

                TableClientes(dataset['LISTA_CLIENTES']);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }
    $('.button_export_excel').click(() => {
        $('#tbl_cliente_promotor').DataTable().buttons(0,0).trigger()
    })
    function TableClientes(LISTA_CLIENTES) {
        $('#tbl_cliente_promotor').DataTable({
            "data": LISTA_CLIENTES,
            "paging": true,
            "destroy": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
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
            buttons: [{extend: 'excelHtml5'}],
            'columns': [
                {"title": "NOMBRE","data"   : "Nombre"},                
                {"title": "FECHA","data"    : "Fecha"},
                {"title": "MONTO","data"    : "Monto"},
                {"title": "ORIGEN","data"   : "Origen"},
            ],
            "columnDefs": [{"className": "", "targets": [ ]},],
        });  
        $("#tbl_cliente_promotor_length").hide();
        $("#tbl_cliente_promotor_filter").hide();
    }


</script>