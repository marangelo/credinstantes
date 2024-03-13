<script type="text/javascript">

    $(document).ready(function () {
        CalcIndicadores(1);
        $('[data-mask]').inputmask();
        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $('#tbl_cliente_promotor_buscar').on('keyup', function() {        
            var vTableFavorito = $('#tbl_cliente_promotor').DataTable();
            vTableFavorito.search(this.value).draw();
        });
        

        $("#IdFilterByZone").change(function() {    
            CalcIndicadores(0);
        });
        
    });

    function CalcIndicadores(Change){
    
        var vLabel = []
        var vData = []

        var Opt   = $("#IdFilterByZone option:selected").val(); 
        
        Opt      = isValue(Opt,-1,true)       
        
        //$("#IdCardTitle").text("Calculando . . . ") 

        $.getJSON("getDashboardPromotor/"+Opt, function(dataset) {

            if (Change > 0) {

                var CLIENTES_NUEVO = dataset['CLIENTES_NUEVO'];

                CLIENTES_NUEVO     = numeral(isValue(CLIENTES_NUEVO,0,true)).format('0.00');

                $("#lblClientesNuevos").text(CLIENTES_NUEVO);

                var RE_PRESTAMOS = dataset['RE_PRESTAMOS'];
                RE_PRESTAMOS     = numeral(isValue(RE_PRESTAMOS,0,true)).format('0.00');
                $("#lblRePrestamo").text(RE_PRESTAMOS);

                var SALDOS_COLOCADOS = dataset['SALDOS_COLOCADOS'];
                SALDOS_COLOCADOS     = numeral(isValue(SALDOS_COLOCADOS,0,true)).format('0,00.00');
                $("#lblSaldosColocados").text(SALDOS_COLOCADOS);
                
            }

            TableClientes(dataset['LISTA_CLIENTES']);
        
        
        
        })
    }
    function TableClientes(LISTA_CLIENTES) {
        $('#tbl_cliente_promotor').DataTable({
            "data": LISTA_CLIENTES,
            "paging": true,
            "destroy": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "order": [[2, 'desc']],
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
            'columns': [
                {"title": "#","data"  : "id_clientes"},
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {
                    return `<a href="Perfil/`+row.id_clientes+`"><strong>#`+row.id_clientes+` </strong> : `+ row.Nombre +` `+row.Apellidos+` </a> ` 
                }}, 
                {"title": "ACCION","data"       : "Accion"},                              
                {"title": "DEPARTAMENTO","data" : "Departamento"},
                {"title": "ZONA","data"         : "Zona"},
                {"title": "DIRECCION","data"    : "Direccion"},
               
            ],
            "columnDefs": [{"className": "", "targets": [ ]},],
        });  
        $("#tbl_cliente_promotor_length").hide();
        $("#tbl_cliente_promotor_filter").hide();
    }


</script>