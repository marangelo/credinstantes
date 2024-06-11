<script type="text/javascript">
    $(document).ready(function () {
        
        $('#btn-consolidado-add').click(function () {
            window.location.href = 'AddConsolidado';
        });

        $('#btn_filter').click(function () {      
            var select_year     = $('#select_year').val();
            
            window.location.href = "Consolidado?year=" + select_year ;
        });



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
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                var total = api.column(2, { page: 'current' }).data().reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                
                total = numeral(total).format('0,0.00');
                console.log(total);
                //$(api.column(3).footer()).html("C$. " + total);
            }
            });

            $(id+"_length").hide();
            $(id+"_filter").hide();
    }
</script>