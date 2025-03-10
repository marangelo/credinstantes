<script type="text/javascript">
    $(document).ready(function () {

        $("#btn-add-employee").click(function(){
            window.location.href = "AddCliente" ;
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_employee').DataTable();     
            vTableArticulos.search(this.value).draw();
        });

        initTable('#tbl_employee');
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
                    url: "rmEmployee",
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

</script>