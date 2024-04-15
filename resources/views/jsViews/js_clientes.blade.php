<script type="text/javascript">
    $(document).ready(function () {
        var currentPath = window.location.pathname; 
        $("#id_select_zona").val(currentPath.slice(-1)).change();

        $('[data-mask]').inputmask()
    
        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        var userRole = $("#id_rol_user").text();
        $('#id_select_zona').change(function() {
            var selectedValue = this.value;           
            window.location.href = currentPath.slice(0, -1) + selectedValue;
        });

        $("#tbl_clientes").DataTable({
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
            "buttons": (userRole == 1) ? ["copy", "excel", "pdf"] : [ ],
            columnDefs: [{ visible: false, targets: 0 }],
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(0, { page: 'current' }).data().each(function(group, i) {
                    if (last !== group) {

                        var tr_color = 'success'
                        if (group != 'AL DIA') {
                            (group === 'EN MORA') ? tr_color = 'danger' : tr_color = 'warning'
                        }

                        $(rows).eq(i).before('<tr class="bg-'+tr_color+' group"><td colspan="13">' + group + '</td></tr>');

                        
                        last = group;
                    }
                });



            }
            
        }).buttons().container().appendTo('#tbl_clientes_wrapper .col-md-6:eq(0)');

        $("#btn_edit_credito").click(function(){
            
                var Municipio_  = $("#edtMunicipio option:selected").val();  
                var Zona_       = $("#edtZonas option:selected").val();  

                var Nombre_      = $("#edtNombre").val();   
                var Apellido_    = $("#edtApellido").val();   
                var Cedula_      = $("#edtCedula").val();
                var Tele_        = $("#edtTelefono").val();
                var Dire_        = $("#edtDireccion").val();
                var IdCl_        = $("#edtIdClient").text();

                Municipio_      = isValue(Municipio_,'N/D',true)            
                Nombre_         = isValue(Nombre_,'N/D',true)
                Apellido_       = isValue(Apellido_,'N/D',true)
                Cedula_         = isValue(Cedula_,'000-000000-00000',true)
                Tele_           = isValue(Tele_,'00-0000-0000',true)
                Dire_           = isValue(Dire_,'N/D',true)

                if(Municipio_ ==='N/D'||Nombre_ === 'N/D' || Apellido_ ==='N/D'){
                    Swal.fire("Oops", "Datos no Completos", "error");
                }else{
                    $.ajax({
                        url: "../editClient",
                        type: 'post',
                        data: {
                            IdCl_:IdCl_,
                            Municipio_   : Municipio_,
                            Zona_        : Zona_,
                            Nombre_      : Nombre_,
                            Apellido_    : Apellido_ , 
                            Cedula_      : Cedula_,
                            Tele_        : Tele_,
                            Dire_        : Dire_,
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


        $("#btn_save_credito").click(function(){

            var DateOPen      = $("#dtApertura").val(); 
            const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');

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
                url: "../SaveNewCredito",
                type: 'post',
                data: {
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
        btns.on('keyup touchend', function(){
            var Monto_              = $("#txtMonto").val();   
            var Plato_              = $("#txtPlazo").val();   
            var Interes_            = $("#txtInteres").val();
            var Cuotas_             = $("#txtCuotas").val();
            var DiaSemana_  = $("#slDiaVisita option:selected").val();  
            

            Monto_         = numeral(isValue(Monto_,0,true)).format('0.00')
            Cuotas_        = numeral(isValue(Cuotas_,0,true)).format('0.00')
            Interes_       = numeral(isValue(Interes_,0,true)).format('0.00')
            Plato_        = numeral(isValue(Plato_,0,true)).format('0.00')

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
        });

        btns.on('keyup', function(e){
            if(isNumberKey(e)){
                var Monto_              = $("#txtMonto").val();   
                var Plato_              = $("#txtPlazo").val();   
                var Interes_            = $("#txtInteres").val();
                var Cuotas_             = $("#txtCuotas").val();
                

                Monto_         = numeral(isValue(Monto_,0,true)).format('0.00')
                Cuotas_        = numeral(isValue(Cuotas_,0,true)).format('0.00')
                Interes_       = numeral(isValue(Interes_,0,true)).format('0.00')
                Plato_        = numeral(isValue(Plato_,0,true)).format('0.00')

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
    })

    function rmItem(IdElem){

        var vTable  = "Tbl_Clientes" ; 
        var nmCamp  = "id_clientes" ; 

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

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function eCliente(c) {
        dta_table = [];
        const Estados ={
            "1" : "ACTIVO",
            "2" : "MORA", 
            "3" : "VENCIDO",
            "4" : "INACTIVO"
        }
        const Colors ={
            "1" : "bg-success",
            "2" : "bg-danger", 
            "3" : "bg-warning",
            "4" : ""
        }

        $("#edtNombre").val(c.nombre);
        $("#edtApellido").val(c.apellidos);
        $("#edtTelefono").val(c.telefono);
        $("#edtCedula").val(c.cedula);
        $("#edtMunicipio").val(c.id_municipio).change();
        $("#edtZonas").val(c.id_zona).change();    
        $("#edtDireccion").text(c.direccion_domicilio);

        $('#mdl_edit_cliente').modal('show');
        Cliente_         = isValue(c.id_clientes,0,true);
        $("#edtIdClient").text(Cliente_);

        $.ajax({
            url: "../getAllCredit",
            type: 'post',
            data: {
                Cliente_   : Cliente_,
                _token  : "{{ csrf_token() }}" 
            },
            async: true,
            success: function(response) {
                response.forEach((response) => {
                    var span = `<span class="badge `+ Colors[response.estado_credito]+`  ">`+  (Estados[response.estado_credito])?? 'N/A' +`</span>`
                    dta_table.push({ 
                        id_creditos         : response.id_creditos,
                        fecha_apertura      : moment(response.fecha_apertura).format('DD/MM/YYYY'),
                        fecha_ultimo_abono  : moment(response.fecha_ultimo_abono).format('DD/MM/YYYY'),
                        saldo               : numeral(isValue(response.saldo,0,true)).format('0,00.00'),
                        total               : numeral(isValue(response.total,0,true)).format('0,00.00'),
                        estado_credito      : span
                    })
                });

                dta_header = [
                    {"title": "#","data": "id_creditos"},                         
                    {"title": "Fecha Apertura","data": "fecha_apertura"}, 
                    {"title": "Ultm Abono","data": "fecha_ultimo_abono"},                                     
                    {"title": "SALDO","data": "saldo"},
                    {"title": "TOTAL","data": "total"},
                    {"title": "ESTADO","data": "estado_credito"},
                    
                    {"title": "","data": "estado_credito", "render": function(data, type, row, meta) {                        
                        return`<button type="button" class="btn btn-block bg-gradient-primary" onClick="ChanceStatus(`+ row.id_creditos +`)">CAMBIAR</button>`;
                    }}
                ]

                dta_columnDefs = [
                    {"visible": false,"searchable": false,"targets": []},
                    {"className": "align-middle dt-right", "targets": []},
                    {"width": "40%","targets": [1]},
                    {"className": "align-middle dt-center", "targets": []},
                ]
                
                table_render('#tblCreditosCliente',dta_table,dta_header,dta_columnDefs,false)
                
            },
            error: function(response) {
                Swal.fire("Oops", "No se ha podido guardar!", "error");
            }
        }).done(function(data) {
            //location.reload();
        });
    }


    

    function ChanceStatus(Credito) {
        Swal.fire({
            title: 'Que estado desea Aplicar',
            input: 'select',
            inputOptions: {
                'Estados': {
                    1: 'Activo',
                    2: 'Mora',
                    3: 'Vencido',
                    4: 'Inactivo'
                },
            },
            inputPlaceholder: 'Seleccin un Estado',
            showCancelButton: true,
            inputValidator: (value) => {

                Value_         = isValue(value,0,true)
                Credi_         = isValue(Credito,0,true)

                $.ajax({
                    url: "../ChanceStatus",
                    type: 'post',
                    data: {
                        Credi_      : Credi_,
                        Value_      : Value_,
                        _token  : "{{ csrf_token() }}" 
                    },
                    async: true,
                    success: function(response) {

                        if(response){
                            Swal.fire({
                                title: 'Registro Actualizado Correctamente ' ,
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
        })
    }

    function table_render(Table,datos,Header,columnDefs,Filter){
        var userRole = $("#id_rol_user").text();
        TableExcel = $(Table).DataTable({
            "data": datos,
            "destroy": true,
            "info": false,
            responsive: true,
            "bPaginate": true,
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [7, -1],
                [7, "Todo"]
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
            'columns': Header,
            "columnDefs": columnDefs,
            "buttons": (userRole == 1) ? ["copy", "excel", "pdf"] : [ ],
            rowCallback: function( row, data, index ) {
                if ( data.Index == 'N/D' ) {
                    $(row).addClass('table-danger');
                } 
            }
        }).buttons().container().appendTo(Table+'_wrapper .col-md-6:eq(0)');
        if(!Filter){
            $(Table+"_length").hide();
            $(Table+"_filter").hide();
        }

    }


</script>