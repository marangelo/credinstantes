<script type="text/javascript">
    $(document).ready(function () {

        var type_form = $("#lbl_titulo_reporte").text().replace(/ /g,'');

        InitTable(type_form);
    
        $("#btn-buscar-abonos").click(function(){
            InitTable(type_form);
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_ingresos').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
        $("#btn_open_modal_renovacion").click(function(){

            

            if(type_form == 'RENOVAR'){
                $('#modal-renovacion').modal('show');
                ModalRenovacion();
            }else{
                window.location.href = "Formulario/0";                
            }
        })

        $("#txt_search_renovaciones").on('keyup', function() {   
            var vTableInactivos = $('#tbl_renovaciones').DataTable();     
            vTableInactivos.search(this.value).draw();
        });

    })
    $('.button_export_excel').click(() => {
        $('#tbl_ingresos').DataTable().buttons(0,0).trigger()
    })
    function ModalRenovacion(){
        $('#modal-renovacion').modal('show');

        $.get( "getClientesInactivos", function( data ) {
            initTable_historico('#tbl_renovaciones',data.Clientes_Inactivos);
        });
    }

    function initTable_historico(id,Clientes_Inactivos){
        
        var tabla = $(id).DataTable({
            "data": Clientes_Inactivos,
            "destroy": true,
            "info": false,
            responsive: true,
            "bPaginate": true,
            "searching": true,
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [7, -1],
                [7, "Todo"]
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
                {"title": "NOMBRE","data": "nombre", "render": function(data, type, row, meta) {
                    return row.nombre.toUpperCase() + ' ' + row.apellidos.toUpperCase();
                }},  
                
                {"title": " - ","data": "id_clientes", "render": function(data, type, row, meta) {
                    return `<a href="CreditoRenovacion/${row.id_clientes}" class="btn btn-success btn-block btn-sm" onclick=""><i class="fas fa-print"></i> </a> `
                }},

            ],
        });

        $( id + "_length").hide();
        $( id + "_filter").hide();
        
    }

    function InitTable(type_form) {

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
                    tyForm  : type_form,
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