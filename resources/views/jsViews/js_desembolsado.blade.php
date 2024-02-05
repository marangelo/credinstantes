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
                {"title": "#","data"  : "id_clientes"},
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {
                    return `<a href="Perfil/`+row.id_clientes+`"><strong>#`+row.id_clientes+` </strong> : `+ row.Nombre +` `+row.Apellidos+` </a> ` 
                }},                               
                {"title": "DEPARTAMENTO","data" : "Departamento"},
                {"title": "ZONA","data"         : "Zona"},
                {"title": "DIRECCION","data"    : "Direccion"},
                {"title": "ACCION","data"       : "Accion"},
            ],
            "columnDefs": [{"className": "", "targets": [ ]},],
        });  
        $("#tbl_cliente_promotor_length").hide();
        $("#tbl_cliente_promotor_filter").hide();
    }


</script>