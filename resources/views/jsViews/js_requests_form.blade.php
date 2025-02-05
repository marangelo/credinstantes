<script type="text/javascript"> 
    $(document).ready(function () 
    {
        $('[data-mask]').inputmask();

        var btns_01 = $('#txtMonto,#txtPlazo,#txtInteres,#txtCuotas');
        btns_01.on('keyup touchend', function(e) {
            if (e.type === 'touchend' || isNumberKey(e)) {
                Calc_Credito();
            }
        });

        $("#btn_save_prospecto").click(function(){
            var IdProspecto = $("#IdRequest").val(); 

            var var_Url = (IdProspecto > 0) ?  "../UpRequestCredit" : "../SaveNewProspecto";

            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'YYYY/MM/DD');

            var DiaSemana_  = $("#slDiaVisita option:selected").val();  
            var Municipio_  = $("#selMunicipio option:selected").val();  
            var Zona_       = $("#selZona option:selected").val();
            var Promotor    = $("#slPromotor option:selected").val();

            var Nombre_      = $("#txtNombre").val();   
            var Apellido_    = $("#txtApellido").val();   
            var Cedula_      = $("#txtCedula").val();
            var Tele_        = $("#txtTelefono").val();
            var Dire_        = $("#txtDireccion").val();


            var Monto_     = $("#txtMonto").val();   
            var Plato_     = $("#txtPlazo").val();   
            var Interes_   = $("#txtInteres").val();
            var Cuotas_    = $("#txtCuotas").val();

            var Total_     = $("#txtTotal").val();
            var vlCuota    = $("#txtVlCuota").val();
            var vlInteres  = $("#txtIntereses").val();
            var InteresesPorCuota  = $("#txtInteresesPorCuota").val();
            var Saldos_    = $("#txtSaldos").val();

            Promotor        = isValue(Promotor,0,true)

            DiaSemana_      = isValue(DiaSemana_,'N/D',true)
            Municipio_      = isValue(Municipio_,'N/D',true)            
            Nombre_         = isValue(Nombre_,'N/D',true)
            Apellido_       = isValue(Apellido_,'N/D',true)
            Cedula_         = isValue(Cedula_,'000-000000-00000',true)
            Tele_           = isValue(Tele_,'00-0000-0000',true)
            Dire_           = isValue(Dire_,'N/D',true)

            if(DiaSemana_ === 'N/D' || Municipio_ ==='N/D'||Nombre_ === 'N/D' || Apellido_ ==='N/D'){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{
                $.ajax({
                    url: var_Url,
                    type: 'post',
                    data: {
                        IdProspecto_  : IdProspecto,
                        DiaSemana_   : DiaSemana_,
                        Municipio_   : Municipio_,
                        Zona_        : Zona_,
                        Promotor_    : Promotor,
                        Nombre_      : Nombre_,
                        Apellido_    : Apellido_ , 
                        Cedula_      : Cedula_,
                        Tele_        : Tele_,
                        Dire_        : Dire_,
                        Monto_       : Monto_,  
                        Plato_       : Plato_,  
                        Interes_     : Interes_,
                        Cuotas_      : Cuotas_,
                        Total_       : Total_,
                        vlCuota      : vlCuota,
                        vlInteres    : vlInteres,
                        InteresesPorCuota:InteresesPorCuota,
                        Saldos_      : Saldos_,
                        FechaOpen    : fechaAnalizada.format('YYYY-MM-DD'),
                        _token  : "{{ csrf_token() }}" 
                    },
                    async: true,
                success: function(response) {
                    if(response){
                        console.log(response)
                        Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }else{
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


    function Calc_Credito(){
        var Monto_     = $("#txtMonto").val();   
        var Plato_     = $("#txtPlazo").val();   
        var Interes_   = $("#txtInteres").val();
        var Cuotas_    = $("#txtCuotas").val();

        Monto_         = numeral(isValue(Monto_,0,true)).format('0.00')
        Cuotas_        = numeral(isValue(Cuotas_,0,true)).format('0.00')
        Interes_       = numeral(isValue(Interes_,0,true)).format('0.00')
        Plato_         = numeral(isValue(Plato_,0,true)).format('0.00')

        Total_         = ((Monto_ * (Interes_ / 100) * parseFloat(Plato_) ) + parseFloat(Monto_))

        vlCuota        = Total_ / parseFloat(Cuotas_);
        vlCuota        = numeral(isValue(vlCuota,0,true)).format('00.00')

        vlInteres      = parseFloat(Total_) - parseFloat(Monto_)
        vlInterePorCuota  = parseFloat(vlInteres) / parseFloat(Cuotas_)
        vlInterePorCuota        = numeral(isValue(vlInterePorCuota,0,true)).format('0.00')

        $("#txtTotal").val(Total_);
        $("#txtVlCuota").val(vlCuota);
        $("#txtIntereses").val(vlInteres);
        $("#txtSaldos").val(Total_);
        $("#txtInteresesPorCuota").val(vlInterePorCuota);
    }


    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>