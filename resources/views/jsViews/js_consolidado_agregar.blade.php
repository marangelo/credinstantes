<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        $('.select2').select2()

        $('#dt-end,#dtAbono').datetimepicker({
            format: 'DD/MM/YYYY'
        })


        $("#dt-end").on("change.datetimepicker", ({date,oldDate}) => {
            InitTable();
        })
                

      
        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })
        
        $("#btn-openModal-gasto").click(function(){
            $("#accion_form_gasto").html('Nuevo');
            $("#IdGasto").html('0');
            $("#txt_concepto").val('');
            $("#txt_Total_monto").val('');       
        })

        $("#btn_save_abono").click(function(){
            
                var Fecha       = isValue($("#dtEnd").val(),'N/D',true);   
                var Monto       = isValue($("#id_txt_valor").val(),0,true) ;   
                var Indicador   = isValue($("#id_select_indicadores").val(),'N/D',true);

                const dtIndicador = moment(Fecha, 'DD/MM/YYYY');

                if(dtIndicador === 'N/D' || Monto === 0 || Indicador ==='N/D'){
                    Swal.fire("Oops", "Datos no Completos", "error");
                }else{
                    $.ajax({
                        url: "SaveIndiador",
                        type: 'post',
                        data: {
                            _Fecha      : dtIndicador.format('YYYY-MM-DD'),
                            _Monto      : Monto,
                            _Indicador  : Indicador,
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

        });

        
    })

    function InitTable() {

        var dtEnd   = $("#dtEnd").val(); 

        dtEnd      = isValue(dtEnd,'N/D',true) 

        const dt_End = moment(dtEnd, 'DD/MM/YYYY');

      
        dt_End_ = dt_End.format('YYYY-MM-DD');
        
        

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
                "url": "getIndicadores",
                "type": 'POST',
                'dataSrc': '',
                "data": {             
                    dtEnd   : dt_End_,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            "columnDefs": [
                {"className": "dt-center", "targets": [0,1,2,4 ]},
                {"className": "dt-right", "targets": [3]},
                { "width": "8%", "targets": [] },
                { "width": "12%", "targets": [  ] },
                { "visible":false, "searchable": false,"targets": [] }
            ],
            'columns': [
                { "title": "#", "data": "Id" },
                { "title": "CONCEPTO", "data": "Concepto" },
                { "title": "FECHA", "data": "Fecha_gasto" },
                { "title": "MONTO C$.", "data": "Monto", render: $.fn.dataTable.render.number(',', '.', 2, '') },
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
            url: "RemoveIndicador",
            data: {
                IdIndicador  : ID,
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
            url: "getInfoIndicador",
            data: {
                IdConso  : ID,
                _token  : "{{ csrf_token() }}" 
            },
            type: 'post',
            async: true,
            success: function(response) {

                $("#id_select_indicadores").val(response.Concepto).change();    
                $("#id_txt_valor").val(response.Monto);
                $("#dtEnd").val(response.Fecha);

             
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