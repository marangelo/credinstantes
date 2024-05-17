<script type="text/javascript">
    const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
    const endOfMonth   = moment().subtract(0, "days").format("YYYY-MM-DD");
    $('[data-mask]').inputmask()
  

    var Selectors = {
        TABLE_SETTING: '#modal_new_product',
        TABLE_UPLOARD: '#modal_upload',
        TABLE_KARDEX: '#modal_kardex'
    };

    dta_table_excel = [];
    var isError = false
    var TableExcel;
   
    $(document).ready(function () {
        var labelRange = startOfMonth + " to " + endOfMonth;

        $('.custom-control-input').on('click', function() {
        
            var isChecked   = $(this).prop('checked');
            var payrollType = $(this).data('payroll-type');
            var id_employee = $("#txt_employee").val();

            console.log(id_employee,payrollType,isChecked)

            SendData(id_employee,payrollType,isChecked)
        });

        $("#btn-add-employee").click(function(){
            window.location.href = "AddEmployee" ;
        })


        $('#id_range_select').val(labelRange);

        $('#id_range_select').change(function () {
            Fechas = $(this).val().split("to");
            if(Object.keys(Fechas).length >= 2 ){
                var ArticuloID = $("#art_code").val()
                getKardexLogs(ArticuloID,Fechas[0],Fechas[1]);
            } 
        }); 

       
        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_employee').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
        $('#id_txt_excel').on('keyup', function() {    
            if(isValue(TableExcel,0,true)){
                TableExcel.search(this.value).draw();
            }
        });
        

        $("#btn_upload").click(function(){
            var addMultiRow = document.querySelector(Selectors.TABLE_UPLOARD);
            var modal = new window.bootstrap.Modal(addMultiRow);
            modal.show();
        });

        
        $("#btn_kardex").click(function(){
            var addMultiRow = document.querySelector(Selectors.TABLE_KARDEX);
            var modal = new window.bootstrap.Modal(addMultiRow);
            modal.show();
        });

        $('#frm-upload').on("change", function(e){ 
            handleFileSelect(e)
        });

        initTable('#tbl_employee');
    });

    function SendData(Employee,PayrollType,isChecked) {
        $.ajax({
            url: '../EmployeeTypePayroll',
            data: {
                Employee_       : Employee,
                PayrollType_    : PayrollType,
                isChecked_      : isChecked,
                _token  : "{{ csrf_token() }}" 
            },
            type: 'post',
            async: true,
            success: function(response) {
            
            },
            error: function(response) {
                
            }
        });
    }
    function Editar(id) {
        window.location ="EditEmployee/" + id
    }

    function Remover(id){
        Swal.fire({
            title: '¿Estas Seguro de remover el registro  ?' + id,
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
                    url: "rmEmployee",
                    data: {
                        id_  : id,
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
                        location.reload();
                    });
                },
            allowOutsideClick: () => !Swal.isLoading()
        });

    }

    
    function OpenModal(Articulo,Event){
        
        var HeaderArticulo = Articulo.DESCRIPCION 
        var FooterArticulo = Articulo.ARTICULO + " | " + Articulo.UND

        // Obtener la fecha y hora de Articulo.CREATED_AT
        const fechaArticulo = moment(Articulo.CREATED_AT);

        // Formatear la fecha y hora según el formato deseado
        const fechaFormateada = fechaArticulo.format('ddd, MMM D, YYYY h:mm');

        $("#articulos_header").text(HeaderArticulo) 
        $("#articulos_footer").text(FooterArticulo)

        var _CANTIDAD = numeral(Articulo.CANTIDAD).format('0,0.00')

        var _JUMBOS = numeral(Articulo.JUMBOS).format('0.00')
        var _FISICO = numeral(Articulo.CANTIDAD).format('0.00')

        $("#id_existencia_actual").text(_CANTIDAD  + " " + Articulo.UND) 
        $("#id_created_at").text(fechaFormateada) 

        
        
        $("#art_code").val(Articulo.ID);
        $("#id_event").val(Event);

        getKardexLogs(Articulo.ID,startOfMonth,endOfMonth)
        
        
        $("#exist_actual").val(_FISICO)
        $("#id_jumbos").val(_JUMBOS)

        var lbl = (Event == 'In')? "Kardex [ INGRESO ]" : "Kardex [ EGRESO ]" ; 

        $("#id_lbl_modal_kardex").text(lbl)
        initTable("#tblRegkardex")

        var TABLE_SETTING = document.querySelector(Selectors.TABLE_SETTING);
        var modal = new window.bootstrap.Modal(TABLE_SETTING);
        modal.show();
    }

    function initTable(id){
        $(id).DataTable({
        "destroy": true,
        "info": false,
        "bPaginate": true,
        "responsive": true, 
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            }
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
        });

        $(id+"_length").hide();
        $(id+"_filter").hide();
    }
   
    function table_render(Table,datos,Header,columnDefs,Filter){

        TableExcel = $(Table).DataTable({
            "data": datos,
            "destroy": true,
            "info": false,
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
            rowCallback: function( row, data, index ) {
                if ( data.Index == 'N/D' ) {
                    $(row).addClass('table-danger');
                } 
            }
        });
        if(!Filter){
            $(Table+"_length").hide();
            $(Table+"_filter").hide();
        }

    }
    var ExcelToJSON = function() {

        this.parseExcel = function(file) {
        var reader = new FileReader();

        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {type: 'binary'});
            dta_table_excel = [];

            workbook.SheetNames.forEach(function(sheetName) {

                isError=false;

                var worksheet = workbook.Sheets[sheetName];
                var range = XLSX.utils.decode_range('A1:I200');
                var rows = XLSX.utils.sheet_to_json(worksheet, {range: range});
                
            
                rows.forEach(function(row) {
                    var fechaEntrada 	= dtFormat(row.FECHA_ENTRADA);
                    dta_table_excel.push({
                        NOMBRES         : row.NOMBRES || 'N/D',
                        APELLIDOS	    : row.APELLIDOS || 'N/D',
                        TELEFONOS	    : row.TELEFONOS || 'N/D',
                        CEDULA		    : row.CEDULA || 'N/D',
                        INSS		    : row.INSS || 'N/D',
                        EMAIL		    : row.EMAIL || 'N/D',
                        DIRECCION	    : row.DIRECCION || 'N/D',
                        FECHA_ENTRADA	: fechaEntrada || 'N/D',
                        VACACIONES	    : numeral(row.VACACIONES).format('0,0') || 'N/D'
                    })
                    
                })

            });

            dta_table_header = [
                {"title": "NOMBRES","data": "NOMBRES"},
                {"title": "APELLIDOS","data": "APELLIDOS"}, 
                {"title": "TELEFONOS","data": "TELEFONOS"},     
                {"title": "CEDULA","data": "CEDULA"},                                     
                {"title": "INSS","data": "INSS"},
                {"title": "EMAIL","data": "EMAIL"},
                {"title": "DIRECCION","data": "DIRECCION"},
                {"title": "FECHA_ENTRADA","data": "FECHA_ENTRADA"},
                {"title": "VACACIONES","data": "VACACIONES"},
            ]

            dta_columnDefs = [
                {"className": "dt-center", "targets": []},
                {"className": "dt-right", "targets": []},
                {"visible"  : false, "searchable": false,"targets": [] }
            ]
            table_render('#tbl_excel',dta_table_excel,dta_table_header,dta_columnDefs,false)
        };

        reader.onerror = function(ex) {

        };

        reader.readAsBinaryString(file);

        };
    };
    function dtFormat(fecha) {
        return (fecha.indexOf('N/') !== -1) ? fecha : moment(fecha, 'M/D/YY').format('YYYY-MM-DD');
    }
    function ReadComment(Comentario){        
        Swal.fire({
            title: 'Comentario',
            html: Comentario,
        })
    }
    function rmKardex(ID){

        IdArticulo = $("#art_code").val();

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
                    url: "rmKardex",
                    data: {
                        id   : ID,
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

    $("#id_send_data_excel").click(function(){ 
     
        
        if(!isError){
        Swal.fire({
            title: '¿Estas Seguro de cargar  ?',
            text: "¡Se cargara la informacion previamente visualizada!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!',
            target: document.getElementById('mdlMatPrima'),
            showLoaderOnConfirm: true,
            preConfirm: () => {
                $.ajax({
                    url: "GuardarInventario",
                    data: {
                        datos   : dta_table_excel,
                        _token  : "{{ csrf_token() }}" 
                    },
                    type: 'post',
                    async: true,
                    success: function(response) {
                        if(response){
                            Swal.fire({
                                title: 'Articulos Ingresados Correctamente ' ,
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

            
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Existen articulos sin Definicion de Codigo ",
                
            })
        }
    })
 
    function handleFileSelect(evt) {    
        var files = evt.target.files;
        var xl2json = new ExcelToJSON();
        xl2json.parseExcel(files[0]);
    }

    function getKardexLogs(ArticuloID,startOfMonth,endOfMonth)
        {
            dta_table_kardex = [];
            $.ajax({
            url: "postKardex",
            data: {
                ArticuloID   : ArticuloID,
                DateStart   : startOfMonth,
                DateEnd   : endOfMonth,
                _token  : "{{ csrf_token() }}" 
            },
            type: 'post',
            async: true,
            success: function(response) {
                if(response){

                    response.forEach((response) => {

                        var _ENTRADA = numeral(response.ENTRADA).format('0,0.00')
                        var _SALIDA = numeral(response.SALIDA).format('0,0.00')
                        var _STOCK = numeral(response.STOCK).format('0,0.00')

                        dta_table_kardex.push({ 
                            ID: response.ID,
                            TIPO_MOVI       : response.TIPO_MOVIMIENTO,
                            FECHA           : response.FECHA,
                            _ENTRADA        :_ENTRADA,
                            _SALIDA         : _SALIDA,
                            _STOCK          : _STOCK,
                            OBSERVACION     : response.OBSERVACION
                        })
                        


                    });

                    dta_header_kardex = [
                        {"title": "ID","data": "ID"}, 
                        {"title": "","data": "ID", "render": function(data, type, row, meta) {

                        var evColor = (row.TIPO_MOVI == 'In')? 'success' : 'warning' ;
                        var evLabel = (row.TIPO_MOVI == 'In')? 'Ingreso ' : 'Egreso' ;
                        var eLetter = (row.TIPO_MOVI == 'In')? 'I ' : 'E' ;
                        var eIcon   = (row.TIPO_MOVI == 'In')? 'fas fa-arrow-left' : 'fas fa-arrow-right' ;
                        var isComm  = '';
                        var showCom = '';

                        if (isValue(row.OBSERVACION,'N/D',true)=='N/D') {
                            isComm  = 'd-none'
                            showCom = ''
                        } else {
                            isComm  = ''
                            showCom = row.OBSERVACION.replace(/\n/g, "");
                        }

                       

                        return`<tr>
                                <td class="log">
                                    <div class="d-flex align-items-center position-relative">
                                        <div class="flex-1">
                                            <p class="text-500 fs--2 mb-0">Numero de Registro # ` + row.ID + ` | <span class="badge badge rounded-pill badge-soft-` + evColor + `">` + evLabel + `<span class="ms-1 ` +eIcon + `" data-fa-transform="shrink-2"></span></span> </p>
                                            <h6 class="mb-0 fw-semi-bold">` + evLabel + ` al inventario registrada</h6>
                                            <p class="text-700 fs--1 mb-0">` + row.FECHA + ` &bull; <span class="fas fa-trash-alt" onclick="rmKardex(` + row.ID + `)"></span> &bull; <span class="fas fa-comment ` + isComm + `" onclick="ReadComment(` + "'" +showCom + "'" + `)"></span></p> 
                                       
                                        </div>
                                    </div>
                                </td>
                                
                            </tr>`

                    }},
                    {"title": "Entrada","data": "_ENTRADA"}, 
                    {"title": "Salida","data": "_SALIDA"},                                     
                    {"title": "Saldo","data": "_STOCK"},
                ]

                dta_columnDefs = [
                    {"visible": false,"searchable": false,"targets": [0]},
                    {"className": "align-middle dt-right", "targets": [2,3,4]},
                    {"width": "40%","targets": [1]},
                    {"className": "align-middle dt-center", "targets": [2]},
                ]
                table_render('#tblRegkardex',dta_table_kardex,dta_header_kardex,dta_columnDefs,false)


                    

                }
                },
            error: function(response) {
                //Swal.fire("Oops", "No se ha podido guardar!", "error");
            }
            }).done(function(data) {
                //CargarDatos(nMes,annio);
            });

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
</script>