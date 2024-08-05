<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        //Initialize Select2 Elements
        $('.select2').select2()

        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#btn-buscar-abonos").click(function(){
            InitTable();
        })

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_prox_vencer').DataTable();     
            vTableArticulos.search(this.value).draw();
        });
    })
    $('.button_export_excel').click(() => {
            $('#tbl_prox_vencer').DataTable().buttons(0,0).trigger()
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

        $("#tbl_prox_vencer").DataTable({
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
                "url": "getProxVencer",
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
            'columns': [
                
                {"title": "NOMBRE","data": "Nombre", "render": function(data, type, row, meta) {                    
                    return '[ ' + row.id_abonoscreditos + ' ] - ' +row.Nombre + ' ' + row.apellido ;
                }},
                {
                    "title": "ZONA",
                    "data": "ZONA",
                }, 
                {
                    "title": "FECHA. ULT. PAGO",
                    "data": "fecha_ultimo_abono",
                }, 
                {
                    "title": "SALDO CREDITO",
                    "data": "saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, 
               
            ],
            "columnDefs": [
                {"className": "dt-center", "targets": [1,2] },
                {"className": "dt-right", "targets": 3 }
            ],
        })

        $("#tbl_prox_vencer_length").hide();
        $("#tbl_prox_vencer_filter").hide();
    }

    

</script>