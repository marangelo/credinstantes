<script type="text/javascript"> 
    $(document).ready(function () 
    {
        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        
        $('[data-mask]').inputmask();

        var btns_01 = $('#txtMonto,#txtPlazo,#txtInteres,#txtCuotas');
        btns_01.on('keyup touchend', function(e) {
            if (e.type === 'touchend' || isNumberKey(e)) {
                Calc_Credito();
            }
        });

        $("#btn_remove_prospecto").click(function(){
            var Idrequest = $("#IdRequest").val();


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
                        url: "../RemoverRequest",
                        data: {
                            Idrequest  : Idrequest,
                            _token  : "{{ csrf_token() }}" 
                        },
                        type: 'post',
                        async: true,
                        success: function(response) {
                            if(response){
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('Solicitudes/Lista/Nuevos') }}";
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
                

        
        });

        $("#btn_save_prospecto").click(function(){
            var IdProspecto = $("#IdRequest").val(); 

            var var_Url = (IdProspecto > 0) ?  "../UpRequestCredit" : "../SaveNewProspecto";
            var lbl_orig = $("#lbl_titulo_origen").html(); 

            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');

            var IdCiente_   = $("#id_Client").val();            

            var DiaSemana_  = $("#slDiaVisita option:selected").val();  
            var Municipio_  = $("#selMunicipio option:selected").val();  
            var Zona_       = $("#selZona option:selected").val();
            var Promotor    = $("#slPromotor option:selected").val();

            var Nombre_      = $("#txtNombre").val();   
            var Apellido_    = $("#txtApellido").val();   
            var Cedula_      = $("#txtCedula").val();
            var Tele_        = $("#txtTelefono").val();
            var Dire_        = $("#txtDireccion").val();


            var Monto_     = numeral($("#txtMonto").val()).format('0.00');   
            var Plato_     = numeral($("#txtPlazo").val()).format('0.00');   
            var Interes_   = numeral($("#txtInteres").val()).format('0.00');
            var Cuotas_    = numeral($("#txtCuotas").val()).format('0.00');

            var Total_     = numeral($("#txtTotal").val()).format('0.00');
            var vlCuota    = numeral($("#txtVlCuota").val()).format('0.00');
            var vlInteres  = numeral($("#txtIntereses").val()).format('0.00');
            var InteresesPorCuota  = numeral($("#txtInteresesPorCuota").val()).format('0.00');
            var Saldos_    = numeral($("#txtSaldos").val()).format('0.00');

            Promotor        = isValue(Promotor,0,true)

            DiaSemana_      = isValue(DiaSemana_,'N/D',true)
            Municipio_      = isValue(Municipio_,'N/D',true)            
            Nombre_         = isValue(Nombre_,'N/D',true)
            Apellido_       = isValue(Apellido_,'N/D',true)
            Cedula_         = isValue(Cedula_,'000-000000-00000',true)
            Tele_           = isValue(Tele_,'00-0000-0000',true)
            Dire_           = isValue(Dire_,'N/D',true)
            IdCiente_       = isValue(IdCiente_,0,true)

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
                        _token       : "{{ csrf_token() }}" ,
                        _Origin      : lbl_orig,
                        IdClientes     : IdCiente_
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
                                window.location.href = "{{ route('Solicitudes/Lista/Nuevos') }}";
                            }else{
                                window.location.href = "{{ route('Solicitudes/Lista/Nuevos') }}";
                            }
                        })
                    }
                },
                error: function(resp) {
                    var reqs = JSON.parse(resp.responseText);
                    
                    let lista = '<ul style="color:white;text-align:left">';
                    $.each(reqs.errors, function(key, value){
                        lista += `<li>✅ ${value}</li>`;
                    });
                    lista += '</ul>';
                    
                    Swal.fire({
                        title: "Estos Campos del Credito son Requeridos",
                        html: lista,
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                    });
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

        Monto_         = numeral(isValue(Monto_ ,0 ,true)).format('0.00')
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