<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        $('.select2').select2()

        $('#dt-ini,#dt-end,#dtAbono').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })

        $("#btn-export-gasto").click(function(){
            var dtEnd   = $("#dtEnd").val();
            var dtIni   = $("#dtIni").val(); 

            dtEnd      = isValue(dtEnd,'N/D',true) 
            dtIni      = isValue(dtIni,'N/D',true) 

            const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
            const dt_End = moment(dtEnd, 'DD/MM/YYYY'); 

            dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
            dt_End_ = dt_End.format('YYYY-MM-DD');
            
            window.location.href = "ExportGastos?" + $.param({ dt_ini: dt_Ini_, dt_end: dt_End_ }); 
        });

        
        $("#btn-openModal-gasto").click(function(){
            $("#accion_form_gasto").html('Nuevo');
            $("#IdGasto").html('0');
            $("#txt_concepto").val('');
            $("#txt_Total_monto").val('');
            $('#modal-lg').modal('show');          
        })

        $("#btn_save_abono").click(function(){
            
                var Fecha      = $("#IdFechaGasto").val();   
                var Monto    = $("#txt_Total_monto").val();   
                var Concepto      = $("#txt_concepto").val();
                var IdGasto      = $("#IdGasto").html();
                
                        
                _Monto      = isValue(Monto,'N/D',true);
                _Concepto   = isValue(Concepto,'N/D',true);
                _Fecha      = isValue(Fecha,'N/D',true);   
                _IdGasto    = isValue(IdGasto,'0',true);

                const FechaGasto = moment(_Fecha, 'DD/MM/YYYY');

                if(_Fecha ==='N/D'|| _Monto === 'N/D' || _Concepto ==='N/D'){
                    Swal.fire("Oops", "Datos no Completos", "error");
                }else{
                    $.ajax({
                        url: "SaveGastoOperaciones",
                        type: 'post',
                        data: {
                            _Fecha      : FechaGasto.format('YYYY-MM-DD'),
                            _Monto      : _Monto,
                            _Concepto   : _Concepto,
                            _IdGasto    : _IdGasto,
                            _token      : "{{ csrf_token() }}" 
                        },
                        async: true,
                        success: function(response) {
                            if(response){
                                Swal.fire({
                                title: 'Gasto Guardado!',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        InitTable();
                                    }   
                                })
                            }
                        },
                        error: function(response) {
                            Swal.fire("Oops", "No se ha podido guardar!", "error");
                        }
                    }).done(function(data) {
                        
                    });

                
            }

        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_gastos_operaciones').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
    })

    function InitTable() {

        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val(); 

        dtEnd      = isValue(dtEnd,'N/D',true) 
        dtIni      = isValue(dtIni,'N/D',true) 

        const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
        const dt_End = moment(dtEnd, 'DD/MM/YYYY');

        var lbl_titulo_reporte = 'Del ' + dt_Ini.format('ddd, MMM DD, YYYY') + ' Al ' + dt_End.format('ddd, MMM DD, YYYY')
        

        dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
        dt_End_ = dt_End.format('YYYY-MM-DD');
        
        $("#lbl_titulo_reporte").text(lbl_titulo_reporte)
        

        $("#tbl_gastos_operaciones").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "info": false,
            "order": [[0, 'desc']],
            "lengthMenu": [[1000,-1], [1000,"Todo"]],
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
                "url": "getGastosOperaciones",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtIni   : dt_Ini_,
                    dtEnd   : dt_End_,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            "columnDefs": [
                {"className": "dt-center", "targets": [0,1,2,4,5 ]},
                {"className": "dt-right", "targets": [3]},
                { "width": "8%", "targets": [] },
                { "width": "12%", "targets": [  ] },
                { "visible":false, "searchable": false,"targets": [] }
            ],
            'columns': [
                { "title": "#", "data": "Id" },
                { "title": "CONCEPTO", "data": "Concepto" },
                { "title": "FECHA GASTO", "data": "Fecha_gasto" },
                { "title": "MONTO C$.", "data": "Monto", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { "title": "CREADO POR.", "data": "Usuario" },
                {"title": "  ","data": "Id", "render": function(data, type, row, meta) {        
                                
                    return `<div class="card-tools text-center">
                        <?php if(Auth::User()->id_rol == 1): ?>
                            <a href="#!" onClick="Editar(`+ row.Id +`)" class="btn btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#!" onClick="Remover(`+ row.Id +`)" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        <?php endif; ?>
                    </div>` ;
                }},   
            ],
            "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            var total = api.column(3, { page: 'current' }).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            total = numeral(total).format('0,0.00');
            $(api.column(3).footer()).html("C$. " + total);
            }
        })

        $("#tbl_gastos_operaciones_length").hide();
        $("#tbl_gastos_operaciones_filter").hide();
    }
    function Remover(ID) {
        $.ajax({
            url: "RemoveGasto",
            data: {
                IdGasto  : ID,
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

    function Editar(ID) {
        $.ajax({
            url: "getGasto",
            data: {
                IdGasto  : ID,
                _token  : "{{ csrf_token() }}" 
            },
            type: 'post',
            async: true,
            success: function(response) {
                $("#accion_form_gasto").html('Editar');
                $("#IdGasto").html(response[0].Id);
                $("#txt_concepto").val(response[0].Concepto);
                $("#txt_Total_monto").val(response[0].Monto);
                $("#IdFechaGasto").val(response[0].Fecha_gasto);

                $('#modal-lg').modal('show');
            },
            error: function(response) {
                swal("Oops", "No se ha podido guardar!", "error");
            }
        }).done(function(data) {
        });
        
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    

</script>