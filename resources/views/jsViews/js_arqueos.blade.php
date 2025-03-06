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

        var slZna   = $("#id_select_zona option:selected").val();  
        var dtEnd   = $("#dtEnd").val();
        var dtIni   = $("#dtIni").val(); 



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
            order: [[0, 'desc']],
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
                "url": "getDataArqueos",
                "type": 'POST',
                'dataSrc': '',
                "data": {                
                    dtIni   : dt_Ini_,
                    dtEnd   : dt_End_,
                    IdZna   : slZna,
                    _token  : "{{ csrf_token() }}" 
                }
            },
            buttons: [{extend: 'excelHtml5'}],
            "columnDefs": [
                {"className": "dt-center", "targets": [0,1,2 ]},
                {"className": "dt-right", "targets": [3,4,5,6]},
                { "width": "8%", "targets": [] },
                { "width": "12%", "targets": [  ] },
                { "visible":false, "searchable": false,"targets": [] }
            ],
            'columns': [
                {
                    "title": "#",
                    "data": "Id"
                },                 
                {"title": "ZONA / NOMBRE ","data": "Zona", "render": function(data, type, row, meta) {
                    
                    return row.Zona + '/ ' + row.Nombre ;
                }},
                {
                    "title": "FECHA ARQUEO",
                    "data": "Fecha_Cuota",
                }, 
                {
                    "title": "CUOTA COBRADA C$.",
                    "data": "cuota_cobrada",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
                {
                    "title": "DEP. TRANSFERENCIA C$.",
                    "data": "deposito_tranferencia",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },      
                {
                    "title": "GASTO OPERACION C$.",
                    "data": "gasto_operacion",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {
                    "title": "SISTEMA C$.",
                    "data": "Sistema",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },  
                {"title": "  ","data": "Id", "render": function(data, type, row, meta) {       
                    
                    
                    @if (in_array(Session::get('rol'), [1]))

                    var Bottones = `<div class="card-tools text-center">
                                <a href="ShowDetalles/`+ row.Id +`" class="btn btn-success primary">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="ExportArqueo/`+ row.Id +`" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>
                                </a>
                                <a href="#!" onClick="Remover(`+ row.Id +`)" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>` ;
                    @else
                    var Bottones = `<div class="card-tools text-center">
                                <a href="ExportArqueo/`+ row.Id +`" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>
                                </a>
                            </div>` ;
                    @endif

                    return Bottones;


                    
                }},      
            ],
        })

        $("#tbl_ingresos_length").hide();
        $("#tbl_ingresos_filter").hide();
    }
    function Remover(ID) {
        $.ajax({
            url: "RemoveArqueo",
            data: {
                Arqueo  : ID,
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

    

</script>