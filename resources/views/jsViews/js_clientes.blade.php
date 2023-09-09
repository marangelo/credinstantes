<script type="text/javascript">
    $(document).ready(function () {

        $('[data-mask]').inputmask()

        $("#tbl_clientes").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "excel", "print"]
        }).buttons().container().appendTo('#tbl_clientes_wrapper .col-md-6:eq(0)');


        $("#btn_save_credito").click(function(){

            var DiaSemana_  = $("#selDiaSemana option:selected").val();  
            var Municipio_  = $("#selMunicipio option:selected").val();  

            var Nombre_      = $("#txtNombre").text();   
            var Apellido_    = $("#txtApellido").text();   
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
            var Saldos_    = $("#txtSaldos").val();

            DiaSemana_      = isValue(DiaSemana_,'N/D',true)
            Municipio_      = isValue(Municipio_,'N/D',true)            
            Nombre_         = isValue(Nombre_,'N/D',true)
            Apellido_       = isValue(Apellido_,'N/D',true)
            Cedula_         = isValue(Cedula_,'000-000000-00000',true)
            Tele_           = isValue(Tele_,'00-0000-0000',true)
            Dire_           = isValue(Dire_,'N/D',true)


            




            if(DiaSemana_ === 'N/D' || Municipio_ ==='N/D'){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{

                $.ajax({
                url: "SaveNewCredito",
                type: 'post',
                data: {
                    DiaSemana_   : DiaSemana_,
                    Municipio_   : Municipio_,
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
                    Saldos_      : Saldos_,
                    _token  : "{{ csrf_token() }}" 
                },
                async: true,
                success: function(response) {
                    if(response.original){
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


        var btns = $('#txtMonto,#txtPlazo,#txtInteres,#txtCuotas');

        btns.on('keyup', function(e){

            var Monto_     = $("#txtMonto").val();   
            var Plato_     = $("#txtPlazo").val();   
            var Interes_   = $("#txtInteres").val();
            var Cuotas_    = $("#txtCuotas").val();

            //var Total_     = $("#txtTotal").val();

            Monto_         = numeral(isValue(Monto_,0,true)).format('0.00')
            Cuotas_        = numeral(isValue(Cuotas_,0,true)).format('0.00')
            Interes_       = numeral(isValue(Interes_,0,true)).format('0.00')
            Cuotas_        = numeral(isValue(Cuotas_,0,true)).format('0.00')

            Total_         = ((Monto_ * (Interes_ / 100) * parseFloat(Cuotas_) ) + parseFloat(Monto_))

            vlCuota        = Total_ / parseFloat(Cuotas_);
            vlCuota        = numeral(isValue(vlCuota,0,true)).format('00.00')

            vlInteres      = parseFloat(Total_) - parseFloat(Monto_)

            $("#txtTotal").val(Total_);
            $("#txtVlCuota").val(vlCuota);
            $("#txtIntereses").val(vlInteres);
            $("#txtSaldos").val(Total_);
            

        });

        

    })

    function isValue(value, def, is_return) {
        if ( $.type(value) == 'null'
            || $.type(value) == 'undefined'
            || $.trim(value) == '(en blanco)'
            || $.trim(value) == ''
            || ($.type(value) == 'number' && !$.isNumeric(value))
            || ($.type(value) == 'array' && value.length == 0)
            || ($.type(value) == 'object' && $.isEmptyObject(value)) ) {
            return ($.type(def) != 'undefined') ? def : false;
        } else {
            return ($.type(is_return) == 'boolean' && is_return === true ? value : true);
        }
    }

</script>