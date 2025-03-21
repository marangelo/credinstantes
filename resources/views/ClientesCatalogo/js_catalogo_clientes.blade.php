<script type="text/javascript">
    $(document).ready(function () {

        $('[data-mask]').inputmask()

        var table_garantias = $('#tbl_garantias').DataTable({
            "searching": false,
            "paging": false,
            "info": false,
            "filter": false,
            "columnDefs": [
                { "targets": [6], "visible": false }
            ],
            "language": {
                "emptyTable": "No hay datos para mostrar"
            }
        })

        var table_ref = $('#tbl_refencias').DataTable({
            "searching": false,
            "paging": false,
            "info": false,
            "filter": false,
            "columnDefs": [
                { "targets": [5], "visible": false }
            ],
            "language": {
                "emptyTable": "No hay datos para mostrar"
            }
        });


        $("#btn_filter_clientes").click(function() {    
            FiltrarCatClientes();
        });

        var count = table_garantias.data().length;
        $("#total_garantias").html(count);
        

        $("#btn-add-employee").click(function(){
            window.location.href = "AddCliente" ;
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_employee').DataTable();     
            vTableArticulos.search(this.value).draw();
        });

        initTable('#tbl_employee');


        $("#btn_guardar_info_cliente").click(function(){

            Save_Client();
            
        })

        $("#btn_add_garantias").click(function(){

            Add_Garantias(table_garantias);
            
        })

        $("#btn_add_referencia").click(function(){

            Add_Refencias(table_ref);

        });

        $("#btn_update_ref").click(function(){

            var nombre_ref = $("#edit_nombre_ref").val();
            var telefono_ref = $("#edit_telefono_ref").val();
            var direccion_ref = $("#edit_direcion_ref").val();
            var id_referencia = $("#edit_id_referencia").val();

            $.ajax({
                url: '../UpdateReferencia',
                method: 'POST',
                data: {
                    id_referencia   : id_referencia,
                    nombre_ref      : nombre_ref,
                    telefono_ref    : telefono_ref,
                    direccion_ref   : direccion_ref,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                            location.reload();
                            
                        })
                },
                error: function(xhr, status, error) {
                }
            });


        });
    });


    function Editar(id) {
        window.location ="FormClientes/" + id
    }

    function Remover(id, path){

        var url_path = (path ==='ref') ? '../rmReferencia' : '../rmGarantia' ;

        Swal.fire({
            title: '¿Estas Seguro de remover el registro  ?' + id,
            text: "¡Se removera la informacion permanentemente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!',
            target: document.getElementById('mdlMatPrima'),
            showLoaderOnConfirm: true,
            preConfirm: () => {
                $.ajax({
                    url: url_path,
                    data: {
                        id_  : id,
                        _token  : "{{ csrf_token() }}" 
                    },
                    type: 'post',
                    async: true,
                    success: function(response) {
                        if(response){
                            Swal.fire({
                                title: 'Registro Removido Correctamente ' ,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'OK'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                    }
                                })
                            }
                        },
                    error: function(response) {
                        //Swal.fire("Oops", "No se ha podido guardar!", "error");
                    }
                    }).done(function(data) {
                        //CargarDatos(nMes,annio);
                        location.reload();
                    });
                },
            allowOutsideClick: () => !Swal.isLoading()
        });

    }


    function initTable(id){
        $(id).DataTable({
        "destroy": true,
        "info": false,
        "bPaginate": true,
        "responsive": true, 
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
            {
                "targets": [],
                "visible": false,
                "searchable": false
            }
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
        });

        $(id+"_length").hide();
        $(id+"_filter").hide();
    }

    function Add_Garantias(table) {

        // Obtener los valores de los inputs
        var detalles = $("#detalles").val();
        var marca = $("#marca").val();
        var color = $("#color").val();
        var valor = $("#valor").val();
        var count = $("#total_garantias").html();

        // Verificar que los campos no estén vacíos
        if (!detalles || !marca || !color || !valor) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        // Incrementar el contador
        count = parseInt(count) + 1;    

        // Agregar la fila a la tabla
        var row = table.row.add([
            count,
            detalles,
            marca,
            color,
            valor,
            " - ",
            "S"
        ]).draw(false).node();

        $("#detalles, #marca, #color, #valor").val("");
        $("#total_garantias").html(count);
    }

    function Add_Refencias(table) {

        // Obtener los valores de los inputs
        var nombre_red      = $("#id_nombre_ref").val();
        var telefono_ref    = $("#id_telefono_ref").val();
        var direccion_red   = $("#id_direcion_ref").val();

        var count = table.data().length;

        // Verificar que los campos no estén vacíos
        if (!nombre_red || !telefono_ref || !direccion_red) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        // Incrementar el contador
        count = parseInt(count) + 1;    

        // Agregar la fila a la tabla
        var row = table.row.add([
            count,
            nombre_red,
            telefono_ref,
            direccion_red,
            " - ",
            "S"
        ]).draw(false).node();

        $("#id_nombre_ref, #id_telefono_ref, #id_direcion_ref").val("");

    }

    function Save_Client(){
        
        var Info_general = {};
        var Info_negocio = {};
        var Info_conyugue = {};
        var Info_garantias = {};
        var Info_referencias = {};

        var id_clientes = $("#id_clientes").html();

        var table_garantia = $('#tbl_garantias').DataTable();
        var rows_garantia  = table_garantia.rows().data().toArray();

        var tbl_referencias = $('#tbl_refencias').DataTable();
        var rows_referencias  = tbl_referencias.rows().data().toArray();

        $.each($("#frm_info_cliente").serializeArray(), function (i, field) {
            Info_general[field.name] = field.value;
        });

        $.each($("#frm_info_cliente_negocio").serializeArray(), function (i, field) {
            Info_negocio[field.name] = field.value;
        });

        $.each($("#frm_info_conyugue").serializeArray(), function (i, field) {
            Info_conyugue[field.name] = field.value;
        });

        $.each(rows_garantia, function (i, field) {
            if(field[6] == "S"){
                Info_garantias[i] = {
                    "detalle_articulo"  : field[1],
                    "marca"             : field[2],
                    "color"             : field[3],
                    "valor_recomendado" : field[4]
                };
            }
        });

        $.each(rows_referencias, function (i, field) {
            if(field[5] == "S"){
                Info_referencias[i] = {
                    "nombre_ref"    : field[1],
                    "telefono_ref"  : field[2],
                    "direccion_ref" : field[3]
                };
            }
        });

        $.ajax({
            url: '../UpdateCliente',
            method: 'POST',
            data:{
                id_clientes : id_clientes,
                iGeneral    : Info_general,
                iNegocio    : Info_negocio,
                iConyugue   : Info_conyugue,
                iGarantias  : Info_garantias,
                iReferencias: Info_referencias,
                _token          : "{{ csrf_token() }}" 
            },
            success: function(response) {
                Swal.fire({
                    title: response.message,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                    }).then((result) => {
                        
                    })
            },
            error: function(xhr, status, error) {
            }
        });


    }

    function Editar_Referencias(id_ref) {
        var obj = document.querySelector('#modal_edit_referencias');
        var modal = new window.bootstrap.Modal(obj);

        $("#edit_id_referencia").val(id_ref.id_referencia);
        $("#edit_nombre_ref").val(id_ref.nombre_ref);
        $("#edit_telefono_ref").val(id_ref.telefono_ref);
        $("#edit_direcion_ref").val(id_ref.direccion_ref);

        modal.show();
    }


    function FiltrarCatClientes(){
            
            var id_zona = $("#IdFilterByZone").val();
    
            $.ajax({
                url: '../FiltrarClientes',
                method: 'POST',
                data:{
                    id_zona : id_zona,
                    _token  : "{{ csrf_token() }}" 
                },
                success: function(dta_Clientes) {
                    TableFilterClientes(dta_Clientes);
                    
                },
                error: function(xhr, status, error) {
                }
            });
    }

    function TableFilterClientes(LISTA_CLIENTES) {
        $('#tbl_employee').DataTable({
            "data": LISTA_CLIENTES,
            "paging": true,
            "destroy": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "lengthMenu": [
                [7, -1],
                [7, "Todo"]
            ],
            "order": [
                [0, "asc"]
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
            'columns': [
                {"title": "NOMBRE Y APELLIDOS","data": "nombre", "render": function(data, type, row, meta) {  
                    return `<div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ asset('/img/user-01.png') }}" alt="user image">
                                <span class="username"> <span class="text-green">[ ` + row.id_clientes + ` ] |</span>
                                    <a href="FormClientes/`+row.id_clientes+`"> ` + row.nombre.toUpperCase() +`  ` + row.apellidos.toUpperCase() + ` </a>                                 
                                </span>
                                <span class="description text-white">`+ row.direccion_domicilio.toUpperCase() +`</span>
                            </div>`
                }},
                {"title": "ESTADO","data"   : "estado"},
                {"title": "CICLOS","data"   : "ciclo"},
                {"title": "TELEFONO","data"   : "telefono"},
                {"title": "CEDULA","data"   : "cedula"},
                {"title": "ACCION","data"   : "acciones"}
            ],
        });  

        $("#tbl_employee_length").hide();
        $("#tbl_employee_filter").hide();
    }




    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }


</script>