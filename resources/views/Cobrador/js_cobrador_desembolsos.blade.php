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

        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val(); 


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
                "url": "getDesembolsados",
                "type": 'POST',
                dataSrc: function(json) {

                    $("#lblClientesNuevos").text(json.CLIENTES_NUEVOS);
                    $("#lblRePrestamo").text(json.REPRESTAMOS);
                    $("#lblReactivacionesValue").text(json.CALC_REACT);
                    $("#lblSaldosColocados").text(json.SALDOS_COLOCADOS);
                    $("#lblCountReact").text(json.CountReact);

                    return json.dtClientes; 
                },
                "data": {                
                    dtIni   : dt_Ini_,
                    dtEnd   : dt_End_,
                    _token  : "{{ csrf_token() }}" 
                }
            },
        buttons: [{extend: 'excelHtml5'}],
            'columns': [                
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {
                    
                    return '[ ' + row.id_clientes + ' ] - ' +row.Nombre ;
                }},
                {"title": "DEPARTAMENTO","data": "Departamento"},
                {"title": "ZONA","data": "Zona"},
                {"title": "DIRECCION","data": "Direccion"},
                {
                    "title": "FECHA",
                    "data": "Fecha"
                }, 
                {
                    "title": "MONTO",
                    "data": "Monto",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "ORIGEN",
                    "data": "Origen"
                },
            ],
            "createdRow": function (row, data, dataIndex) {              
                
            },
            "footerCallback": function (row, data, start, end, display) {
            }
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }

    

</script>