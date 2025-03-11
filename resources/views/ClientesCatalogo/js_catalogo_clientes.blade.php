<script type="text/javascript">
    $(document).ready(function () {

        $('[data-mask]').inputmask()

        var table = $('#tbl_garantias').DataTable({
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
        var count = table.data().length;
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

            Add_Garantias(table);
            
        })
    });


    function Editar(id) {
        window.location ="FormClientes/" + id
    }

    function Remover(id){
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
                    url: "../rmGarantia",
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

    function Save_Client(){
        
        var Info_general = {};
        var Info_negocio = {};
        var Info_conyugue = {};
        var Info_garantias = {};

        var id_clientes = $("#id_clientes").html();

        var table = $('#tbl_garantias').DataTable();
        var rows  = table.rows().data().toArray();

        $.each($("#frm_info_cliente").serializeArray(), function (i, field) {
            Info_general[field.name] = field.value;
        });

        $.each($("#frm_info_cliente_negocio").serializeArray(), function (i, field) {
            Info_negocio[field.name] = field.value;
        });

        $.each($("#frm_info_conyugue").serializeArray(), function (i, field) {
            Info_conyugue[field.name] = field.value;
        });

        $.each(rows, function (i, field) {
            if(field[6] == "S"){
                Info_garantias[i] = {
                    "detalle_articulo"  : field[1],
                    "marca"             : field[2],
                    "color"             : field[3],
                    "valor_recomendado" : field[4]
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

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }


</script>