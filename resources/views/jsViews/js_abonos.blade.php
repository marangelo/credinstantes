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

        

       
        

    })

    function InitTable() {

        var slCli   = $("#id_select_cliente option:selected").val();  
        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val(); 

        slCli      = isValue(slCli,-1,true) 
        dtEnd      = isValue(dtEnd,'N/D',true) 
        dtIni      = isValue(dtIni,'N/D',true) 

        const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
        const dt_End = moment(dtEnd, 'DD/MM/YYYY');
        

        dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
        dt_End_ = dt_End.format('YYYY-MM-DD');


        $("#tbl_clientes").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "ajax":{
                "url": "getAbonos",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtIni   : dt_Ini_,
                    dtEnd   : dt_End_,
                    IdCln   : slCli,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            'columns': [
                { "title": "NOMBRE",              "data": "Nombre" },
                { "title": "APELLIDO",            "data": "apellido" },
                { "title": "FECHA CUOTA",       "data": "fecha_cuota" },
                {
                    "title": "CUOTA COBRADA",
                    "data": "cuota_cobrada",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "PAGO A CAPITAL",
                    "data": "pago_capital",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "PAGO A INTERES",
                    "data": "pago_intereses",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },                  
            ],
        })
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

</script>