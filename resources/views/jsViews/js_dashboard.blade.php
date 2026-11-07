<script type="text/javascript">
    var salesChart = null;   // una sola instancia; antes se creaba un Chart nuevo en cada filtro

    $(document).ready(function () {

        $("#IdbtnFilter").on('click', function (e) {
            e.preventDefault();
            CalcIndicadores();
        });

        $('#dt-Ini').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        CalcIndicadores();
    });

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

    function setLoading(isLoading) {
        $("#dashKpis").attr('aria-busy', isLoading ? 'true' : 'false');
        $("#IdbtnFilter")
            .prop('disabled', isLoading)
            .attr('aria-busy', isLoading ? 'true' : 'false');
    }

    // Escribe un valor y dispara un fundido corto para indicar que cambió
    function setKpi(selector, text) {
        var $el = $(selector);
        if (!$el.length) return;
        $el.removeClass('is-updated').text(text);
        void $el[0].offsetWidth;   // reinicia la animación
        $el.addClass('is-updated');
    }

    function money(v) { return numeral(isValue(v, 0, true)).format('0,0.00'); }
    function count(v) { return numeral(isValue(v, 0, true)).format('0,0'); }

    function CalcIndicadores() {

        var dtIni = isValue($("#dtIni").val(), 'N/D', true);
        var dt_Ini_ = moment(dtIni, 'DD/MM/YYYY').format('YYYY-MM-DD');
        var Opt = isValue($("#IdFilterByZone option:selected").val(), -1, true);

        setLoading(true);

        $.getJSON("getDashboard/" + Opt, { dt_ini: dt_Ini_ }, function (dataset) {

            var data = [{
                "INGRESO_NETO"     : money(dataset['INGRESO']),
                "CAPITAL"          : money(dataset['CAPITAL']),
                "UTILIDAD_BRUTA"   : money(dataset['INTERESES']),
                "UTILIDAD_NETA"    : money(dataset['UTIL_NETA']),
                "CREDITOS_ACTIVOS" : count(dataset['clientes_activos']),
                "SALDO_CARTERA"    : money(dataset['SALDOS_CARTERA']),
                "MORA_ATRASADA"    : money(dataset['MORA_ATRASADA']),
                "MORA_VENCIDA"     : money(dataset['MORA_VENCIDA']),
                "DISPENSA"         : money(dataset['DISPENSA']),
                "SALDOS_CAPITAL"   : money(dataset['SALDOS_CAPITAL'])
            }];

            setKpi("#lblIngreso",        data[0].INGRESO_NETO);
            setKpi("#lblCapital",        data[0].CAPITAL);
            setKpi("#lblInteres",        data[0].UTILIDAD_BRUTA);
            setKpi("#lbl_ultil_neta",    data[0].UTILIDAD_NETA);
            setKpi("#lblClientes",       data[0].CREDITOS_ACTIVOS);
            setKpi("#id_saldos_cartera", data[0].SALDO_CARTERA);
            setKpi("#lblMoraAtrasada",   data[0].MORA_ATRASADA);
            setKpi("#lblMoraVencida",    data[0].MORA_VENCIDA);
            setKpi("#lbl_dispensa",      data[0].DISPENSA);
            setKpi("#lbl_saldo_capital", data[0].SALDOS_CAPITAL);

            renderChart($.map(dataset.label || [], function (x) { return x; }),
                        $.map(dataset.Data  || [], function (x) { return x; }));

            tbMetricas(data);

        }).fail(function () {
            if (window.toastr) {
                toastr.error('No se pudieron cargar los indicadores. Revise su conexión e intente de nuevo.');
            }
        }).always(function () {
            setLoading(false);
        });
    }

    function renderChart(vLabel, vData) {
        var brand   = '#86b817';
        var muted   = '#adb5bd';
        var grid    = 'rgba(255, 255, 255, 0.08)';
        var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (salesChart) {
            salesChart.data.labels = vLabel;
            salesChart.data.datasets[0].data = vData;
            salesChart.update();
            return;
        }

        salesChart = new Chart($('#sales-chart'), {
            type: 'bar',
            data: {
                labels: vLabel,
                datasets: [{
                    label: 'Ingreso',
                    backgroundColor: brand,
                    hoverBackgroundColor: '#9ad11f',
                    borderWidth: 0,
                    maxBarThickness: 48,
                    data: vData
                }]
            },
            options: {
                maintainAspectRatio: false,
                animation: { duration: reduced ? 0 : 400 },
                legend: { display: false },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    displayColors: false,
                    callbacks: {
                        label: function (item) { return 'C$ ' + numeral(item.yLabel).format('0,0.00'); }
                    }
                },
                hover: { mode: 'index', intersect: false },
                scales: {
                    yAxes: [{
                        gridLines: { color: grid, zeroLineColor: grid, drawBorder: false },
                        ticks: {
                            beginAtZero: true,
                            fontColor: muted,
                            padding: 8,
                            callback: function (value) {
                                return 'C$' + (value >= 1000 ? numeral(value).format('0.[0]a') : value);
                            }
                        }
                    }],
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { fontColor: muted }
                    }]
                }
            }
        });
    }

    // Tabla oculta: solo existe para el botón "Excel" de DataTables
    function tbMetricas(ARRAY_METRICAS) {

        if ($.fn.DataTable.isDataTable('#tbl_metrias_home')) {
            $('#tbl_metrias_home').DataTable().clear().destroy();
            $("#tbl_metrias_home").empty();
        }

        $('#tbl_metrias_home').DataTable({
            "data": ARRAY_METRICAS,
            "paging": false,
            "destroy": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
            "dom": 'B',
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel" aria-hidden="true"></i> Excel',
                titleAttr: 'Exportar indicadores a Excel',
                className: 'btn btn-outline-light',
                title: 'Indicadores'
            }],
            'columns': [
                {"title": "INGRESO NETO",     "data": "INGRESO_NETO"},
                {"title": "CAPITAL",          "data": "CAPITAL"},
                {"title": "UTILIDAD BRUTA",   "data": "UTILIDAD_BRUTA"},
                {"title": "UTILIDAD NETA",    "data": "UTILIDAD_NETA"},
                {"title": "CREDITOS ACTIVOS", "data": "CREDITOS_ACTIVOS"},
                {"title": "SALDO DE CARTERA", "data": "SALDO_CARTERA"},
                {"title": "MORA ATRASADA",    "data": "MORA_ATRASADA"},
                {"title": "MORA VENCIDA",     "data": "MORA_VENCIDA"},
                {"title": "DISPENSA",         "data": "DISPENSA"},
                {"title": "SALDOS CAPITAL",   "data": "SALDOS_CAPITAL"},
            ],
        });
        $("#tbl_metrias_home").hide();
    }
</script>
