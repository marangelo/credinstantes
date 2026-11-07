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

        $('#id_txt_buscar').on('keyup', function() {
            $('#tbl_notificaciones').DataTable().search(this.value).draw();
        });
    })

    function InitTable() {
        const dt_Ini = moment($("#dtIni").val(), 'DD/MM/YYYY');
        const dt_End = moment($("#dtEnd").val(), 'DD/MM/YYYY');

        $("#lbl_titulo_reporte").text('Notificaciones del ' + dt_Ini.format('ddd, MMM DD, YYYY') + ' Al ' + dt_End.format('ddd, MMM DD, YYYY'))

        $("#tbl_notificaciones").DataTable({
            "responsive": true,
            "lengthChange": false,
            "destroy": true,
            "autoWidth": false,
            "info": false,
            "order": [[0, 'desc']],
            "lengthMenu": [[100,-1], [100,"Todo"]],
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
                "url": "{{ route('getNotificaciones') }}",
                "type": 'POST',
                'dataSrc': '',
                "data": {
                    dtIni    : dt_Ini.format('YYYY-MM-DD'),
                    dtEnd    : dt_End.format('YYYY-MM-DD'),
                    IdUser   : $("#id_cbo_usuario").val(),
                    _token   : "{{ csrf_token() }}"
                }
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0,2,3,4,5,6,8,9]}
            ],
            'columns': [
                { "title": "#", "data": "Id" },
                { "title": "CLIENTE", "data": "Cliente", "render": function(data, type, row) {
                    return `<a href="{{ url('Perfil') }}/` + row.IdCliente + `">` + row.Cliente + `</a>`
                }},
                { "title": "CREDITO", "data": "IdCredito" },
                { "title": "ZONA", "data": "Zona" },
                { "title": "FECHA VISITA", "data": "FechaVisita" },
                { "title": "HORA", "data": "HoraVisita" },
                { "title": "MONTO ATRASADO", "data": "Monto", "render": function(data) {
                    return `<span class="text-danger">C$ ` + numeral(data).format('0,0.00') + `</span>`
                }},
                { "title": "COMENTARIO", "data": "Comentario" },
                { "title": "PROXIMA VISITA", "data": "Proxima" },
                { "title": "USUARIO", "data": "Usuario" },
                { "title": "", "data": "Id", "orderable": false, "render": function(data) {
                    return `<a href="{{ url('notificacionVisita') }}/` + data + `" target="_blank" class="btn btn-warning btn-sm"><i class="fas fa-print"></i></a>`
                }},
            ]
        })

        $("#tbl_notificaciones_filter").hide();
    }
</script>
