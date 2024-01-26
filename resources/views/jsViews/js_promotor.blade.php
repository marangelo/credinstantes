<script type="text/javascript">

    $(document).ready(function () {

        $('#tbl_cliente_promotor').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        $('#tbl_cliente_promotor_buscar').on('keyup', function() {        
            var vTableFavorito = $('#tbl_cliente_promotor').DataTable();
            vTableFavorito.search(this.value).draw();
        });


        $("#tbl_cliente_promotor_length").hide();
        $("#tbl_cliente_promotor_filter").hide();
        
    })


</script>