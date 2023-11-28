<script type="text/javascript">
    $(document).ready(function () {

        var currentDate = moment().format("YYYY-MM-DD");
        
        InitTable(currentDate);

        $('[data-mask]').inputmask()

        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        

        $("#btn-buscar").click(function(){
            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');
            dtVisita = fechaAnalizada.format('YYYY-MM-DD')
            InitTable(dtVisita);
        })


        

    })

    function InitTable(currentDate) {

        $("#lbl_visitar").text(currentDate)

        var DiaW_  = $("#IdDiaW option:selected").val();  
        var Zona_  = $("#IdZona option:selected").val();  

        $("#tbl_clientes_visita").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "lengthMenu": [[100,200,300,400,-1], [100,200,300,400,"Todo"]],
            "order": [[ 8, 'asc' ]],
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
                "url": "getVisitar",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    Fecha_  : currentDate,
                    DiaW_   : DiaW_,
                    Zona_   : Zona_,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            "columnDefs": [
                {"className": "dt-left", "targets": [1 ]},
                {"className": "dt-center", "targets": [0,3,4,8 ]},
                {"className": "dt-right", "targets": [5,6,7]},
                {"width":"20%","targets":[2]},
                {"width":"5%","targets":[]}
            ],
            'columns': [
                { "title": "#",            "data": "id_pagoabono" },
                { "title": "NOMBRE",            "data": "Nombre" },
                { "title": "DIRECCION",         "data": "direccion_domicilio" },
                { "title": "ZONA",              "data": "zona" },
                { "title": "TELEFONO",          "data": "telefono" },
                {
                    "title": "CUOTA",
                    "data": "cuota",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {
                    "title": "PENDIENTE",
                    "data": "pendiente",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },
                {
                    "title": "SALDO",
                    "data": "saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                { "title": "ESTADO",  "data": "Estado", } ,      
            ],
            drawCallback: function () {
                var api = this.api();

                var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

                ttCuota = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                ttSaldo = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                $( api.column( 5 ).footer() ).html(
                    'C$ ' + numeral(isValue(ttCuota,0,true)).format('0,00.00')
                );
                $( api.column( 7 ).footer() ).html(
                    'C$ ' + numeral(isValue(ttSaldo,0,true)).format('0,00.00')
                );
            },
            "createdRow": function (row, data, dataIndex) {              
                // if (data.today === 1) {
                //     $(row).addClass("bg-success");
                // }
                // if (data.Estado === 'EN MORA') {
                //     $(row).addClass("bg-warning");
                // }

                // if (data.Estado === 'VENCIDO') {
                //     $(row).addClass("bg-danger");
                // }
            }
        })
        $("#tbl_clientes_visita_filter").hide();
    }


</script>