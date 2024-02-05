<script type="text/javascript">

    $(document).ready(function () {
        CalcIndicadores();
    

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


        $.getJSON("getClientesDesembolsados/", function(dataset) {

            TableClientes(dataset);
        
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