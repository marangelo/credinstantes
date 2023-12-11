<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        //Initialize Select2 Elements
        $('.select2').select2()

        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_ingresos').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
    })

    function InitTable() {

        var slCli   = $("#id_select_cliente option:selected").val();  
        var slZna   = $("#id_select_zona option:selected").val();  
        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val(); 



        slCli      = isValue(slCli,-1,true) 
        slZna      = isValue(slZna,-1,true) 
        dtEnd      = isValue(dtEnd,'N/D',true) 
        dtIni      = isValue(dtIni,'N/D',true) 

        const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
        const dt_End = moment(dtEnd, 'DD/MM/YYYY');

        var lbl_titulo_reporte = 'Del ' + dt_Ini.format('ddd, MMM DD, YYYY') + ' Al ' + dt_End.format('ddd, MMM DD, YYYY')
        

        dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
        dt_End_ = dt_End.format('YYYY-MM-DD');
        
        $("#lbl_titulo_reporte").text(lbl_titulo_reporte)



    

        $("#tbl_ingresos").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "info": false,
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
                "url": "getAbonos",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtIni   : dt_Ini_,
                    dtEnd   : dt_End_,
                    IdCln   : slCli,
                    IdZna   : slZna,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            'columns': [
                
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {
                    
                    return row.Nombre + ' ' + row.apellido;
                }},
                {
                    "title": "INGRESO NETO",
                    "data": "cuota_cobrada",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "CAPITAL",
                    "data": "pago_capital",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "INTERESES",
                    "data": "pago_intereses",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },                  
            ],
            "fnDrawCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                };
                INGRESO = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                CAPITAL = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
                INTERES = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

               

                INGRESO         = numeral(isValue(INGRESO,0,true)).format('0,00.00')
                CAPITAL         = numeral(isValue(CAPITAL,0,true)).format('0,00.00')
                INTERES         = numeral(isValue(INTERES,0,true)).format('0,00.00')

                $('#id_lbl_ingreso').html(INGRESO);
                $('#id_lbl_capital').html(CAPITAL);
                $('#id_lbl_interes').html(INTERES);
            }
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }

    

</script>