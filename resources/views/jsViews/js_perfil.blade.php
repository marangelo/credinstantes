<script type="text/javascript">
    $(document).ready(function () {

        $("#tbl_abonos_creditos").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "excel", "print"]
        }).buttons().container().appendTo('#tbl_clientes_wrapper .col-md-6:eq(0)');


        




        $("#btn_save_credito").click(function(){

            var DiaSemana_  = $("#selDiaSemana option:selected").val();  
            var Municipio_  = $("#selMunicipio option:selected").val();  

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
            var Saldos_    = $("#txtSaldos").val();

        
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


        var btns = $('#txtMonto,#txtPlazo,#txtInteres,#txtCuotas');

        btns.on('keyup', function(e){
            if(isNumberKey(e)){
                var Monto_     = $("#txtMonto").val();   
                var Plato_     = $("#txtPlazo").val();   
                var Interes_   = $("#txtInteres").val();
                var Cuotas_    = $("#txtCuotas").val();

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

            }

        });

        $("#btn_save_abono").click(function(){

            var Capital_    = $("#txt_Capital").val();   
            var Interes_    = $("#txt_Interes").val();   
            var Total_      = $("#txt_Total_abono").val();

            var IdCred      = $("#lbl_credito").text();

            Capital_      = isValue(Capital_,'N/D',true)
            Interes_      = isValue(Interes_,'N/D',true)            
            Total_         = isValue(Total_,'N/D',true)
            IdCred         = isValue(IdCred,0,true)

            if(Capital_ === 'N/D' || Interes_ ==='N/D'||Total_ === 'N/D' ){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{

                $.ajax({
                    url: "{{ route('SaveNewAbono')}}",
                    type: 'post',
                    data: {
                        Capital_   : Capital_,
                        Interes_   : Interes_,
                        Total_      : Total_,
                        IdCred      : IdCred,
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
                    //location.reload();
                });

            }

        })

        var btns = $('#txt_Capital,#txt_Interes');

        btns.on('keyup', function(e){
            if(isNumberKey(e)){
                var Capital_    = $("#txt_Capital").val();   
                var Interes_    = $("#txt_Interes").val();   

                Capital_        = numeral(isValue(Capital_,0,true)).format('0.00')
                Interes_        = numeral(isValue(Interes_,0,true)).format('0.00')

                Total_          = parseFloat(Capital_) + parseFloat(Interes_)

                $("#txt_Total_abono").val(Total_);

            }

        });

    })

    function initTable_modal(id,datos){
        $(id).DataTable({
            "data": datos,
            "destroy": true,
            "info": false,
            "bPaginate": false,
            "searching": false,
            "order": [
                [0, "asc"]
            ],
            "lengthMenu": [
                [5, -1],
                [5, "Todo"]
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
            'columns':  [         
                {"title": "Fecha Cuota","data": "fecha_cuota", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill ms-3 badge-soft-info ">`+ row.fecha_cuota  +`</span> `
                }},
                {"title": "Capital","data": "pago_capital", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill ms-3 badge-soft-info ">C$  `+ numeral(row.pago_capital).format('0,00.00')  +`</span> `
                }},
                {"title": "Interes","data": "pago_intereses", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill ms-3 badge-soft-info ">C$  `+ numeral(row.pago_intereses).format('0,00.00')  +`</span> `
                }},
                {"title": "Cuota Credito","data": "cuota_credito", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill ms-3 badge-soft-info ">C$  `+ numeral(row.cuota_credito).format('0,00.00')  +`</span> `
                }},
                {"title": "Monto Abono","data": "cuota_cobrada", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill ms-3 badge-soft-info ">C$  `+ numeral(row.cuota_cobrada).format('0,00.00')  +`</span> `
                }},
                ],
        });
    }

   
    function getModalHistorico(id){
        $("#lbl_mdl_id_credito").html(id);
        $('#modal-historico').modal('show');
            var IdCredito = $("#lbl_mdl_id_credito").text();

            $.get( "../getHistoricoAbono/" + IdCredito, function( data ) {
                initTable_modal('#tbl_lista_abonos',data);

            });
    }

    function getIdCredi(id){
        $("#lbl_credito").html(id);
    }

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
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

</script>