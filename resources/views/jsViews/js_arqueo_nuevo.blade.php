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

    $(document).ready(function () {

        InitTable();
       
        $('#dt-arqueo').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        

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
                inputValue: canti_,
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
                inputValue: canti_,
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
            var IdArqueo = $("#id_moneda").text();

            var dtIni               = $("#dtIni").val();
            var txt_deposito_dia    = $("#txt_deposito_dia").val();
            var txt_tranferencia    = $("#txt_deposito_tranferencia").val();
            var txt_gastos          = $("#txt_gastos").val();

            dtIni_                  = moment(dtIni, 'DD/MM/YYYY');
            txt_deposito_dia_       = numeral(isValue(txt_deposito_dia,0,true)).format('0.00')
            txt_tranferencia_       = numeral(isValue(txt_tranferencia,0,true)).format('0.00')
            txt_gastos_             = numeral(isValue(txt_gastos,0,true)).format('0.00')

            $.ajax({
                url: "UpdateArqueo",
                data: {
                    Arqueo  : IdArqueo,
                    Fecha   : dtIni_.format('YYYY-MM-DD'),
                    Deposit : txt_deposito_dia_,
                    Tranfe  : txt_tranferencia_,
                    Gastos  : txt_gastos_,
                    _token  : "{{ csrf_token() }}" 
                },
                type: 'post',
                async: true,
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Informacion Guardada.'
                    })
                    UpdateTotal();
                },
                error: function(response) {
                    swal("Oops", "No se ha podido guardar!", "error");
                }
            }).done(function(data) {
            });
        })

        
    })

    function UpdateTotal() {

        var txt_deposito_dia    = $("#txt_deposito_dia").val();
        var txt_tranferencia    = $("#txt_deposito_tranferencia").val();
        var txt_gastos          = $("#txt_gastos").val();

        var total_NIO           = $('#ID_TOTAL_NIO').html();
        var total_USD           = $('#id_lbl_total_usd').html();

        var total_SYS           = $('#id_total_sistema').html();

        txt_deposito_dia_       = numeral(isValue(txt_deposito_dia,0,true)).format('0.00')
        txt_tranferencia_       = numeral(isValue(txt_tranferencia,0,true)).format('0.00')
        txt_gastos_             = numeral(isValue(txt_gastos,0,true)).format('0.00')
        total_SYS_              = numeral(isValue(total_SYS,0,true)).format('0.00')
        total_NIO_              = numeral(isValue(total_NIO,0,true)).format('0.00')
        total_USD_              = numeral(isValue(total_USD,0,true)).format('0.00')

        
        var TOTAL_FINAL         = parseFloat(total_NIO_) + parseFloat(total_USD_) + parseFloat(txt_deposito_dia_) + parseFloat(txt_tranferencia_) + parseFloat(txt_gastos_)

        var TOTAL_SYS_VS_CASH   = parseFloat(numeral(TOTAL_FINAL).format("0.00")) - parseFloat(numeral(total_SYS).format("0.00"))

        TOTAL_SYS_VS_CASH = (TOTAL_SYS_VS_CASH < 0) ? 0 : TOTAL_SYS_VS_CASH ;

        $('#id_lbl_total_final').html(numeral(TOTAL_FINAL).format("0,00.00"));
        $('#TOTAL_SYS_VS_CASH').html(numeral(TOTAL_SYS_VS_CASH).format("0,00.00"));
        
    }

    function InitTable(){
        var IdArqueo = $("#id_moneda").text();

        var TOTAL_SISTEMA = numeral(3000).format("0,00.00");
        $('#id_total_sistema').html(TOTAL_SISTEMA);

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