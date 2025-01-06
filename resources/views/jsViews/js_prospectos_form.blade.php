<script type="text/javascript"> 
    $(document).ready(function () 
    {
        $('[data-mask]').inputmask();

        $("#btn_save_prospecto").click(function(){

            var Zona_       = $("#selZona option:selected").val();

            var Nombre_      = $("#txtNombre").val();   
            var Apellido_    = $("#txtApellido").val();   
            var Cedula_      = $("#txtCedula").val();
            var Tele_        = $("#txtTelefono").val();
            var Dire_        = $("#txtDireccion").val();
            var Monto_       = $("#txtMonto").val();   
            var IdProspecto_ = $("#IdProspecto").val();

                
            Nombre_         = isValue(Nombre_,'N/D',true)
            Apellido_       = isValue(Apellido_,'N/D',true)
            Cedula_         = isValue(Cedula_,'000-000000-00000',true)
            Tele_           = isValue(Tele_,'00-0000-0000',true)
            Dire_           = isValue(Dire_,'N/D',true)
            Monto_          = isValue(Monto_,'N/D',true)
            Zona_           = isValue(Zona_,'N/D',true)
            IdProspecto_    = isValue(IdProspecto_,'0',true)


            if(Nombre_ === 'N/D' || Apellido_ ==='N/D'||Dire_ === 'N/D' || Zona_ ==='N/D'){

                console.log(Nombre_ + ' ' + Apellido_ + ' ' + Dire_ + ' ' + Zona_);
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{
                $.ajax({
                url: "../SaveProspecto",
                type: 'post',
                data: {
                    Zona_        : Zona_,
                    Nombre_      : Nombre_,
                    Apellido_    : Apellido_ , 
                    Cedula_      : Cedula_,
                    Tele_        : Tele_,
                    Dire_        : Dire_,
                    Monto_       : Monto_,     
                    IdProspecto_ : IdProspecto_,             
                    _token       : "{{ csrf_token() }}" 
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
                //location.reload();
            });

    
        }


        });
        
    })
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>