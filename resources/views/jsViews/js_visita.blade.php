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

        $("#tbl_clientes_visita").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
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
            "ajax":{
                "url": "getVisitar",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    Fecha_   : currentDate,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            'columns': [
                { "title": "NOMBRE",              "data": "Nombre" },
                { "title": "APELLIDO",            "data": "apellido" },
                { "title": "DIRECCION",       "data": "direccion_domicilio" },
                { "title": "TELEFONO",       "data": "telefono" },
                {
                    "title": "CUOTA",
                    "data": "cuota",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {
                    "title": "SALDO PENDIENTE",
                    "data": "pendiente",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },
                {
                    "title": "SALDO",
                    "data": "saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                { "title": "ESTADO",  "data": "Estado", "render": function (data, type, row) {
                        if (data === "VENCIDO") {
                            return '<span class="text-danger">' + data + '</span>'; 
                        } else if (data === "EN MORA") {
                            return '<span class="text-primary">' + data + '</span>'; 
                        } else {
                            return data; 
                        }
                    }
                }        
            ],
            
           
        })
    }


</script>