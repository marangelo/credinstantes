<script type="text/javascript">
    $(document).ready(function () {

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
                
                //'1 Año': [moment().subtract(1, 'year'), moment()],
                // '2 Años': [moment().subtract(2, 'year'), moment()],
                // '3 Años': [moment().subtract(3, 'year'), moment()]
                },
            "showCustomRangeLabel": false,
            "alwaysShowCalendars": true,
            "startDate": moment().format('D MMM. YYYY'),
            "endDate": moment().format('D MMM. YYYY'),
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
            InitTable() 
        });

        InitTable();

        $('.select2').select2()
        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })
        $("#btn-add-arqueo").click(function(){
            var Zona_Name   = $("#id_select_zona option:selected").text();  
            
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_ingresos').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
    })

    function InitTable() {

        var slZna   = $("#id_select_zona option:selected").val();  

        var picker = $('input[name="dt_range"]').data('daterangepicker');

        var dt_Ini  = picker.startDate.format('YYYY-MM-DD');
        var dt_End  = picker.endDate.format('YYYY-MM-DD');  

        var dtIniLbl  = picker.startDate.format('ddd, MMM DD, YYYY');
        var dtEndLbl  = picker.endDate.format('ddd, MMM DD, YYYY');        
        



        slZna      = isValue(slZna,-1,true) 
        dtEnd      = isValue(dt_End,'N/D',true) 
        dtIni      = isValue(dt_Ini,'N/D',true) 

        var lbl_titulo_reporte = 'Del ' + dtIniLbl + ' Al ' + dtEndLbl;
        

        $("#lbl_titulo_reporte").text(lbl_titulo_reporte)
        

        $("#tbl_ingresos").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "info": false,
            order: [[0, 'desc']],
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
            "ajax":{
                "url": "getBitacora",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtIni   : dt_Ini,
                    dtEnd   : dt_End,
                    IdZna   : slZna,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            "columnDefs": [
                {"className": "dt-center", "targets": [1,2,5,8,9,10,11]},
                {"className": "dt-right", "targets": [4,6,7]},
                { "width": "8%", "targets": [] },
                { "width": "12%", "targets": [  ] },
                { "visible":false, "searchable": false,"targets": [0] }
            ],
            'columns': [
                {
                    "title": "#",
                    "data": "Id"
                },                 
                {"title": "ORIGEN","data": "Origen"},
                {
                    "title": "FECHA",
                    "data": "fecha_arqueo",
                }, 
                {
                    "title": "NOMBRE COMPLETO",
                    "data": "Nombre",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "MONTO",
                    "data": "Monto",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },      
                {
                    "title": "PLAZO",
                    "data": "Plazo",
                    render: $.fn.dataTable.render.number(',', '.', 0, '')
                },  
                {
                    "title": "CUOTA",
                    "data": "Cuota",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {
                    "title": "SEGURO",
                    "data": "Seguro",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {
                    "title": "DIA DE VICITA",
                    "data": "IdSemana"
                },
                {
                    "title": "N DE COMRPOBANTE",
                    "data": "Id",
                },
                {
                    "title": "TASA DE %",
                    "data": "Interes",
                },
                {
                    "title": "ZONA",
                    "data": "Zona",
                },
                
                
                
            ],
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }

    function ExportarBitacora()
    {
        var slZna   = $("#id_select_zona option:selected").val();  

        var picker = $('input[name="dt_range"]').data('daterangepicker');

        var dt_Ini  = picker.startDate.format('YYYY-MM-DD');
        var dt_End  = picker.endDate.format('YYYY-MM-DD');  

        slZna      = isValue(slZna,-1,true) 
        dtEnd      = isValue(dt_End,'N/D',true) 
        dtIni      = isValue(dt_Ini,'N/D',true) 

        var url = 'ExportarBitacora/' + dtIni + '/' + dtEnd + '/' + slZna;

        window.open(url, '_blank');
    }
    

    

</script>