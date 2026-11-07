<script type="text/javascript">
    $(document).ready(function () {
        InitTable();
        $('.select2').select2()
        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#btn-buscar").click(function(){
            InitTable();
        })

        $("#btn-export").click(function(){
            var dtEnd   = $("#dtEnd").val();
            var dtIni   = $("#dtIni").val();
            var IdUser  = $("#id_cbo_usuario").val();
            var Tipo    = $("#id_cbo_tipo").val();
            dtEnd      = isValue(dtEnd,'N/D',true)
            dtIni      = isValue(dtIni,'N/D',true)
            const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
            const dt_End = moment(dtEnd, 'DD/MM/YYYY');
            dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
            dt_End_ = dt_End.format('YYYY-MM-DD');
            window.location.href = "ExportMonitoreoActividad?" + $.param({ dt_ini: dt_Ini_, dt_end: dt_End_, IdUser: IdUser, Tipo: Tipo });
        });

        $('#id_txt_buscar').on('keyup', function() {
            var vTable = $('#tbl_actividad').DataTable();
            vTable.search(this.value).draw();
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

        $("#tbl_actividad").DataTable({
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
                "url": "getMonitoreoActividad",
                "type": 'POST',
                'dataSrc': '',
                "data": {
                    dtIni    : dt_Ini_,
                    dtEnd    : dt_End_,
                    IdUser   : $("#id_cbo_usuario").val(),
                    Tipo     : $("#id_cbo_tipo").val(),
                    _token   : "{{ csrf_token() }}"
                }
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0,1,3,4,6,7,8]}
            ],
            'columns': [
                { "title": "#", "data": "Id" },
                { "title": "USUARIO", "data": "Usuario" },
                { "title": "SECCION", "data": "Seccion" },
                { "title": "TIPO", "data": "Tipo" },
                { "title": "METODO", "data": "Metodo" },
                { "title": "RUTA", "data": "Ruta" },
                { "title": "REGISTRO", "data": "Registro" },
                { "title": "IP", "data": "Ip" },
                { "title": "FECHA / HORA", "data": "FechaEntrada" },
            ]
        })

        $("#tbl_actividad_length").hide();
        $("#tbl_actividad_filter").hide();
    }
</script>
