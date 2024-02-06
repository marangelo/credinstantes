<script type="text/javascript">

    $(document).ready(function () {
        CalcIndicadores();
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
        
    });

    function CalcIndicadores(){
    
        var vLabel = []
        var vData = []

        var Opt   = $("#IdFilterByZone option:selected").val(); 
        
        Opt      = isValue(Opt,-1,true)       
        
        //$("#IdCardTitle").text("Calculando . . . ") 

        $.getJSON("getMetricasPromotor/"+Opt, function(dataset) {
        
            var CLIENTES_NUEVO = dataset['CLIENTES_NUEVO'];

            CLIENTES_NUEVO     = numeral(isValue(CLIENTES_NUEVO,0,true)).format('0.00');
        
            $("#lblClientesNuevos").text(CLIENTES_NUEVO);

            var RE_PRESTAMOS = dataset['RE_PRESTAMOS'];
            RE_PRESTAMOS     = numeral(isValue(RE_PRESTAMOS,0,true)).format('0.00');
            $("#lblRePrestamo").text(RE_PRESTAMOS);
        
            var SALDOS_COLOCADOS = dataset['SALDOS_COLOCADOS'];
            SALDOS_COLOCADOS     = numeral(isValue(SALDOS_COLOCADOS,0,true)).format('0,00.00');
            $("#lblSaldosColocados").text(SALDOS_COLOCADOS);

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