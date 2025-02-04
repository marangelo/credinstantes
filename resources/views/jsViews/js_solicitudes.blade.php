<script type="text/javascript">
    $(document).ready(function () {

        InitTable();
    
        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_ingresos').DataTable();     
            vTableArticulos.search(this.value).draw();
        });

    })
    $('.button_export_excel').click(() => {
        $('#tbl_ingresos').DataTable().buttons(0,0).trigger()
    })
    function ModalHistorico(IdCliente){
        
        $("#lbl_id_cliente").html(IdCliente);
        $('#modal-historico').modal('show');

        $.get( "getCreditos/" + IdCliente, function( data ) {
            
            initTable_historico('#tbl_creditos',data);

            $("#lbl_nombre_cliente").html(data[0].Nombre + ' ' + data[0].Apellidos);

        });
    }

    function initTable_historico(id,InfoCreditos){
        
        var tabla = $(id).DataTable({
            "data": InfoCreditos[0].Creditos,
            "destroy": true,
            "info": false,
            responsive: true,
            "bPaginate": false,
            "searching": false,
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [5, -1],
                [5, "Todo"]
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
            'columns':  [ 
                {"title": "NUM","data": "id_creditos"},  
                {"title": "ESTADO","data": "id_creditos", "render": function(data, type, row, meta) {
                    const estilo = {
                        '1' : 'bg-success',
                        '2' : 'bg-danger',
                        '3' : 'bg-warning'
                    }
                    const clase = estilo[row.estado_credito] || ''

                    var nombre_estado = _.findWhere(InfoCreditos[0].Estados_credito, {id_estados: row.estado_credito }).nombre_estado;

                    return `<span class="badge ${clase}">${nombre_estado}</span>`
                }}, 
                {"title": "FECHA INICIO","data": "id_creditos", "render": function(data, type, row, meta) {
                    const dt = moment(row.fecha_apertura, 'YYYY/MM/DD HH:mm:ss').format('ddd, MMM DD, YYYY');  
                    return `<span class="badge rounded-pill badge-soft-info ">`+ dt	  +`</span> `
                }},
                {"title": "FECHA FINAL","data": "id_creditos", "render": function(data, type, row, meta) {
                    
                    const dt = moment(row.fecha_ultimo_abono, 'YYYY/MM/DD HH:mm:ss').format('ddd, MMM DD, YYYY');

                    return `<span class="badge rounded-pill badge-soft-info ">`+ dt +`</span> `
                }},
                {"title": "TOTAL","data": "total", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">`+ numeral(row.total).format('0,00.00') +`</span> `                    
                }},
                {"title": "SALDO ","data": "saldo", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info text-success">C$  `+ numeral(row.saldo).format('0,00.00')  +`</span> `
                }},
                {"title": " - ","data": "id_creditos", "render": function(data, type, row, meta) {
                    return `<a href="CreditoPrint/${row.id_creditos}" class="btn btn-success btn-block btn-sm" onclick=""><i class="fas fa-print"></i> </a> `
                }},

            ],
        });
        $( id + "_length").hide();
        
    }

    function InitTable() {

        var slZna   = $("#id_select_zona option:selected").val();  
        slZna       = isValue(slZna,-1,true) 
        

        $("#tbl_ingresos").DataTable({
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
            
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "-",
            "search": "BUSCAR"
            },
            "ajax":{
                "url": "{{ route('getSolicitudes')}}",
                "type": 'POST',
                'dataSrc': '',
                "data": {
                    IdZna   : slZna,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            'columns': [
                
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {                   
                    return '[ ' + row.id_clientes + ' ] - ' +row.Nombre + ' ' + row.apellido  ;
                }},
                {"title": "CEDULA","data": "Cedula"},
                {"title": "DIRECCION","data": "Direccion"},
                {"title": "TELEFONO","data": "Telefono"},
                {"title": "ZONA","data": "Zona"},
                {"title": "FECHA","data": "Fecha_registro"},
                {"title": "MONTO","data": "Monto_promedio", "render": function(data, type, row, meta) {
                    return numeral(row.Monto_promedio).format('0,00.00');
                }},
                {"title": "CREADO POR","data": "Usuario"},
                {"title": "ESTADO","data": "Estado"},
                {
                    "title": "DOCUMENTOS",
                    "data": "Accion"
                }, 
                {
                    "title": "ACCION",
                    "data": "Botones"
                }, 
            ],
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    

</script>