<script type="text/javascript">
    $(document).ready(function () {
        InitTable();

        $("#btn-buscar-morosidad").click(function(){
            InitTable();
        })

   
        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        


        

    })

    function InitTable() {

        $("#tbl_clientes_morosos").DataTable({
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
            },
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', footer: true, customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                     // Loop over the cells in column `C`
                    // $('row c[r^="I"]', sheet).each( function () {
                    //     // Get the value
                    //     if ( $('is t', this).text() == 'AL DIA' ) {
                    //         $(this).attr( 's', '20' );
                    //     }
                    // });
                } },
            ],
            footer: true,
            initComplete: function() {
                $('.buttons-excel').html('Exportar  <i class="fa fa-file-excel" />')
            },
            "ajax":{
                "url": "getMorosidad",
                "type": 'POST',
                'dataSrc': '',
                "data": {               
                    _token  : "{{ csrf_token() }}" 
                }
            },
            'columns': [
                {"title": "NOMBRE","data": "nombre", "render": function(data, type, row, meta) {
                    return `<a href="Perfil/`+ row.IdCliente +`" ><strong># `+ row.IdCliente +` </strong> : `+ row.nombre +`  : `+ row.apellidos +` </a> `
                    
                }},
                { "title": "ESTADO",            "data": "Estado" },
            ],
        })
        $("#tbl_clientes_length").hide();
        $("#tbl_clientes_filter").hide();
    }


</script>