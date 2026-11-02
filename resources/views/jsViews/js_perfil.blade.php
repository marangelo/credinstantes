<script type="text/javascript">
    $(document).ready(function () {
        $("#lbl_cancelacion").hide();
        // Los créditos ahora se muestran como tarjetas (Perfil.blade.php), ya no como tabla.

        // Selector segmentado "Tipo de pago" -> controla el select #slTipoAbono que usa la lógica
        $('#pfTipoSeg').on('click', 'button[data-tipo]', function () {
            var tipo = String($(this).data('tipo'));
            if ($('#slTipoAbono').val() === tipo) return;
            marcarTipo(tipo);
            $('#slTipoAbono').val(tipo).trigger('change');
        });

        // El botón muestra el monto que se va a registrar (sirve de confirmación)
        $('#txt_Total_abono').on('input keyup change', actualizarBotonPago);

        // Al abrir la hoja de pago, el botón vuelve a su estado normal
        $('#modal-lg').on('show.bs.modal', function () { liberarBotonPago(); });

        // En PC: al abrir, el cursor queda en el monto (seleccionado) y Enter registra el pago.
        // En celular no se enfoca solo, para no abrir el teclado encima de la hoja.
        $('#modal-lg').on('shown.bs.modal', function () {
            if (window.matchMedia && window.matchMedia('(pointer: fine)').matches) {
                $('#txt_Total_abono').trigger('focus').trigger('select');
            }
        });
        $('#txt_Total_abono, #txt_descuento').on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#btn_save_abono').trigger('click');
            }
        });


        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#dtAbono').datetimepicker({
            format: 'DD/MM/YYYY'
        });


       

        $("#slTipoAbono").change(function() {
            
            var IdC_      = $("#lbl_credito").text();
            
            var OpSelected = this.value ;

            marcarTipo(OpSelected);

            if (OpSelected === '0' || OpSelected === '1') {
                // Recalcula de inmediato (antes había una espera fija de 3 s)
                $("#txt_Total_abono").val('');
                $("#lbl_cancel_capital").html("C$ 0.00");
                $("#lbl_cancel_interes").html("C$ 0.00");
                actualizarBotonPago();

                getIdCredi($.trim(IdC_));
            } else {
                $("#lbl_cancelacion").hide();
            }

           
            
        });

       




        $("#btn_add_credito").click(function(){

            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');

            var DiaSemana_  = $("#slDiaVisita option:selected").val(); 
            var Promotor  = $("#slPromotor option:selected").val(); 
            

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
                        Promotor_    : Promotor,
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

            var btnPago = $(this);
            // Evita registrar el mismo pago dos veces (doble toque / red lenta)
            if (btnPago.data('guardando')) return;

            var Total_      = $("#txt_Total_abono").val();

            if (!(numeral(Total_).value() > 0)) {
                $("#txt_Total_abono").addClass('is-invalid').trigger('focus');
                Swal.fire("Monto inválido", "Escriba un monto mayor que cero.", "error");
                return;
            }
            $("#txt_Total_abono").removeClass('is-invalid');
            var IdCred      = $("#lbl_credito").text();
            var DateAbono   = $("#IddtApertura").val();
            // var DateAbono   = $("#lista_pagos option:selected").text(); 
            // var parts       = DateAbono.split(" | ");
            // DateAbono = moment(parts[1]).format("YYYY-MM-DD");

            var NumPago     = $("#lista_pagos option:selected").val(); 
            var Descuento   = $("#txt_descuento").val();
            var Desc        = isValue(Descuento,0,true);

            const dtAbono   = moment(DateAbono, 'DD/MM/YYYY');

            var sldPendiene = $("#id_mdl_saldo_pendiente").html()
        
            Total_         = isValue(Total_,'N/D',true)
            IdCred         = isValue(IdCred,0,true);
            Descuento      = isValue(Desc,0,true);
            

            var opt  = $("#slTipoAbono option:selected").val(); 

  


            if(Total_ === 'N/D' ){
                Swal.fire("Oops", "Datos no Completos", "error");
            }else{

                if (sldPendiene === '0.00' || opt === '0' || opt === '1') {

                    bloquearBotonPago();

                    $.ajax({
                        url: "{{ route('SaveNewAbono')}}",
                        type: 'post',
                        data: {
                            Total_      : Total_,
                            IdCred      : IdCred,
                            FechaAbono  : dtAbono.format('YYYY-MM-DD'),
                            Tipo        : opt,
                            Desc        : Desc,
                            NumPago     : NumPago,
                            _token  : "{{ csrf_token() }}" 
                        },
                        async: true,
                        success: function(response) {
                            if(response){
                                // El botón queda bloqueado: el pago ya se guardó y la página se recarga
                                $('#modal-lg').modal('hide');
                                Swal.fire({
                                    title: 'Pago registrado',
                                    text: 'C$ ' + numeral(Total_).format('0,0.00') + ' al crédito #' + IdCred,
                                    icon: 'success',
                                    confirmButtonText: 'Listo',
                                    allowOutsideClick: false
                                }).then(function () {
                                    location.reload();
                                });
                            } else {
                                liberarBotonPago();
                                Swal.fire("No se registró el pago", "El servidor no confirmó el pago. Revise el historial antes de intentar de nuevo.", "error");
                            }
                        },
                        error: function(xhr) {
                            liberarBotonPago();
                            var msg = (xhr.status === 419)
                                ? "La sesión expiró. Recargue la página e intente de nuevo."
                                : (xhr.status === 0)
                                    ? "Sin conexión. Revise el historial antes de intentar de nuevo, por si el pago sí se guardó."
                                    : "No se ha podido guardar el pago.";
                            Swal.fire("No se registró el pago", msg, "error");
                        }
                    });
                    
                } else {
                    Swal.fire("Saldo pendiente en abono", "", "error"); 
                }
            }

        })

    

    })


    function initTable_historico(id,datos){
        var tabla = $(id).DataTable({
            "data": datos,
            "createdRow": etiquetarFila,
            "destroy": true,
            "info": false,
            responsive: true,
            "bPaginate": true,
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
                {"title": "#","data": "NUM_PAGO"},        
                {"title": "FECHA PAGO","data": "FECHA_PAGO", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">`+ row.FECHA_PAGO  +`</span> `
                }},
                {"title": "FECHA ABONO","data": "FECHA_ABONO", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">`+ row.FECHA_ABONO  +`</span> `                    
                }},
                {"title": "SALDO PENDIENTE","data": "SALDO_PENDIENTE", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info text-success">C$  `+ numeral(row.SALDO_PENDIENTE).format('0,00.00')  +`</span> `
                }},

            ],
        });
        $("#tbl_pagos_realizados_length").hide();
        
    }

    function EditarCredito(Credito) {

        window.location.href = "../EditarCredito/"+Credito;

    }

    function initTable_modal(id,datos){
        var tabla = $(id).DataTable({
            "data": datos,
            "createdRow": etiquetarFila,
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
                        return `<a href="#" class="pf-pending-link" title="Cobrar el saldo pendiente de esta cuota" onclick="AbonoPendiente(`+row.saldo_cuota+`,`+row.id_creditos+ `,`+ row.NumPago +`); return false;">C$ `+ numeral(row.saldo_cuota).format('0,0.00')  +` · Cobrar</a>`
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
                

                {"title": "","data": "cuota_cobrada", "orderable": false, "className": "pf-td-actions", "render": function(data, type, row, meta) {

                    var isPagoParcial   = numeral(isValue(row.abono_dia2,0,true)).format('000.00')
                    var id_voucher      = numeral(isValue(row.id_abonoscreditos,0,true)).format('0')

                    // Enlaces reales (antes eran <a> dentro de <button>, HTML inválido y difícil de tocar)
                    var html = '<div class="pf-row-actions">';
                    if (isPagoParcial > 0) {
                        html += '<a href="../voucherParcial/' + id_voucher + '" target="_blank" rel="noopener" class="pf-btn"><i class="fas fa-print" aria-hidden="true"></i> Parcial</a>';
                    }
                    html += '<a href="../voucher/' + id_voucher + '" target="_blank" rel="noopener" class="pf-btn"><i class="fas fa-print" aria-hidden="true"></i> Comprobante</a>';
                    @if( Session::get('rol') == '1')
                    html += '<button type="button" class="pf-btn pf-btn--danger" onclick="rmAbono(' + row.id_abonoscreditos + ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar</button>';
                    @endif
                    html += '</div>';
                    return html;
                }},

                ],
        });
        
    }

    function AbonoPendiente(Monto,Id,Num_Pago){
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
                        NumPago     : Num_Pago,
                        Desc        : 0,
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

        var vTable  = "tbl_creditos" ; 
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

    function getNotificacionVisita(IdCredito){
        var ahora = moment();

        $("#lbl_ntf_id_credito").html(IdCredito);
        $("#txt_ntf_fecha").val(ahora.format('YYYY-MM-DD'));
        $("#txt_ntf_hora").val(ahora.format('HH:mm'));
        $("#txt_ntf_monto").val('');
        $("#txt_ntf_proxima").val('');
        $("#txt_ntf_comentario").val('');
        $("#lbl_ntf_contador").html(0);
        $('#modal-notificacion-visita').modal('show');
    }

    $("#txt_ntf_comentario").on('input', function () {
        $("#lbl_ntf_contador").html($(this).val().length);
    });

    $("#btn_print_notificacion").click(function(){
        var IdCredito   = $("#lbl_ntf_id_credito").text().trim();
        var Fecha       = $("#txt_ntf_fecha").val();
        var Hora        = $("#txt_ntf_hora").val();
        var Monto       = numeral($("#txt_ntf_monto").val()).value();
        var Proxima     = $("#txt_ntf_proxima").val();
        var Comentario  = $("#txt_ntf_comentario").val();

        if (!Fecha || !Hora) {
            Swal.fire("Fecha y hora de la visita son requeridas", "", "error");
            return;
        }

        if (!(Monto > 0)) {
            Swal.fire("Digite el monto atrasado", "", "error");
            return;
        }

        if (Proxima && moment(Proxima).isBefore(moment(Fecha))) {
            Swal.fire("La próxima visita no puede ser antes de la visita", "", "error");
            return;
        }

        var btn = $(this);

        // Bloquea el botón mientras se guarda para evitar varios registros por clicks repetidos
        if (btn.data('guardando')) return;
        btn.data('guardando', true).prop('disabled', true)
           .html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        var Datos = {
            IdCredito       : IdCredito,
            FechaVisita     : Fecha,
            HoraVisita      : Hora,
            MontoAtrasado   : Monto,
            FechaProxima    : Proxima,
            Comentario      : Comentario,
            _token          : "{{ csrf_token() }}"
        };

        var MaxIntentos = 3;

        function Liberar() {
            btn.data('guardando', false).prop('disabled', false)
               .html('<i class="fas fa-print"></i> Imprimir');
        }

        function Fallo(Mensaje, Reintentable) {
            if (!Reintentable) {
                Liberar();
                Swal.fire("Oops", Mensaje, "error");
                return;
            }

            // El botón sigue bloqueado mientras el usuario decide, así no se generan registros nuevos
            Swal.fire({
                icon: 'error',
                title: 'No se pudo guardar',
                text: Mensaje,
                showCancelButton: true,
                confirmButtonText: 'Reintentar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Guardar(1);
                } else {
                    Liberar();
                }
            });
        }

        function Guardar(Intento) {
            $.ajax({
                url: "{{ route('SaveNotificacionVisita') }}",
                type: 'post',
                data: Datos,
                timeout: 15000,
                success: function(response) {
                    if (response.success) {
                        $('#modal-notificacion-visita').modal('hide');
                        Liberar();
                        MostrarComprobante(response.id);
                    } else {
                        Fallo(response.message, false);
                    }
                },
                error: function(xhr) {
                    // Solo se reintentan fallas de red/timeout o errores del servidor (5xx).
                    // El servidor devuelve la notificación existente si el registro ya se había insertado.
                    var Reintentable = (xhr.status === 0 || xhr.status >= 500);

                    if (Reintentable && Intento < MaxIntentos) {
                        btn.html('<i class="fas fa-spinner fa-spin"></i> Reintentando (' + (Intento + 1) + '/' + MaxIntentos + ')...');
                        setTimeout(function () { Guardar(Intento + 1); }, 1000 * Math.pow(2, Intento - 1));
                        return;
                    }

                    var Mensaje = (xhr.status === 419)
                        ? "La sesión expiró, recargue la página."
                        : (xhr.responseJSON && xhr.responseJSON.message) || "No se ha podido guardar la notificación!";

                    Fallo(Mensaje, Reintentable);
                }
            });
        }

        Guardar(1);
    });

    // Se llama solo cuando el registro ya se guardó.
    // En el WebView de la app Android se navega en la misma vista (window.open deja la ventana en blanco).
    // En navegador se intenta otra pestaña; si el bloqueador de popups la impide, se navega en la misma.
    function MostrarComprobante(IdNotificacion) {
        var Url = "{{ url('notificacionVisita') }}/" + IdNotificacion;
        var EsWebView = /\bwv\b/.test(navigator.userAgent);

        if (!EsWebView) {
            var win = window.open(Url, '_blank');
            if (win) return;
        }

        window.location.href = Url;
    }

    function getIdCredi(IdCredito){

    

        var opt         = $("#slTipoAbono option:selected").val(); 
        var IdAbono     = $("#lista_pagos option:selected").val();

        var lstPagos    = $("#lista_pagos");
       
    

        $.get( "../getSaldoAbono/" + IdCredito + "/" + opt  , function( data ) {
            

            let ListaPagos = data.Plan_pago;
            lstPagos.empty();
            $.each( ListaPagos, function(key, val) {
                //let optValue = numeral(val.NUM_PAGO).format('00') + " | " + val.FECHA_PAGO;
                let optValue = numeral(val.NUM_PAGO).format('00') + " | " + val.FECHA_PAGO;

                if (val.PENDIENTE === 0) {
                    optValue += ' (Pagado)';
                }

                let option = new Option(optValue, val.NUM_PAGO);

                if (val.PENDIENTE === 0) {
                    $(option).prop('disabled', true);
                }

                lstPagos.append(option);
            });

            
        
            dtSaldo = numeral(isValue(data.Saldo_to_cancelar,0,true)).format('00.00')
            Interes_ = numeral(isValue(data.Interes_,0,true)).format('0,0.00')
            Capital_ = numeral(isValue(data.Capital_,0,true)).format('0,0.00')
            SaldoPe_ = numeral(isValue(data.Saldo_Pendiente,0,true)).format('0,0.00')

            txtSaldo = (parseFloat(data.Saldo_Pendiente) > 0)? data.Saldo_Pendiente : dtSaldo

            $("#id_mdl_saldo_pendiente").html(SaldoPe_);
            $("#pfPendNote").prop('hidden', !(parseFloat(data.Saldo_Pendiente) > 0));
            marcarTipo(opt);

            if ((parseFloat(dtSaldo) > 0) ) {


                $('#modal-lg').modal('show');                
                $("#lbl_credito").html(IdCredito);

                
                
                $("#txt_Total_abono").val(txtSaldo);
                actualizarBotonPago();

                switch (opt) {
                    case '0':
                        $("#lbl_cancelacion").hide();
                        break;
                    case '1':
                        $("#lbl_cancelacion").show();

                        $("#lbl_cancel_capital").html("C$ " + Capital_);
                        $("#lbl_cancel_interes").html("C$ " + Interes_);
                        break;
                    case '2':
                        $("#lbl_cancelacion").hide();
                        break;
                
                    default:
                        break;
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


     

            initTable_modal('#tbl_lista_abonos',data[0].Abonos);
            initTable_historico('#tbl_pagos_realizados',data[0].Pagos);


        });

        $.get( "../getNotificacionesCliente/{{ $perfil_cliente->id_clientes }}", function( data ) {
            initTable_notificaciones('#tbl_notificaciones_cliente', data);
        });
    }

    function initTable_notificaciones(id,datos){
        $(id).DataTable({
            "data": datos,
            "createdRow": etiquetarFila,
            "destroy": true,
            "info": false,
            responsive: true,
            "bPaginate": true,
            "searching": false,
            "order": [
                [0, "desc"]
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
                "emptyTable": "SIN NOTIFICACIONES",
                "search": "BUSCAR"
            },
            'columns':  [
                {"title": "#","data": "Id"},
                {"title": "CREDITO","data": "IdCredito"},
                {"title": "FECHA VISITA","data": "FechaVisita", "render": function(data, type, row) {
                    return `<span class="badge rounded-pill badge-soft-info ">`+ row.FechaVisita + ' ' + row.HoraVisita +`</span> `
                }},
                {"title": "MONTO ATRASADO","data": "Monto", "render": function(data) {
                    return `<span class="badge rounded-pill badge-soft-info text-danger">C$  `+ numeral(data).format('0,00.00') +`</span> `
                }},
                {"title": "PROXIMA VISITA","data": "Proxima"},
                {"title": "COMENTARIO","data": "Comentario"},
                {"title": "USUARIO","data": "Usuario"},
                {"title": "","data": "Id", "orderable": false, "render": function(data) {
                    return `<a href="../notificacionVisita/`+ data +`" target="_blank" class="btn btn-warning btn-sm"><i class="fas fa-print"></i></a>`
                }},
            ],
        });
        $(id + "_length").hide();
    }

  
    // ---------- Hoja de pago: helpers ----------
    function marcarTipo(tipo) {
        $('#pfTipoSeg button').each(function () {
            $(this).attr('aria-checked', String($(this).data('tipo')) === String(tipo) ? 'true' : 'false');
        });
    }

    function actualizarBotonPago() {
        if ($('#btn_save_abono').data('guardando')) return;
        var monto = numeral($('#txt_Total_abono').val()).value() || 0;
        $('#pfPayLabel').text(monto > 0 ? 'Registrar C$ ' + numeral(monto).format('0,0.00') : 'Registrar pago');
    }

    function bloquearBotonPago() {
        $('#btn_save_abono').data('guardando', true).prop('disabled', true).attr('aria-busy', 'true');
        $('#pfPayLabel').text('Guardando…');
    }

    function liberarBotonPago() {
        $('#btn_save_abono').data('guardando', false).prop('disabled', false).attr('aria-busy', 'false');
        actualizarBotonPago();
    }

    // En celular las filas de las tablas del historial se muestran como tarjetas con etiquetas
    function etiquetarFila(row, data, dataIndex, cells) {
        var api = this.api ? this.api() : (this.column ? this : null);
        if (!api) return;
        $(cells).each(function (i) {
            var titulo = $(api.column(i).header()).text().trim();
            if (titulo && titulo.replace(/-/g, '') !== '') $(this).attr('data-label', titulo);
        });
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

</script>