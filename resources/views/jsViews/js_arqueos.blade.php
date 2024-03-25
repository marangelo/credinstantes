<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        $('.select2').select2()

        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })
        $("#btn-add-arqueo").click(function(){
            var Zona_Name   = $("#id_select_zona option:selected").text();  
            Swal.fire({
                icon: "question",
                title: "Quiere Aperturar un Arqueo para esta: " ,
                text: Zona_Name,
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                showLoaderOnConfirm: true,
                preConfirm: async (login) => {
                    var Zona   = $("#id_select_zona option:selected").val();  
                    Zona      = isValue(Zona,-1,true) 

                    if (Zona > 0 ) {                        
                        try {
                            const githubUrl = `ArqueoInit/${Zona}`;
                            const response = await fetch(githubUrl);
                            if (!response.ok) {
                                return Swal.showValidationMessage(`${JSON.stringify(await response.json())}`);
                            }
                        return response.json();
                        } catch (error) {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        }
                            
                    } else {
                        Swal.showValidationMessage(`Seleccione una Zona`);
                    }

                },
                allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: `ARQUEDO #:   ${result.value.ID_ARQUEO} `,
                            text: `FECHA:  ${result.value.FECHA_ARQUEO} `
                        });
                        location.href = 'ShowDetalles/' + result.value.ID_ARQUEO
                    }
                });
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_ingresos').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
    })
    function InitTable() {

        var slCli   = $("#id_select_cliente option:selected").val();  
        var slZna   = $("#id_select_zona option:selected").val();  
        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val(); 



        slCli      = isValue(slCli,-1,true) 
        slZna      = isValue(slZna,-1,true) 
        dtEnd      = isValue(dtEnd,'N/D',true) 
        dtIni      = isValue(dtIni,'N/D',true) 

        const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
        const dt_End = moment(dtEnd, 'DD/MM/YYYY');

        var lbl_titulo_reporte = 'Del ' + dt_Ini.format('ddd, MMM DD, YYYY') + ' Al ' + dt_End.format('ddd, MMM DD, YYYY')
        

        dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
        dt_End_ = dt_End.format('YYYY-MM-DD');
        
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
                "url": "getAbonos",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtIni   : dt_Ini_,
                    dtEnd   : dt_End_,
                    IdCln   : slCli,
                    IdZna   : slZna,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            'columns': [
                {
                    "title": "ID",
                    "data": "cuota_cobrada",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },                 
                {"title": "ZONA / RUTA","data": "Nombre", "render": function(data, type, row, meta) {
                    
                    return '[ ' + row.id_abonoscreditos + ' ] - ' +row.Nombre + ' ' + row.apellido ;
                }},
                {
                    "title": "FECHA ARQUEO",
                    "data": "cuota_cobrada",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "SISTEMA",
                    "data": "pago_capital",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "TOTAL",
                    "data": "pago_intereses",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },      
                {
                    "title": "EFECTIVO",
                    "data": "pago_intereses",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {"title": "  ","data": "Nombre", "render": function(data, type, row, meta) {
                    
                    return `<div class="card-tools text-center">
                                <button type="button" class="btn btn-success primary" title="Date range">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-primary" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>` ;
                }},      
            ],
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }

    

</script>