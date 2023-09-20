<script type="text/javascript">
    $(document).ready(function () {

        $("#tbl_abonos_creditos").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "excel", "print"]
        }).buttons().container().appendTo('#tbl_clientes_wrapper .col-md-6:eq(0)');

        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#dtAbono').datetimepicker({
            format: 'DD/MM/YYYY'
        });


        $("#btn_add_credito").click(function(){

            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');

            var DiaSemana_  = fechaAnalizada.day();  

            var Monto_      = $("#txtMonto").val();   
            var Plato_      = $("#txtPlazo").val();   
            var Interes_    = $("#txtInteres").val();
            var Cuotas_     = $("#txtCuotas").val();

            var Total_      = $("#txtTotal").val();
            var vlCuota     = $("#txtVlCuota").val();
            var vlInteres   = $("#txtIntereses").val();
            var Saldos_     = $("#txtSaldos").val();

        
            DiaSemana_      = isValue(DiaSemana_,'N/D',true)
            Plato_          = isValue(Plato_,'N/D',true)            
            Interes_        = isValue(Interes_,'N/D',true)
            Cuotas_         = isValue(Cuotas_,'N/D',true)
            IdClientes      =  {{request()->segment(2) }}
            var InteresesPorCuota  = $("#txtInteresesPorCuota").val();


            if(Monto_ === 'N/D' || Plato_ ==='N/D'||Interes_ === 'N/D' || Cuotas_ ==='N/D'){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{
                $.ajax({
                    url: "../AddCredito",
                    type: 'post',
                    data: {
                        DiaSemana_   : DiaSemana_,
                        IdClientes   : IdClientes,
                        Monto_       : Monto_,  
                        Plato_       : Plato_,  
                        Interes_     : Interes_,
                        Cuotas_      : Cuotas_,
                        Total_       : Total_,
                        vlCuota      : vlCuota,
                        vlInteres    : vlInteres,
                        Saldos_      : Saldos_,
                        InteresesPorCuota:InteresesPorCuota,
                        FechaOpen    : fechaAnalizada.format('YYYY-MM-DD'),
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


        var btns_01 = $('#txtMonto,#txtPlazo,#txtInteres,#txtCuotas');
        btns_01.on('keyup touchend', function(){
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

        })

        btns_01.on('keyup', function(e){
            if(isNumberKey(e)){
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

        });

        $("#btn_save_abono").click(function(){

            var Total_      = $("#txt_Total_abono").val();

            var IdCred      = $("#lbl_credito").text();

            var DateAbono   = $("#IddtApertura").val();
            const dtAbono   = moment(DateAbono, 'DD/MM/YYYY');
          
            Total_         = isValue(Total_,'N/D',true)
            IdCred         = isValue(IdCred,0,true)

            if(Total_ === 'N/D' ){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{

                $.ajax({
                    url: "{{ route('SaveNewAbono')}}",
                    type: 'post',
                    data: {
                        Total_      : Total_,
                        IdCred      : IdCred,
                        FechaAbono    : dtAbono.format('YYYY-MM-DD'),
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
                {"title": "Pendiente","data": "saldo_cuota", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill ms-3 badge-soft-info ">C$  `+ numeral(row.saldo_cuota).format('0,00.00')  +`</span> `
                }},

                {"title": "","data": "cuota_cobrada", "render": function(data, type, row, meta) {

                    var id_voucher = numeral(isValue(row.id_abonoscreditos,0,true)).format('0')

                    return `
                    <button type="button" class="btn btn-block bg-gradient-danger btn-sm"><a href="#" onclick="rmAbono(`+row.id_abonoscreditos+`)" class="text-white"><i class="fas fa-trash"></i></a></button>
                    <button type="button" class="btn btn-block bg-gradient-primary btn-sm"><a href="../voucher/`+id_voucher+`" class="text-white" target="_blank"><i class="fas fa-print"></i></a></button>
                    `
                }},

                ],
        });
    }

    function rmItem(IdElem){

        var vTable  = "Tbl_Creditos" ; 
        var nmCamp  = "id_creditos" ; 

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
                    url: "../rmElem",
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
    function rmAbono(IdElem){

        

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
                    url: "../rmAbono",
                    data: {
                        IdElem  : IdElem,
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

    

    $("#btn_mdl_credito").click(function(){

        var Cliente = {{request()->segment(2)}}

        $.ajax({
            url: "../creditCheck",
            type: 'post',
            data: {
                Cliente      : Cliente,
                _token  : "{{ csrf_token() }}" 
            },
            async: true,
            success: function(response) {
                if (response.original.creditCheck) {
                    $('#modal-xl').modal('show');
                    $("#txtMonto").val(response.original.MontoMaximo)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title:  'Credito en paralelo',
                        text: 'Este cliente no clasifica para un credito en paralelo',
                    })
                }
            },
            error: function(response) {
                
            }
        }).done(function(data) {

        });

    })

   
    function getModalHistorico(id){
        $("#lbl_mdl_id_credito").html(id);
        $('#modal-historico').modal('show');
            var IdCredito = $("#lbl_mdl_id_credito").text();

            $.get( "../getHistoricoAbono/" + IdCredito, function( data ) {
                initTable_modal('#tbl_lista_abonos',data);

            });
    }

    function getIdCredi(obj){
        var Cuota = numeral(isValue(obj.cuota,0,true)).format('00.00')
        $("#txt_Total_abono").val(Cuota);
        $("#lbl_credito").html(obj.id_creditos);
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