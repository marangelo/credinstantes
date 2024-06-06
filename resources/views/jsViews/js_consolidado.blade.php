<script type="text/javascript">
    $(document).ready(function () {



        initializeDataTable('#tbl_consolidado');
    });
    function initializeDataTable(id) {
        $(id).DataTable({
            "destroy": true,
            "info": false,
            "bPaginate": false,
            "responsive": false, 
            "columnDefs": [
                { "targets": 0, "visible": false }
            ],
            "order": [
                [0, "asc"]
            ],
            "lengthMenu": [
                [25, -1],
                [25, "Todo"]
            ],
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
            });

            $(id+"_length").hide();
            $(id+"_filter").hide();
    }
</script>