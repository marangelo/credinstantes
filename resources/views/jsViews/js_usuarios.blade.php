<script type="text/javascript">
    $("#tbl_usrs").DataTable({
            "responsive": true, 
        "lengthChange": false, 
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
        "buttons": ["copy", "excel", "print"]
    }).buttons().container().appendTo('#tbl_clientes_wrapper .col-md-6:eq(0)');

    $("#btn_form_add_user").click(function(){
        // Limpiar los campos de entrada
        $("#txtFullName").val("");
        $("#txtUserName").val("");
        $("#txtPassWord_one").val("");
        $("#txtPassWord_two").val("");
        $("#txtComentario").val("");

        // Restablecer el select a su valor predeterminado (1 en este caso)
        $("#sclPrivi").val(1);

        // Llamar a la función para guardar el formulario
        $('#modal_xl_add_user').modal('show');     
        $("#id_estado").html(0);   
    });
    function Remove_form(IdElem){

        var vTable  = "users" ; 
        var nmCamp  = "id" ; 

        Swal.fire({
            title: '¿Estas Seguro de remover el registro  ?',
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
                    url: "rmElem",
                    data: {
                        IdElem  : IdElem,
                        vTable  : vTable,
                        nmCamp  : nmCamp,
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
                    });
                },
            allowOutsideClick: () => !Swal.isLoading()
        });

    }

    function Edit_form(usr) {
        $('#modal_xl_add_user').modal('show');  
        $("#txtFullName").val(usr.nombre);
        $("#txtUserName").val(usr.email);
        $("#id_estado").html(usr.id);
        $("#txtPassWord_one").val("password-hide");
        $("#txtPassWord_two").val("password-hide");
        $("#txtComentario").val(usr.Comment);
        $("#sclPrivi").val(usr.id_rol).change();
    }

    function Save_Form() {

        var Permiso     = $("#sclPrivi option:selected").val();  
        var FullName    = $("#txtFullName").val();   
        var UserName    = $("#txtUserName").val();   
        var Pass01      = $("#txtPassWord_one").val();
        var Pass02      = $("#txtPassWord_two").val();
        var Commit      = $("#txtComentario").val();
        var Estado      = $("#id_estado").text();

        const Formulario = {
            Permiso     : isValue(Permiso, 'N/D', true),
            Nombre    : isValue(FullName, 'N/D', true),
            Usuario    : isValue(UserName, 'N/D', true),
            Contrasena      : isValue(Pass01, 'N/D', true),
            Estado      : isValue(Estado, 'N/D', true),
            Commit      : isValue(Commit, 'N/D', true),
            _token      : "{{ csrf_token() }}" 
        };

        const claveEncontrada = Object.keys(Formulario).find((clave) => Formulario[clave] === 'N/D');



        if (claveEncontrada) {
            Swal.fire({
            title: '<strong>REQUERIDO: <u>' + claveEncontrada + '</u></strong>',
            icon: 'error',
            showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
          
            })
        } else {
            if (Pass01 !== Pass02) {
                Swal.fire("Oops", "Contraseña no Coincide!", "error");
            } else if ( Pass01 && Pass02) {


                $.ajax({
                    url: "AddNewUser",
                    type: 'post',
                    data: Formulario,
                    async: true,
                    success: function(response) {
                        if (response) {
                            Swal.fire({
                                title: 'Registro procesado Correctamente ',
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
                    }
                }).done(function(data) {
                });

            
            
            } 
            
        }
        
    }

    $("#btn_save_user").click(function(){
        Save_Form();        
    });
</script>