<script type="text/javascript">

    $(document).ready(function () {

        $('.select2').select2();

        $("#IdFilterByZone").change(function () {
            CargarGiros();
        });

        $('#tbl_ruleta_giros_buscar').on('input', function () {
            $('#tbl_ruleta_giros').DataTable().search(this.value).draw();
        });

        $('input[name="dt_range"]').daterangepicker({
            "autoApply": true,
            ranges: {
                'Hoy': [moment(), moment()],
                'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
                'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
                'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
                'Este Mes': [moment().startOf('month'), moment()],
                'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            "showCustomRangeLabel": false,
            "alwaysShowCalendars": true,
            "startDate": moment().format('D MMM. YYYY'),
            "endDate": moment().format('D MMM. YYYY'),
            opens: 'left',
            locale: {
                format: "D MMM. YYYY",
                separator: " - ",
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                fromLabel: "Desde",
                toLabel: "Hasta",
                customRangeLabel: "Personalizado",
                weekLabel: "S",
                daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
                monthNames: [
                    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ],
                firstDay: 1
            }
        }, function (start, end, label) {
            CargarGiros();
        });

        CargarGiros();
    });

    function CargarGiros() {

        var Zona   = isValue($("#IdFilterByZone option:selected").val(), -1, true);
        var picker = $('input[name="dt_range"]').data('daterangepicker');

        $.ajax({
            url: "getGirosRuleta",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                Zona: Zona,
                dtIni: picker.startDate.format('YYYY-MM-DD'),
                dtEnd: picker.endDate.format('YYYY-MM-DD')
            },
            dataType: "json",
            success: function (dataset) {
                $("#lbl_demo").toggle(dataset['DEMO'] === true);
                TableGiros(dataset['GIROS']);
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error);
                Swal.fire('Ruleta', 'No se pudieron cargar los giros.', 'error');
            }
        });
    }

    function TableGiros(GIROS) {
        $('#tbl_ruleta_giros').DataTable({
            "data": GIROS,
            "destroy": true,
            "paging": true,
            "pageLength": 25,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "order": [[0, "desc"]],
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "zeroRecords": "NO HAY COINCIDENCIAS",
                "paginate": {
                    "first": "Primera",
                    "last": "Última ",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "emptyTable": "SIN GIROS EN EL PERIODO",
                "info": "_START_ a _END_ de _TOTAL_ giros",
                "infoEmpty": "",
                "infoFiltered": "(de _MAX_)",
                "search": "BUSCAR"
            },
            'columns': [
                { "title": "FECHA", "data": "FECHA", "render": function (v, type) {
                    return type === 'display' ? moment(v, 'YYYY-MM-DD HH:mm').format('DD/MM/YYYY hh:mm a') : v;
                } },
                { "title": "CÓDIGO", "data": "CODIGO" },
                { "title": "CLIENTE", "data": "CLIENTE" },
                { "title": "PROMOTOR", "data": "PROMOTOR" },
                { "title": "PREMIO", "data": "MONTO", "className": "text-right", "render": function (v, type, r) {
                    // Objetos: se muestra el nombre; al ordenar van después del dinero
                    if (r['TIPO'] === 'OBJETO') {
                        return type === 'display' ? '<i class="fas fa-gift mr-1"></i>' + $('<div>').text(r['PREMIO']).html()
                             : type === 'sort' ? Number.MAX_SAFE_INTEGER : r['PREMIO'];
                    }
                    return type === 'display' ? 'C$ ' + numeral(v).format('0,0.00') : v;
                } },
            ],
        });
        $("#tbl_ruleta_giros_filter").hide();
    }

</script>
