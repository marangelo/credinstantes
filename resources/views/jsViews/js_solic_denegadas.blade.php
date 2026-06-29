<script type="text/javascript">
    $(document).ready(function () {
        InitTable();
        $('.select2').select2()
        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#btn-buscar-solic").click(function(){
            InitTable();
        })

        $("#btn-export-solic").click(function(){
            var dtEnd   = $("#dtEnd").val();
            var dtIni   = $("#dtIni").val();
            var IdUser  = $("#id_cbo_usuario").val();
            dtEnd      = isValue(dtEnd,'N/D',true)
            dtIni      = isValue(dtIni,'N/D',true)
            const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
            const dt_End = moment(dtEnd, 'DD/MM/YYYY');
            dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
            dt_End_ = dt_End.format('YYYY-MM-DD');
            window.location.href = "ExportSolicDenegadas?" + $.param({ dt_ini: dt_Ini_, dt_end: dt_End_, IdUser: IdUser });
        });

        $('#id_txt_buscar').on('keyup', function() {
            var vTable = $('#tbl_solic_denegadas').DataTable();
            vTable.search(this.value).draw();
        });
    })

    function ReactivarSolic(ID) {
        Swal.fire({
            title: 'Reactivar solicitud',
            text: 'Esta solicitud volverá a estado activo. ¿Continuar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, reactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "ReactivarSolicDenegada",
                    type: 'post',
                    data: {
                        IdSolic  : ID,
                        _token  : "{{ csrf_token() }}"
                    },
                    async: true,
                    success: function(response) {
                        if (response.original.success) {
                            Swal.fire('Reactivada', 'La solicitud fue reactivada correctamente', 'success');
                            InitTable();
                        } else {
                            Swal.fire('Error', 'No se pudo reactivar la solicitud', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo reactivar la solicitud', 'error');
                    }
                });
            }
        });
    }

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

        $("#tbl_solic_denegadas").DataTable({
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
                "url": "getSolicDenegadas",
                "type": 'POST',
                'dataSrc': '',
                "data": {
                    dtIni    : dt_Ini_,
                    dtEnd    : dt_End_,
                    IdUser   : $("#id_cbo_usuario").val(),
                    _token   : "{{ csrf_token() }}"
                }
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0,1,2,4,5]},
                {"className": "dt-right", "targets": [3]},
                { "width": "8%", "targets": [] },
                { "width": "12%", "targets": [] },
                { "visible":false, "searchable": false,"targets": [] }
            ],
            'columns': [
                { "title": "#", "data": "Id" },
                { "title": "CLIENTE", "data": "Cliente" },
                { "title": "FECHA SOLICITUD", "data": "FechaSolicitud" },
                { "title": "MONTO C$.", "data": "MontoSolicitado", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { "title": "CREADO POR.", "data": "Usuario" },
                {"title": "ACCIÓN","data": "Id", "render": function(data, type, row) {
                    return `<button class="btn btn-success btn-sm" onClick="ReactivarSolic(`+ row.Id +`)">
                        <i class="fas fa-check-circle"></i> Reactivar
                    </button>`;
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

        $("#tbl_solic_denegadas_length").hide();
        $("#tbl_solic_denegadas_filter").hide();
    }
</script>
