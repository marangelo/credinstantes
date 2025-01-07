<script type="text/javascript">
    $(document).ready(function () {

        InitTable();
        $('.select2').select2();

        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });
      

    
        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_ingresos').DataTable();     
            vTableArticulos.search(this.value).draw();
        });

        var btns_01 = $('#txtMonto,#txtPlazo,#txtInteres,#txtCuotas');
        btns_01.on('keyup touchend', function(e) {
            if (e.type === 'touchend' || isNumberKey(e)) {
                Calc_Credito();
            }
        });

        //abre modal para guardar credito
        $("#btn_save_credito").click(function(){

            var IdProspecto = $("#txtIdProspecto").val(); 

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
                    url: "../SaveNewProspecto",
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
        //cierra modal de guardar credito


       

    })
    $('.button_export_excel').click(() => {
        $('#tbl_ingresos').DataTable().buttons(0,0).trigger()
    })
    function ModalHistorico(IdCliente){
        
        $("#lbl_id_cliente").html(IdCliente);
        $('#modal-historico').modal('show');

        $.get( "getCreditos/" + IdCliente, function( data ) {
            
            initTable_historico('#tbl_creditos',data);

            $("#lbl_nombre_cliente").html(data[0].Nombre + ' ' + data[0].Apellidos);

        });
    }
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
    function initTable_historico(id,InfoCreditos){
        
        var tabla = $(id).DataTable({
            "data": InfoCreditos[0].Creditos,
            "destroy": true,
            "info": false,
            responsive: true,
            "bPaginate": false,
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
                "emptyTable": "-",
                "search": "BUSCAR"
            },
            'columns':  [ 
                {"title": "NUM","data": "id_creditos"},  
                {"title": "ESTADO","data": "id_creditos", "render": function(data, type, row, meta) {
                    const estilo = {
                        '1' : 'bg-success',
                        '2' : 'bg-danger',
                        '3' : 'bg-warning'
                    }
                    const clase = estilo[row.estado_credito] || ''

                    var nombre_estado = _.findWhere(InfoCreditos[0].Estados_credito, {id_estados: row.estado_credito }).nombre_estado;

                    return `<span class="badge ${clase}">${nombre_estado}</span>`
                }}, 
                {"title": "FECHA INICIO","data": "id_creditos", "render": function(data, type, row, meta) {
                    const dt = moment(row.fecha_apertura, 'YYYY/MM/DD HH:mm:ss').format('ddd, MMM DD, YYYY');  
                    return `<span class="badge rounded-pill badge-soft-info ">`+ dt	  +`</span> `
                }},
                {"title": "FECHA FINAL","data": "id_creditos", "render": function(data, type, row, meta) {
                    
                    const dt = moment(row.fecha_ultimo_abono, 'YYYY/MM/DD HH:mm:ss').format('ddd, MMM DD, YYYY');

                    return `<span class="badge rounded-pill badge-soft-info ">`+ dt +`</span> `
                }},
                {"title": "TOTAL","data": "total", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info ">`+ numeral(row.total).format('0,00.00') +`</span> `                    
                }},
                {"title": "SALDO ","data": "saldo", "render": function(data, type, row, meta) {
                    return `<span class="badge rounded-pill badge-soft-info text-success">C$  `+ numeral(row.saldo).format('0,00.00')  +`</span> `
                }},
                {"title": " - ","data": "id_creditos", "render": function(data, type, row, meta) {
                    return `<a href="CreditoPrint/${row.id_creditos}" class="btn btn-success btn-block btn-sm" onclick=""><i class="fas fa-print"></i> </a> `
                }},

            ],
        });
        $( id + "_length").hide();
        
    }
    function ModalProspecto(IdProspecto){

        $("#txtIdProspecto").val(IdProspecto);

        $.get("getInfoProspecto/" + IdProspecto, function(p) {            
            $("#txtNombre").val(p.Nombres);
            $("#txtApellido").val(p.Apellidos);
            $("#txtCedula").val(p.Cedula);
            $("#txtTelefono").val(p.Telefono);
            $("#txtDireccion").html(p.Direccion);
            $("#txtMonto").val(p.Monto_promedio	);
        });

    
        $('#modal-xl').modal('show');
    }

    function InitTable() {

        var slZna   = $("#id_select_zona option:selected").val();  
        slZna       = isValue(slZna,-1,true) 

        var lbl_titulo_reporte = 'CLIENTES PROSPECTOS';
        
        $("#lbl_titulo_reporte").text(lbl_titulo_reporte)
        

        $("#tbl_ingresos").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
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
            "ajax":{
                "url": "getProspectos",
                "type": 'POST',
                'dataSrc': '',
                "data": {
                    IdZna   : slZna,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            'columns': [
                
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {                   
                    return '[ ' + row.id_clientes + ' ] - ' +row.Nombre + ' ' + row.apellido  ;
                }},
                {"title": "CEDULA","data": "Cedula"},
                {"title": "DIRECCION","data": "Direccion"},
                {"title": "TELEFONO","data": "Telefono"},
                {"title": "ZONA","data": "Zona"},
                {"title": "FECHA","data": "Fecha_registro"},
                {"title": "MONTO","data": "Monto_promedio", "render": function(data, type, row, meta) {
                    return numeral(row.Monto_promedio).format('0,00.00');
                }},
                {"title": "CREADO POR","data": "Usuario"},
                {
                    "title": "ACCION",
                    "data": "Accion"
                }, 
               
            ],
           
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    

</script>