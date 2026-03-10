<script type="text/javascript">
    var var_tbl_moneda_nio;
    var var_tbl_moneda_usd;

    var moneda_tc        = $("#lbl_moneda_tc").text();

    let ttNIO;
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('.select2').select2()

    $(document).ready(function () {

        InitTable();
        $('#dt-arqueo,#dtAbono').datetimepicker({
            format: 'DD/MM/YYYY',
            defaultDate: new Date()
        });

        $("#dt-arqueo").on("change.datetimepicker", ({date, oldDate}) => {
            // console.log("New date", date);
            // console.log("Old date", oldDate);

            var Arqueo = $("#id_moneda").text();
            var dtIni  = $("#dtIni").val();
            dtIni_     = moment(dtIni, 'DD/MM/YYYY');


            $.ajax({
                url: "UpdateRecuperado",
                data: {
                    Arqueo  : Arqueo,
                    Fecha   : dtIni_.format('YYYY-MM-DD'),
                    _token  : "{{ csrf_token() }}" 
                },
                type: 'post',
                async: true,
                success: function(response) {
                    var ttRecuperado = numeral(isValue(response,0,true)).format('0,00.00')
                    $('#id_total_sistema').val(ttRecuperado)
                    //InitTable();
                    UpdateTotal();
                },
                error: function(response) {
                    swal("Oops", "No se ha podido guardar!", "error");
                }
            }).done(function(data) {
            });
        })

        $('#tbl_moneda_nio ').on('click', "td", function() {
            var dtaRow = var_tbl_moneda_nio.row(this).data();
            var visIdx = $(this).index();

            var Denomi         = dtaRow.denominacion;
            var Denomi_        = numeral(isValue(Denomi,0,true)).format('0,00.00')
            var canti_         = numeral(isValue(dtaRow.cantidad,0,true)).format('0.00')            
            var IdArqueo       = dtaRow.Id;
            var Linea          = dtaRow.Linea;


            Swal.fire({
                title: "C$. " + Denomi_,
                text: "Digitar valor a ingresar " ,
                input: 'text',
                inputPlaceholder: 'Digite la cantidad',
                inputAttributes: {
                    id: 'cantidad',
                    required: 'true',
                    onkeypress: 'soloNumeros(event.keyCode, event, $(this).val())'
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Digita la cantidad por favor';
                    }

                    if (isNaN(value)) {
                        return 'Formato incorrecto';
                    } else {
                        $.ajax({
                            url: "UpdateRowArqueo",
                            data: {
                                Moneda  : 'NIO',
                                Arqueo  : IdArqueo,
                                Linea   : Linea,
                                Cantidad : value,
                                Denomi  : Denomi,
                                TC      : moneda_tc,
                                _token  : "{{ csrf_token() }}" 
                            },
                            type: 'post',
                            async: true,
                            success: function(response) {
                                InitTable();
                            },
                            error: function(response) {
                                swal("Oops", "No se ha podido guardar!", "error");
                            }
                        }).done(function(data) {
                        });
                    }
                }
            })
        })
        $('#tbl_moneda_usd ').on('click', "td", function() {
            var dtaRow = var_tbl_moneda_usd.row(this).data();
            var visIdx = $(this).index();
            
            var Denomi         = dtaRow.denominacion;
            var Denomi_        = numeral(isValue(Denomi,0,true)).format('0,00.00')
            var canti_         = numeral(isValue(dtaRow.cantidad,0,true)).format('0.00')            
            var IdArqueo       = dtaRow.Id;
            var Linea          = dtaRow.Linea;

            Swal.fire({
                title: "$. " + Denomi_,
                text: "Digitar valor a ingresar " ,
                input: 'text',
                target: document.getElementById('mdlHorasParo'),
                inputPlaceholder: 'Digite la cantidad',
                inputAttributes: {
                    id: 'cantidad',
                    required: 'true',
                    onkeypress: 'soloNumeros(event.keyCode, event, $(this).val())'
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Digita la cantidad por favor';
                    }

                    if (isNaN(value)) {
                        return 'Formato incorrecto';
                    } else {
                        $.ajax({
                            url: "UpdateRowArqueo",
                            data: {
                                Moneda  : 'USD',
                                Arqueo  : IdArqueo,
                                Linea   : Linea,
                                Cantidad : value,
                                Denomi  : Denomi,
                                TC      : moneda_tc,
                                _token  : "{{ csrf_token() }}" 
                            },
                            type: 'post',
                            async: true,
                            success: function(response) {
                                InitTable();
                            },
                            error: function(response) {
                                swal("Oops", "No se ha podido guardar!", "error");
                            }
                        }).done(function(data) {
                        });
                    }
                }
            })
        })

        $("#bt_save_arqueo").click(function(){      
            SaveForm();      
            
        })

        


        $("#btn_save_desembolso").on("click", function() 
        {
            var lbl = $("#lbl_deposito_dia").html();    
            var IdArqueo = $("#id_moneda").text();     
            
            var data = {
                Path: "../SaveDesembolso",
                Arqueo: IdArqueo,
                SelectCliente: $("#name_cliente").val(),
                Monto: $("#txt_desembolso").val()
            };

            UpTransacciones(data).then(function(res) {
                if (res.original.success) {
                    Toast.fire({
                        icon: 'success',
                        title: res.original.message
                    });
                    InitDataDesembolso( IdArqueo, lbl);
                    SaveForm();
                }
            });

            
        })

        $("#btn_save_transferencia").on("click", function() 
        {
            var lbl = $("#lbl_deposito_tranferencia").html();     
            var IdArqueo = $("#id_moneda").text();  

            var data = {
                Path: "../SaveTransferencia",
                Arqueo: IdArqueo,
                SelectCuenta: $("#id_select_transferencia").val(),
                Monto: $("#txt_transferencia").val(),
                Referencia: $("#txt_referencia_transferencia").val()
            };
            UpTransacciones(data).then(function(res) {
                if (res.original.success) {
                    Toast.fire({
                        icon: 'success',
                        title: res.original.message
                    });
                    InitDataTransferencias( IdArqueo, lbl);
                    SaveForm();
                }
            });
            
        })

        $("#btn_save_deposito").on("click", function() 
        {
            var lbl = $("#lbl_gastos").html();
            var IdArqueo = $("#id_moneda").text();
            var FechaDeposito = $("#fecha_deposito").val()

            var data = {
                Path: "../SaveDeposito",
                Arqueo: $("#id_moneda").text(),
                SelectCliente: $("#id_select_cliente_deposito").val(),
                SelectCuenta: $("#id_select_cuenta_deposito").val(),
                Monto: $("#txt_deposito_monto").val(),
                Referencia: $("#txt_referencia_deposito").val(),
                FechaDeposito: moment(FechaDeposito, 'DD/MM/YYYY').format('YYYY-MM-DD hh:mm')
            };

            UpTransacciones(data).then(function(res) {
                if (res.original.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Acción guardada.'
                    });
                    InitDataDepositos( IdArqueo, lbl);
                    SaveForm()
                }
            });
        })

        $("#btn_add_recuperacion").on("click", function() 
        {
            var lbl = $("#lbl_deposito_dia").html();    
            var IdArqueo = $("#id_moneda").text();      
            InitDataDesembolso( IdArqueo, lbl) ;
            
        })
        
        $("#btn_dep_transfer").on("click", function() 
        {
            var lbl = $("#lbl_deposito_tranferencia").html();            
            var IdArqueo = $("#id_moneda").text();      
            
            InitDataTransferencias( IdArqueo, lbl) 
        })

        $("#btn_dep_cliente").on("click", function() 
        {
            var lbl = $("#lbl_gastos").html();
            var IdArqueo = $("#id_moneda").text();
            InitDataDepositos( IdArqueo, lbl);
            

        })

        $('#mdl-form-extra-lg').on('hidden.bs.modal', function () {
            $('#custom-content-below-tab .nav-item').show();
        });

        

        
    })

    function SaveForm() {

        var IdArqueo = $("#id_moneda").text();

        var dtIni               = $("#dtIni").val();
        var txt_deposito_dia    = $("#txt_deposito_dia").val();
        var txt_tranferencia    = $("#txt_deposito_tranferencia").val();
        var total_SYS           = $('#id_total_sistema').val();

        var txt_gastos          = $("#txt_gastos").val();
        var txt_commit          = $("#id_commit").val();

        dtIni_                  = moment(dtIni, 'DD/MM/YYYY');
        txt_deposito_dia_       = numeral(isValue(txt_deposito_dia,0,true)).format('0.00')
        txt_tranferencia_       = numeral(isValue(txt_tranferencia,0,true)).format('0.00')
        txt_gastos_             = numeral(isValue(txt_gastos,0,true)).format('0.00')
        total_SYS_             = numeral(isValue(total_SYS,0,true)).format('0.00')

        $.ajax({
            url: "UpdateArqueo",
            data: {
                Arqueo  : IdArqueo,
                Fecha   : dtIni_.format('YYYY-MM-DD'),
                Deposit : txt_deposito_dia_,
                Tranfe  : txt_tranferencia_,
                Gastos  : txt_gastos_,
                Commit  : txt_commit,
                ttSYS   : total_SYS_,
                _token  : "{{ csrf_token() }}" 
            },
            type: 'post',
            async: true,
            success: function(res) {
                Toast.fire({
                    icon: 'success',
                    title: res.original.message
                })
                UpdateTotal();
            },
            error: function(response) {
                swal("Oops", "No se ha podido guardar!", "error");
            }
        }).done(function(data) {
        });
        
    }

    

    async function UpTransacciones(data) {
        try {
            const response = await fetch(data.Path, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            return result;
            

        } catch (error) {
            console.error(error);
            return { success: false, error };
        }
        
    }

    async function removeDesembolso(IdTransaccion) {
        try {
            await DownTransacciones(IdTransaccion, "../DownDesembolso");

            var lbl = $("#lbl_deposito_tranferencia").html();     
            var IdArqueo = $("#id_moneda").text();        

            InitDataDesembolso(IdArqueo, lbl);
        } catch (error) {
            console.error("Error al eliminar desembolso:", error);
        }
    }
    async function removeTransferencia(IdTransaccion) {
        try {
            await DownTransacciones(IdTransaccion, "../DownTransferencia");

            var lbl = $("#lbl_deposito_dia").html();    
            var IdArqueo = $("#id_moneda").text();      

            InitDataTransferencias(IdArqueo, lbl);
        } catch (error) {
            console.error("Error al eliminar desembolso:", error);
        }
    }
    async function removeDeposito(IdTransaccion) {
        try {
            await DownTransacciones(IdTransaccion, "../DownDeposito");

            var lbl = $("#lbl_gastos").html();
            var IdArqueo = $("#id_moneda").text();     

            InitDataDepositos(IdArqueo, lbl);
        } catch (error) {
            console.error("Error al eliminar desembolso:", error);
        }
    }

    async function DownTransacciones(IdTransaccion, Path) {
        try {
            const response = await fetch(Path, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    IdTransaccion     : IdTransaccion
                })
            });

            const result = await response.json();
            Toast.fire({ icon: 'error', title: 'Accion Eliminada.' });
            

        } catch (error) {
            console.error(error);
        }
        
    }

    async function getData(Path,Arqueo, lbl, columns, callback = null, ttTransaccion,btnResumen) {
        try {
            const response = await fetch(Path, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    Arqueo     : Arqueo
                })
            });

            const result = await response.json();

            mostrarSoloTab(callback);

            InitDataTable(
                columns,
                result.original.data
            );


            if(typeof result.original.Comentarios !== 'undefined'){
                setCommit(result.original.Comentarios);
            }

            $(ttTransaccion).html('( ' + numeral(isValue(result.original.Total,2,true)).format('0,00.00') + ' )');
            $(btnResumen).val(numeral(isValue(result.original.Total,2,true)).format('0.00'));


            OpenModal(lbl); 

        } catch (error) {
            console.error(error);
        }
    }

    function setCommit(commit) {

        var str_commit = "";
        $("#id_commit").val(str_commit);


        $("#id_commit").val(commit);

        
    }

    function InitDataDesembolso( IdArqueo, lbl) 
    {
        getData(
            '../getDesembolso',
            IdArqueo, 
            lbl,
            [
                { "data": "id", "title": "ID" },
                { "data": "nombre_cliente", "title": "CLIENTE" },
                { "data": "monto", "title": "MONTO C$." , render: $.fn.dataTable.render.number( ',', '.', 2  ) },
                { "data": "accion", "title": " - " }
            ],
            [
                'custom-content-desembolso-tab',
                'custom-content-desembolso'
            ],
            "#total_desembolso",
            "#txt_deposito_dia"
        );
    }
    function InitDataTransferencias( IdArqueo, lbl) 
    {
        getData(
            '../getTransferencias',
            IdArqueo, 
            lbl,
            [
                { "data": "id", "title": "ID" },
                { "data": "cuenta", "title": "CUENTA" },
                { "data": "monto", "title": "MONTO C$.", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
                { "data": "refe", "title": "REFERENCIA" },
                { "data": "accion", "title": " - " }
            ],
            [
                'custom-content-transferencias-tab',
                'custom-content-transferencias'
            ],
            "#total_transferencia",
            "#txt_deposito_tranferencia"
        );
    }

    function InitDataDepositos( IdArqueo, lbl) 
    {
        getData(
            '../getDepositos',
            IdArqueo, 
            lbl,
            [
                { "data": "id", "title": "ID" },
                { "data": "FECHA", "title": "FECHA DEP.", render: function(data,type,row){
                    return moment(data).format('D MMM YYYY');
                } },
                { "data": "nombre_cliente", "title": "CLIENTE" },
                { "data": "cuenta_bancaria", "title": "CUENTA" },
                { "data": "monto", "title": "MONTO C$.", render: $.fn.dataTable.render.number( ',', '.', 2   ) },
                { "data": "referencias", "title": "REFERENCIA" },
                { "data": "accion", "title": " - " }
            ],
            [
                'custom-content-depositos-tab',
                'custom-content-depositos'
            ],
            "#total_depositos",
            "#txt_gastos"
        );
    }
    function InitDataTable(columns, data = []) {

        // destruir si existe
        if ($.fn.DataTable.isDataTable('#tbl_deposiciones')) {
            $('#tbl_deposiciones').DataTable().destroy();
        }

        // reconstruir thead
        let thead = "<thead><tr>";
        columns.forEach(col => {
            thead += `<th>${col.title || ''}</th>`;
        });

        thead += "</tr></thead>";
        $("#tbl_deposiciones").html(thead + "<tbody></tbody>");

        $("#tbl_deposiciones").DataTable({
            destroy: true,
            data: data,
            columns: columns,
            autoWidth: false,
            lengthChange: false,
            info: false,
            paging: false,
            searching: false,
            ordering: columns.length > 0, // evita error si no hay columnas
            order: columns.length > 0 ? [[0, 'desc']] : [],
            language: {
                zeroRecords: "NO HAY COINCIDENCIAS",
                emptyTable: "NO HAY COINCIDENCIAS",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            columnDefs: columns.length > 0 ? [
                { targets: 0, visible: false }
            ] : []
        });
    }



    function OpenModal(Titulos) 
    {
        $('.modal-title').html(Titulos);
        $('#mdl-form-extra-lg').modal('show');
    }

    

    function mostrarSoloTab(ArrayTabPane) {
        
        var tabId = ArrayTabPane[0];
        var paneId = ArrayTabPane[1];

        // 1. Ocultar todas las pestañas
        $('#custom-content-below-tab .nav-item').hide();

        // 2. Reset tabs
        $('#custom-content-below-tab .nav-link')
            .removeClass('active')
            .attr('aria-selected', 'false');

        // 3. Reset panes
        $('.tab-pane')
            .removeClass('show active');

        // 4. Mostrar y activar tab
        $('#' + tabId)
            .closest('.nav-item')
            .show();

        $('#' + tabId)
            .addClass('active')
            .attr('aria-selected', 'true');

        // 5. Mostrar y activar pane
        $('#' + paneId)
            .addClass('show active');
    }

    function UpdateTotal() {

        var txt_deposito_dia    = $("#txt_deposito_dia").val();
        var txt_tranferencia    = $("#txt_deposito_tranferencia").val();
        var txt_gastos          = $("#txt_gastos").val();

        var total_NIO           = $('#ID_TOTAL_NIO').html();
        var total_USD           = $('#id_lbl_total_usd').html();

        var total_SYS           = $('#id_total_sistema').val();

        txt_deposito_dia_       = numeral(isValue(txt_deposito_dia,0,true)).format('0.00')
        txt_tranferencia_       = numeral(isValue(txt_tranferencia,0,true)).format('0.00')
        txt_gastos_             = numeral(isValue(txt_gastos,0,true)).format('0.00')
        total_SYS_              = numeral(isValue(total_SYS,0,true)).format('0.00')
        total_NIO_              = numeral(isValue(total_NIO,0,true)).format('0.00')
        total_USD_              = numeral(isValue(total_USD,0,true)).format('0.00')

        
        var TOTAL_FINAL         = parseFloat(total_NIO_) + parseFloat(total_USD_) + parseFloat(txt_deposito_dia_) + parseFloat(txt_tranferencia_) + parseFloat(txt_gastos_)

        var TOTAL_SYS_VS_CASH   = parseFloat(numeral(TOTAL_FINAL).format("0.00")) - parseFloat(numeral(total_SYS).format("0.00"))

        //TOTAL_SYS_VS_CASH = (TOTAL_SYS_VS_CASH < 0) ? 0 : TOTAL_SYS_VS_CASH ;

        $('#id_lbl_total_final').html(numeral(TOTAL_FINAL).format("0,00.00"));
        
        $('#TOTAL_SYS_VS_CASH').html(numeral(TOTAL_SYS_VS_CASH).format("0,00.00"));
        
    }

    function InitTable(){
        var IdArqueo = $("#id_moneda").text();



        var_tbl_moneda_nio =  $("#tbl_moneda_nio").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "searching": false,
            "info": false,
            "bPaginate": false,
            "order": [[0, 'asc']],
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
                "url": "DataTableMoneda",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    Moneda  : "NIO",
                    Id      : IdArqueo,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0]},
                {"className": "dt-right", "targets": [1,2,3]},
                { "visible":false,"targets": [0] }
            ],
            'columns': [
                {
                    "title": "#",
                    "data": "Linea",
                }, 
                {
                    "title": "DENOMINACION",
                    "data": "denominacion",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "CANTIDAD",
                    "data": "cantidad",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },      
                {
                    "title": "TOTAL",
                    "data": "total",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },   
            ],
            "initComplete": function( settings, json ) {
                UpdateTotal();
            },
            "fnDrawCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                };
                TOTAL = api.column( 3 ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                TOTAL         = numeral(isValue(TOTAL,0,true)).format('0,00.00')

                ttNIO = TOTAL

                $('#ID_TOTAL_NIO').html(TOTAL);
            }
        })

        var_tbl_moneda_usd = $("#tbl_moneda_usd").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "searching": false,
            "info": false,
            "bPaginate": false,
            "order": [[0, 'asc']],
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
                "url": "DataTableMoneda",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    Moneda  : "USD",
                    Id      : IdArqueo,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0]},
                {"className": "dt-right", "targets": [1,2,3]},
                { "visible":false,"targets": [0] }
            ],
            'columns': [
                {
                    "title": "#",
                    "data": "Linea",
                }, 
                {
                    "title": "DENOMINACION",
                    "data": "denominacion",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "CANTIDAD",
                    "data": "cantidad",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },      
                {
                    "title": "TOTAL",
                    "data": "total",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },   
            ],
            "initComplete": function( settings, json ) {
                UpdateTotal();
            },
            "fnDrawCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                };
                TOTAL = api.column( 3 ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                TOTAL         = numeral(isValue(TOTAL,0,true)).format('0,00.00')

                $('#id_lbl_total_usd').html(TOTAL);
            }
        })
    }
    
    function soloNumeros(caracter, e, numeroVal) {
        var numero = numeroVal;
        if (String.fromCharCode(caracter) === "." && numero.length === 0) {
            e.preventDefault();
            swal.showValidationMessage('No se puede iniciar con un punto');
        } else if (numero.includes(".") && String.fromCharCode(caracter) === ".") {
            e.preventDefault();
            swal.showValidationMessage('No puede haber mas de dos puntos');
        } else {
            const soloNumeros = new RegExp("^[0-9]+$");
            if (!soloNumeros.test(String.fromCharCode(caracter)) && !(String.fromCharCode(caracter) === ".")) {
                e.preventDefault();
                swal.showValidationMessage(
                    'No se pueden escribir letras, solo se permiten datos númericos'
                );
            }
        }
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }


</script>