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


            var Nombre_         = $("#txtNombre_Municipio").val();   
            var Departamento    = $("#selDepartamento option:selected").val();  
            
            Nombre_             = isValue(Nombre_,'N/D',true)
            Departamento        = isValue(Departamento,'N/D',true)


            if(Nombre_ === 'N/D' || Departamento === 'N/D'){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{

                $.ajax({
                url: "SaveNewMunicipio",
                type: 'post',
                data: {
                    Nombre_   : Nombre_,
                    Departamento:Departamento,
                    _token  : "{{ csrf_token() }}" 
                },
                async: true,
                success: function(response) {

                    if(response.status == 200){
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