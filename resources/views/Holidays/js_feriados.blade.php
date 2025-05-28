<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        $('#dtFeriado').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $("#btn_filtrar_feriados").click(function(){
            InitTable();
        })

        $("#btn-add_feriado").click(function(){
            $("#accion_form").html('Nuevo');
            $("#IdDtFeriado").text("0");


            $("#txt_descripcion").val('');
            $('#modal-lg').modal('show');          
        })

        $("#btn_save_feriado").click(function(){
            SaveHoliday();
        })
         $("#btn-UpdateHoliday").click(function(){
            UpdateHoliday();
        })

       
    })
   
    function InitTable() {

        var year_feriados   = $("#id_feriado_year option:selected").val();  

        $("#tbl_feriados").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "info": false,
            "searching": false,
            "paging": false,
            "order": [[1, 'asc']],
            
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
                "url": "HolidaysList",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtFeriados   : year_feriados,                  
                    _token  : "{{ csrf_token() }}" 
                }
            },
            'columns': [
                {
                    "title": "ID",
                    "data": "id_holiday"
                },
                
                {
                    "title": "FECHA FRIADA",
                    "data": "date_holiday"
                },
                {
                    "title": "DESCRIPCION",
                    "data": "Description"
                },
                {
                    "title": "ACCIONES",
                    "data": "id_holiday",
                    "render": function (data, type, row) {
                        return  '<button class="btn btn-primary btn-sm" onclick="EditHoliday(' + data + ')"><i class="fas fa-edit"></i> Editar</button> ' +
                                '<button class="btn btn-danger btn-sm" onclick="DeleteHoliday(' + data + ')"><i class="fas fa-trash"></i> Eliminar</button>';
                    }
                }

                
            ]
            
        })
    }

    function DeleteHoliday(id) {
        if (confirm("¿Está seguro de eliminar este feriado?")) {
            $.ajax({
                type: "POST",
                url: "HolidaysDelete",
                data: {
                    id_holiday: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data) {
                        Swal.fire({
                        title: "Feriado eliminado correctamente.",
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
                        
                    } else {
                        alert("Error al eliminar el feriado.");
                    }
                },
                error: function () {
                    alert("Error al realizar la solicitud.");
                }
            });
        }
    }

    function EditHoliday(id) {
        $("#accion_form").html('Editar');
        $.ajax({
            type: "POST",
            url: "HolidaysEdit",
            data: {
                id_holiday: id,
                _token: "{{ csrf_token() }}"
            },
            success: function (data) {
                if (data) {
                    $("#txt_descripcion").val(data.Description);
                    $("#IdFecha_Feriado").val(data.date_holiday);
                    $("#IdDtFeriado").text(data.id_holiday);
                    $('#modal-lg').modal('show');
                } else {
                    alert("Error al cargar los datos del feriado.");
                }
            },
            error: function () {
                alert("Error al realizar la solicitud.");
            }
        });
    }

    function SaveHoliday() {
        var IdDtFeriado = $("#IdDtFeriado").text();
        var IdFecha_Feriado = $("#IdFecha_Feriado").val();
        var txt_descripcion = $("#txt_descripcion").val();

        _Fecha          = isValue(IdFecha_Feriado,'N/D',true);  
        Descripcion    = isValue(txt_descripcion,'N/D',true);

        $.ajax({
            url: "HolidaysSave",
            type: 'post',
            data: {
                _Fecha          : _Fecha,
                _Descripcion    : Descripcion,
                _IdDtFeriado    : IdDtFeriado,
                _token          : "{{ csrf_token() }}" 
            },
            async: true,
            success: function(response) {
                if(response){
                    Swal.fire({
                    title: 'Gasto Guardado!',
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
                Swal.fire("Oops", "No se ha podido guardar!", "error");
            }
        })
    }

    function UpdateHoliday() {

        var year_feriados = {};
        $("#id_feriado_year option").each(function(){
            year_feriados[$(this).val()] = $(this).text();
        });


        Swal.fire({
            title: "El Año a actualizar ",
            input: "select",
            inputOptions: year_feriados,
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Look up",
            showLoaderOnConfirm: true,
            preConfirm: async (login) => {
                try {
                    const UpdateHoliday = `{{ url('UpdateHoliday/${login}') }}`;
                    const response = await fetch(UpdateHoliday);
                    if (!response.ok) {
                        return Swal.showValidationMessage(`${JSON.stringify(await response.json())}`);
                    }
                    return response.json();
                } catch (error) {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            },
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    text: `Nuevos feriados del ${result.value.year} Cread correctamente.`,
                    title: `${result.value.message}`,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });0
            }0
            });
    }

    

</script>