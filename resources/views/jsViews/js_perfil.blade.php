<script type="text/javascript">
    $(document).ready(function () {
        $("#lbl_cancelacion").hide();
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
        

        $("#slTipoAbono").change(function() {
            
            var IdC_      = $("#lbl_credito").text();

            $("#txt_Total_abono").val(0.00);

            $("#id_lbl_cuota").text("Calculando....")

            $("#lbl_cancel_capital").html("C$ 0.00");
            $("#lbl_cancel_interes").html("C$ 0.00");

            setTimeout(function() {
                $("#id_lbl_cuota").text(" Cuota a pagar ");
                getIdCredi(IdC_)
            }, 3000);
            
        });

       




        $("#btn_add_credito").click(function(){

            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');

            var DiaSemana_  = $("#slDiaVisita option:selected").val(); 

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
            IdCred         = isValue(IdCred,0,true);

            var opt  = $("#slTipoAbono option:selected").val(); 



            if(Total_ === 'N/D' ){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{

                $.ajax({
                    url: "{{ route('SaveNewAbono')}}",
                    type: 'post',
                    data: {
                        Total_      : Total_,
                        IdCred      : IdCred,
                        FechaAbono  : dtAbono.format('YYYY-MM-DD'),
                        Tipo        : opt,
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
        var tabla = $(id).DataTable({
            "data": datos,
            "destroy": true,
            "info": false,
            responsive: true,
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
                {"title": "#","data": "id"},        
                {"title": "FECHA","data": "fecha_cuota", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">`+ row.fecha_cuota  +`</span> `

                    
                }},
                {"title": "CAPITAL","data": "pago_capital", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info text-success">C$  `+ numeral(row.pago_capital).format('0,00.00')  +`</span> `
                }},
                {"title": "INTERES","data": "pago_intereses", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info text-warning">C$  `+ numeral(row.pago_intereses).format('0,00.00')  +`</span> `
                }},
                {"title": "PENDIENTE","data": "saldo_cuota", "render": function(data, type, row, meta) {

                    if(row.saldo_cuota > 0){
                        return `<a href="#" onclick="AbonoPendiente(`+row.saldo_cuota+`,`+row.id_creditos+`)" class=""><span class="badge rounded-pill ms-3 badge-soft-info text-danger">C$  `+ numeral(row.saldo_cuota).format('0,00.00')  +`</span> </a>  `
                    }else{
                        return `<span class="badge rounded-pill badge-soft-info ">C$  `+ numeral(row.saldo_cuota).format('0,00.00')  +`</span> `
                    }

                }},
                {"title": "CUOTA","data": "cuota_credito", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">C$  `+ numeral(row.cuota_credito).format('0,00.00')  +`</span> `
                }},
                {"title": "ABONO","data": "cuota_cobrada", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">C$  `+ numeral(row.cuota_cobrada).format('0,00.00')  +`</span> `
                }},
                

                {"title": "--------------","data": "cuota_cobrada", "render": function(data, type, row, meta) {

                   

                    var isPagoParcial   = numeral(isValue(row.abono_dia2,0,true)).format('000.00')

                    var id_voucher      = numeral(isValue(row.id_abonoscreditos,0,true)).format('0')

                    var isCol = (isPagoParcial > 0)? '4' : '6';

                    var isHide = (isPagoParcial > 0)? '' : 'style="display:none"';

                    return `<div class="row">

                        <div class="col-md-`+isCol+` col-xl-`+isCol+`  " `+isHide+`>
                            <button type="button" class="btn btn-warning btn-block btn-sm"><a href="../voucherParcial/`+id_voucher+`" class="text-white" target="_blank"><i class="fas fa-print"></i> Parcial</a></button>
                        </div>

                        <div class="col-md-`+isCol+` col-xl-`+isCol+`  ">
                            <button type="button" class="btn btn-primary btn-block btn-sm"><a href="../voucher/`+id_voucher+`" class="text-white" target="_blank"><i class="fas fa-print"></i> Completo</a></button>
                        </div>
                        <div class="col-md-`+isCol+` col-xl-`+isCol+`  ">
                            @if( Session::get('rol') == '1')        
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="rmAbono(`+row.id_abonoscreditos+`)"><i class="fas fa-trash"></i> Remover</button>      
                            @endif
                        </div>
                        
                    </div>`
                }},

                ],
        });
        
    }

    function AbonoPendiente(Monto,Id){
        const FechaAbono = moment().format('YYYY-MM-DD HH:mm:ss');
        Swal.fire({
            title: '¿Quiere realizar el pago pendiente  ?',
            text: "¡Se aplicara el pago pendiente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!',
            target: document.getElementById('mdlMatPrima'),
            showLoaderOnConfirm: true,
            preConfirm: () => {
                $.ajax({
                    url: "../SaveNewAbono",
                    data: {
                        IdCred      : Id,
                        Total_      : Monto,
                        FechaAbono  : FechaAbono,
                        Tipo        : 0,
                        _token      : "{{ csrf_token() }}" 
                    },
                    type: 'post',
                    async: true,
                    success: function(response) {
                        if(response){
                            Swal.fire({
                                title: 'Abono Aplicado Correctamente ' ,
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
                        text: 'Este cliente tiene un '+response.original.cumplimiento_porcentaje+'% Avanzado ',
                    })
                }
                
            },
            error: function(response) {
                
            }
        }).done(function(data) {

        });

    })

    function getIdCredi(IdCredito){

        var opt  = $("#slTipoAbono option:selected").val(); 

        $.get( "../getSaldoAbono/" + IdCredito + "/" + opt , function( data ) {


            dtSaldo = numeral(isValue(data.Saldo_to_cancelar,0,true)).format('00.00')
            Interes_ = numeral(isValue(data.Interes_,0,true)).format('0,0.00')
            Capital_ = numeral(isValue(data.Capital_,0,true)).format('0,0.00')

            if ((parseFloat(dtSaldo) > 0) ) {


                $('#modal-lg').modal('show');                
                $("#lbl_credito").html(IdCredito);
                $("#txt_Total_abono").val(dtSaldo);


                
                if (opt === '0') {
                    $("#lbl_cancelacion").hide();
                } else {
                    $("#lbl_cancelacion").show();

                    $("#lbl_cancel_capital").html("C$ " + Capital_);
                    $("#lbl_cancel_interes").html("C$ " + Interes_);
                }


            }else{

                Swal.fire({
                    icon: 'error',
                    title:  'Credito',
                    text: 'Este cliente no tiene saldo pendiente',
                })

            }
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

  
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

</script>