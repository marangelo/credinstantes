<script type="text/javascript">

    $(document).ready(function () {
        CalcIndicadores();
        $('[data-mask]').inputmask();
        $('#reservationdate').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $('#tbl_cliente_promotor_buscar').on('keyup', function() {        
            var vTableFavorito = $('#tbl_cliente_promotor').DataTable();
            vTableFavorito.search(this.value).draw();
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

        $("#btn_save_credito").click(function(){

                var DateOPen      = $("#dtApertura").val(); 
                const fechaAnalizada = moment(DateOPen, 'DD/MM/YYYY');

                var DiaSemana_  = $("#slDiaVisita option:selected").val();  
                var Municipio_  = $("#selMunicipio option:selected").val();  
                var Zona_        = $("#selZona option:selected").val();

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
                        Zona_        : Zona_,
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


        

        $("#IdFilterByZone").change(function() {    
            CalcIndicadores();
        });
        
    });
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function CalcIndicadores(){
    
        var vLabel = []
        var vData = []

        var Opt   = $("#IdFilterByZone option:selected").val(); 
        
        Opt      = isValue(Opt,-1,true)       
        
        //$("#IdCardTitle").text("Calculando . . . ") 

        $.getJSON("getDashboardPromotor/"+Opt, function(dataset) {
        
            var CLIENTES_NUEVO = dataset['CLIENTES_NUEVO'];

            CLIENTES_NUEVO     = numeral(isValue(CLIENTES_NUEVO,0,true)).format('0.00');
        
            $("#lblClientesNuevos").text(CLIENTES_NUEVO);

            var RE_PRESTAMOS = dataset['RE_PRESTAMOS'];
            RE_PRESTAMOS     = numeral(isValue(RE_PRESTAMOS,0,true)).format('0.00');
            $("#lblRePrestamo").text(RE_PRESTAMOS);
        
            var SALDOS_COLOCADOS = dataset['SALDOS_COLOCADOS'];
            SALDOS_COLOCADOS     = numeral(isValue(SALDOS_COLOCADOS,0,true)).format('0,00.00');
            $("#lblSaldosColocados").text(SALDOS_COLOCADOS);

            TableClientes(dataset['LISTA_CLIENTES']);
        
        
        
        })
    }
    function TableClientes(LISTA_CLIENTES) {
        $('#tbl_cliente_promotor').DataTable({
            "data": LISTA_CLIENTES,
            "paging": true,
            "destroy": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
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
            'columns': [
                {"title": "#","data"  : "id_clientes"},
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {
                    return `<a href="Perfil/`+row.id_clientes+`"><strong>#`+row.id_clientes+` </strong> : `+ row.Nombre +` `+row.Apellidos+` </a> ` 
                }},                               
                {"title": "DEPARTAMENTO","data" : "Departamento"},
                {"title": "ZONA","data"         : "Zona"},
                {"title": "DIRECCION","data"    : "Direccion"},
                {"title": "ACCION","data"       : "Accion"},
            ],
            "columnDefs": [{"className": "", "targets": [ ]},],
        });  
        $("#tbl_cliente_promotor_length").hide();
        $("#tbl_cliente_promotor_filter").hide();
    }


</script>