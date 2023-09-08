<script type="text/javascript">
    $(document).ready(function () {
        //Money Euro
        $('[data-mask]').inputmask()

        $("#tbl_clientes").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tbl_clientes_wrapper .col-md-6:eq(0)');

    })

</script>