<script type="text/javascript">
$(document).ready(function () {
 
    var Selectors = {
        MODAL_REQUEST: '#modal_new_request',        
    };

  
    $("#list_employee").select2({
        dropdownParent: $("#modal_new_request")
    });

    $("#btn-add-employee").click(function(){
        window.location.href = "Employee" ;
    })


    $("#btn-add-nomina").click(function(){
        var obj = document.querySelector(Selectors.MODAL_REQUEST);
        var modal = new window.bootstrap.Modal(obj);
        $("#id_form").text("0");
        modal.show();
    });


    $('#date_ini').change(function() {
        CalcDiffDay()
    });

   
    $("#btn_save_request").click(function(){
            
            var employee  = $("#list_employee option:selected").val();  

            var date_ini        = $("#date_ini").val();   
            var date_end        = $("#date_end").val();   
            var date_return     = $("#date_return").val();
            var list_type       = $("#list_type").val();
            var cant_day        = $("#cant_day").val();
            var observation     = $("#observation").val();

            var IdRequest       = $("#id_form").text();

            IdRequest      = isValue(IdRequest,0,true)   
            employee      = isValue(employee,0,true)   

            date_ini      = isValue(date_ini,'N/D',true)   
            date_end      = isValue(date_end,'N/D',true)    
            date_return   = isValue(date_return,'N/D',true)    
            list_type     = isValue(list_type,'N/D',true)    
            cant_day      = isValue(cant_day,'N/D',true)    
            observation   = isValue(observation,'N/D',true)    
        

            if(employee == 0||  date_ini ==='N/D' || date_end === 'N/D' || list_type ==='N/D'){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{
                $.ajax({
                    url: "SaveRequest",
                    type: 'post',
                    data: {
                        IdRequest_      : IdRequest,
                        employee_       : employee,
                        date_ini_       : date_ini,
                        date_end_       : date_end,
                        date_return_    : date_return , 
                        list_type_      : list_type,
                        cant_day_       : cant_day,
                        observation_    : observation,
                        _token  : "{{ csrf_token() }}" 
                    },
                    async: true,
                    success: function(response) {
                        if(response){
                            Swal.fire({
                            title: 'Correcto',
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
                }).done(function(data) {
                    if(response){
                            Swal.fire({
                            title: 'Correcto',
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
                });

            
        }

    })




});


function CalcDiffDay(){
    var var_date_ini = $("#date_ini").val();
    var var_date_end = $("#date_end").val();
    var DiffDay = 0 ;

    var_date_ini = isValue(var_date_ini,'N/D',true);
    var_date_end = isValue(var_date_end,'N/D',true);
    
    if (var_date_ini != 'N/D' && var_date_end != 'N/D') {
        var fechaFin = moment(var_date_end);
        var fechaInicio = moment(var_date_ini);

        DiffDay = (fechaFin.diff(fechaInicio, 'days') + 1 ) * 1.18;    
    }
    $("#cant_day").val(numeral(DiffDay).format('0.00'))

    
}

function SaveData(Nombre,Modelo) {
    $.ajax({
        url: "SaveTypeRequest",
        data: {
            Nombre_: Nombre,
            Modelo_: Modelo,
            _token  : "{{ csrf_token() }}" 
        },
        type: 'post',
        async: true,
        success: function(response) {
            swal("Exito!", "Guardado exitosamente", "success");
        },
        error: function(response) {
            swal("Oops", "No se ha podido guardar!", "error");
        }
    }).done(function(data) {
        location.reload();
    });
}
function Remover(id,mdl){
    Swal.fire({
        title: '¿Estas Seguro de remover el registro  ?',
        text: "¡Se removera la informacion permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            $.ajax({
                url: "rmRequests",
                data: {
                    id_  : id,
                    mdl_  : mdl,
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

function edit(o) {

    var IsOK = isValue(o.type_name,'N/D',true);

    var varDescripcion = (IsOK === 'N/D') ? o.status_name : o.type_name ; 
    var varID = (IsOK === 'N/D') ? o.id_request_status : o.id_request_type ; 
    var varModelo = (IsOK === 'N/D') ? 2 : 1 ; 


    Swal.fire({
        title: varDescripcion,
        text: 'Editar registro',
        input: "text",
        inputPlaceholder: 'Valor a Ingresar',
        inputAttributes: {
            autocapitalize: "off"
        },
        showCancelButton: true,
        confirmButtonText: 'Editar',
        showLoaderOnConfirm: true,
        inputValue: varDescripcion,
        inputValidator: (value) => {
            if (!value) {
                return 'Campo no puede estar Vacio';
            }
            UpdateData(value,varModelo,varID)
        }
    })

}

function edit_request(o) {

    

    var obj = document.querySelector('#modal_new_request');
    var modal = new window.bootstrap.Modal(obj);

    $("#list_employee").val(o.employee_id).change();
    //$('#id_select_articulo').selectpicker('val', row.codigo);
    

    $("#list_type").val(o.request_type_id).change();
    $("#id_form").text(o.id_vacation_request);

    $("#date_ini").val(o.start_date);   
    $("#date_end").val(o.end_date);   
    $("#date_return").val(o.return_date);
    $("#cant_day").val(o.requested_days);
    $("#observation").val(o.observation);

    modal.show();

    


}

function UpdateData(Nombre,Modelo,ID) {
    $.ajax({
        url: "UpdateRequest",
        data: {
            ID_     : ID,
            Nombre_ : Nombre,
            Modelo_ : Modelo,             
            _token  : "{{ csrf_token() }}" 
        },
        type: 'post',
        async: true,
        success: function(response) {
            swal("Exito!", "Guardado exitosamente", "success");
        },
        error: function(response) {
            swal("Oops", "No se ha podido guardar!", "error");
        }
    }).done(function(data) {
        location.reload();
    });
}


function MSG(txt) {
    Swal.fire({
        title: txt,
        text: 'Nuevo registro',
        input: "text",
        inputPlaceholder: 'Valor a Ingresar',
        inputAttributes: {
            autocapitalize: "off"
        },
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        showLoaderOnConfirm: true,
        inputValue: $('#cantidad').text(),
        inputValidator: (value) => {
            if (!value) {
                return 'Campo no puede estar Vacio';
            }
            SaveData(value,txt)
        }
    })
}
</script>