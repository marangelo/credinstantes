<script type="text/javascript">
    $(document).ready(function () {
        $('#dt-arqueo').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        var var_tbl_moneda_nio = $('#tbl_moneda_nio').DataTable({
            "responsive": false, 
            "lengthChange": false, 
            "bPaginate": false,
            "autoWidth": false,
            "searching": false,
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
        });
        var var_tbl_moneda_usd = $('#tbl_moneda_usd').DataTable(
            {
            "responsive": false, 
            "bPaginate": false,
            "searching": false,
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
        }
        );

        $('#tbl_moneda_nio ').on('click', "td", function() {
            var dtaRow = var_tbl_moneda_nio.row(this).data();
            var visIdx = $(this).index();
            Swal.fire({
                title: dtaRow[1],
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
                inputValue: dtaRow[2],
                inputValidator: (value) => {
                    if (!value) {
                        return 'Digita la cantidad por favor';
                    }

                    if (isNaN(value)) {
                        return 'Formato incorrecto';
                    } else {
                        // $.ajax({
                        //     url: "../GuardarTiempoParo",
                        //     data: {
                        //         id_tipo_tiempo_paro: dtaRow['ID_ROW'],
                        //         num_orden: num_orden,
                        //         cantidad: value,
                        //         idturno: idturno
                        //     },
                        //     type: 'post',
                        //     async: true,
                        //     success: function(response) {
                        //         //  swal("Exito!", "Guardado exitosamente", "success");
                        //     },
                        //     error: function(response) {
                        //         swal("Oops", "No se ha podido guardar!", "error");
                        //     }
                        // }).done(function(data) {
                        //     detalles_tiempos_paros();
                        // });
                    }
                }
            })


            


        })
        $('#tbl_moneda_usd ').on('click', "td", function() {

            var dtaRow = var_tbl_moneda_usd.row(this).data();
            var visIdx = $(this).index();
            Swal.fire({
                title: dtaRow[1],
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
                inputValue: dtaRow[2],
                inputValidator: (value) => {
                    if (!value) {
                        return 'Digita la cantidad por favor';
                    }

                    if (isNaN(value)) {
                        return 'Formato incorrecto';
                    } else {
                        // $.ajax({
                        //     url: "../GuardarTiempoParo",
                        //     data: {
                        //         id_tipo_tiempo_paro: dtaRow['ID_ROW'],
                        //         num_orden: num_orden,
                        //         cantidad: value,
                        //         idturno: idturno
                        //     },
                        //     type: 'post',
                        //     async: true,
                        //     success: function(response) {
                        //         //  swal("Exito!", "Guardado exitosamente", "success");
                        //     },
                        //     error: function(response) {
                        //         swal("Oops", "No se ha podido guardar!", "error");
                        //     }
                        // }).done(function(data) {
                        //     detalles_tiempos_paros();
                        // });
                    }
                }
            })

        })


    })
    
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
   
    

</script>