<script type="text/javascript">

    var Promociones = [];

    $(document).ready(function () {

        $('#btn_agregar_premio').click(function () {
            AgregarPremio({ TIPO: 'DINERO', MONTO: '', NOMBRE: '', PESO: 1 });
            $('#tbl_premios_editor tbody tr:last .txt_premio_valor').focus();
        });

        $('#tbl_premios_editor')
            .on('change', '.sel_premio_tipo', function () {
                var fila = $(this).closest('tr');
                var objeto = $(this).val() === 'OBJETO';
                fila.find('.txt_premio_valor')
                    .attr({ type: objeto ? 'text' : 'number', placeholder: objeto ? 'Ej. Gorra' : 'Ej. 100', maxlength: 60 })
                    .val('');
            })
            .on('input', '.txt_premio_peso', CalcularChances)
            .on('click', '.btn_quitar_premio', function () {
                $(this).closest('tr').remove();
                CalcularChances();
            });

        $('#txt_vigencia').daterangepicker({
            "autoApply": true,
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
                monthNames: [
                    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ],
                firstDay: 1
            }
        });

        $('#btn_nueva_promocion').click(function () {
            AbrirPromocion(null);
        });

        $('#tbl_ruleta_promociones').on('click', '.btn_editar_promocion', function () {
            var id = $(this).data('id');
            AbrirPromocion(Promociones.find(function (p) { return p['ID'] == id; }));
        });

        $('#tbl_ruleta_promociones').on('click', '.btn_eliminar_promocion', function () {
            var id = $(this).data('id');
            EliminarPromocion(Promociones.find(function (p) { return p['ID'] == id; }));
        });

        $('#btn_save_promocion').click(GuardarPromocion);

        CargarPromociones();
    });

    function CargarPromociones() {
        $.ajax({
            url: "getPromocionesRuleta",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            dataType: "json",
            success: function (dataset) {
                $("#lbl_demo").toggle(dataset['DEMO'] === true);
                Promociones = dataset['PROMOCIONES'];
                TablePromociones(Promociones);
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error);
                Swal.fire('Promociones', 'No se pudieron cargar las promociones.', 'error');
            }
        });
    }

    function TextoPremio(p) {
        return p['TIPO'] === 'OBJETO' ? p['NOMBRE'] : 'C$ ' + numeral(p['MONTO']).format('0,0.[00]');
    }

    // Fila del editor de premios: tipo, monto o nombre del objeto y peso
    function AgregarPremio(p) {
        var objeto = p['TIPO'] === 'OBJETO';
        var fila = $('<tr>' +
            '<td><select class="form-control form-control-sm sel_premio_tipo">' +
                '<option value="DINERO">Dinero</option><option value="OBJETO">Objeto</option>' +
            '</select></td>' +
            '<td><input class="form-control form-control-sm txt_premio_valor" maxlength="60"></td>' +
            '<td><input type="number" class="form-control form-control-sm txt_premio_peso" min="0.0001" max="1000" step="any"></td>' +
            '<td class="text-right align-middle lbl_premio_chance"></td>' +
            '<td><button type="button" class="btn btn-sm btn-outline-danger btn_quitar_premio" title="Quitar"><i class="fas fa-times"></i></button></td>' +
        '</tr>');
        fila.find('.sel_premio_tipo').val(p['TIPO']);
        fila.find('.txt_premio_valor')
            .attr({ type: objeto ? 'text' : 'number', placeholder: objeto ? 'Ej. Gorra' : 'Ej. 100' })
            .val(objeto ? p['NOMBRE'] : p['MONTO']);
        fila.find('.txt_premio_peso').val(p['PESO']);
        $('#tbl_premios_editor tbody').append(fila);
        CalcularChances();
    }

    // % de probabilidad de cada premio según su peso
    function CalcularChances() {
        var filas = $('#tbl_premios_editor tbody tr');
        var pesos = filas.map(function () { return Math.max(0, parseFloat($(this).find('.txt_premio_peso').val()) || 0); }).get();
        var total = pesos.reduce(function (a, b) { return a + b; }, 0);
        filas.each(function (i) {
            $(this).find('.lbl_premio_chance').text(total > 0 ? numeral(pesos[i] / total).format('0.[000]%') : '-');
        });
    }

    function LeerPremios() {
        return $('#tbl_premios_editor tbody tr').map(function () {
            var tipo  = $(this).find('.sel_premio_tipo').val();
            var valor = $.trim($(this).find('.txt_premio_valor').val());
            return {
                tipo: tipo,
                monto: tipo === 'DINERO' ? valor : null,
                nombre: tipo === 'OBJETO' ? valor : null,
                peso: $.trim($(this).find('.txt_premio_peso').val())
            };
        }).get();
    }

    // Barra de uso: verde < 75%, amarilla < 100%, roja al llegar al límite
    function BarraUso(usados, limite) {
        var pct   = limite > 0 ? Math.min(100, Math.round(usados * 100 / limite)) : 0;
        var color = pct >= 100 ? 'bg-danger' : (pct >= 75 ? 'bg-warning' : 'bg-success');
        return '<div class="d-flex justify-content-between"><span>' + usados + ' / ' + limite + '</span>' +
               (pct >= 100 ? '<span class="badge badge-danger">AGOTADO</span>' : '<small>' + pct + '%</small>') + '</div>' +
               '<div class="progress progress-xs mb-0"><div class="progress-bar ' + color + '" style="width: ' + pct + '%"></div></div>';
    }

    function TablePromociones(PROMOCIONES) {
        $('#tbl_ruleta_promociones').DataTable({
            "data": PROMOCIONES,
            "destroy": true,
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": false,
            "autoWidth": false,
            "responsive": true,
            "language": { "emptyTable": "SIN PROMOCIONES" },
            'columns': [
                { "title": "PROMOCIÓN", "data": "NOMBRE", "render": function (v, t, r) {
                    var total = r['PREMIOS'].reduce(function (a, p) { return a + p['PESO']; }, 0);
                    var conPeso = r['PREMIOS'].some(function (p) { return p['PESO'] !== 1; });
                    var premios = r['PREMIOS'].map(function (p) {
                        var chance = conPeso ? ' <small>(' + numeral(p['PESO'] / total).format('0.[000]%') + ')</small>' : '';
                        var icono  = p['TIPO'] === 'OBJETO' ? '<i class="fas fa-gift mr-1"></i>' : '';
                        return '<span class="badge badge-light mr-1">' + icono + $('<div>').text(TextoPremio(p)).html() + chance + '</span>';
                    }).join('');
                    return '<strong>' + $('<div>').text(v).html() + '</strong><br>' + premios;
                } },
                { "title": "VIGENCIA", "data": "FECHA_INICIO", "render": function (v, t, r) {
                    return moment(r['FECHA_INICIO']).format('DD/MM/YYYY') + '<br>' + moment(r['FECHA_FIN']).format('DD/MM/YYYY');
                } },
                { "title": "HOY", "data": "USADOS_HOY", "render": function (v, t, r) {
                    return r['ACTIVA'] ? BarraUso(r['USADOS_HOY'], r['LIMITE_DIARIO']) : 'Límite: ' + r['LIMITE_DIARIO'];
                } },
                { "title": "ESTA SEMANA", "data": "USADOS_SEMANA", "render": function (v, t, r) {
                    return r['ACTIVA'] ? BarraUso(r['USADOS_SEMANA'], r['LIMITE_SEMANAL']) : 'Límite: ' + r['LIMITE_SEMANAL'];
                } },
                { "title": "ESTADO", "data": "ACTIVA", "className": "text-center", "render": function (v) {
                    return v ? '<span class="badge badge-success">ACTIVA</span>' : '<span class="badge badge-secondary">INACTIVA</span>';
                } },
                { "title": "", "data": "ID", "className": "text-center", "render": function (v) {
                    return '<div class="btn-group">' +
                        '<button type="button" class="btn btn-sm btn-primary btn_editar_promocion" data-id="' + v + '" title="Editar"><i class="fas fa-edit"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-danger btn_eliminar_promocion" data-id="' + v + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div>';
                } },
            ],
            "columnDefs": [
                { "width": "30%", "targets": 0 },
                { "width": "18%", "targets": [2, 3] },
            ],
        });
    }

    function AbrirPromocion(p) {
        var picker = $('#txt_vigencia').data('daterangepicker');
        $('#tbl_premios_editor tbody').empty();

        if (p) {
            $('#lbl_modal_promocion').text('Editar promoción');
            $('#txt_id_promocion').val(p['ID']);
            $('#txt_nombre').val(p['NOMBRE']);
            picker.setStartDate(moment(p['FECHA_INICIO']));
            picker.setEndDate(moment(p['FECHA_FIN']));
            p['PREMIOS'].forEach(AgregarPremio);
            $('#txt_limite_diario').val(p['LIMITE_DIARIO']);
            $('#txt_limite_semanal').val(p['LIMITE_SEMANAL']);
            $('#chk_activa').prop('checked', p['ACTIVA']);
        } else {
            $('#lbl_modal_promocion').text('Nueva promoción');
            $('#txt_id_promocion').val(0);
            $('#txt_nombre').val('');
            picker.setStartDate(moment());
            picker.setEndDate(moment().endOf('month'));
            $('#txt_limite_diario').val('');
            $('#txt_limite_semanal').val('');
            $('#chk_activa').prop('checked', true);
            AgregarPremio({ TIPO: 'DINERO', MONTO: '', NOMBRE: '', PESO: 1 });
        }
        $('#modal_promocion').modal('show');
    }

    function EliminarPromocion(p) {
        var aviso = 'Va a dejar de aparecer en la lista' + (p['ACTIVA'] ? ' y la ruleta se queda sin promoción activa' : '') +
                    '. Los giros que ya se hicieron se conservan.';
        Swal.fire({
            title: '¿Eliminar "' + $('<div>').text(p['NOMBRE']).html() + '"?',
            html: aviso,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            focusCancel: true
        }).then(function (r) {
            if (!r.isConfirmed && r.value !== true) return;
            $.ajax({
                url: "RemovePromocionRuleta",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", Id: p['ID'] },
                dataType: "json",
                success: function (res) {
                    res['GUARDADO'] ? toastr.success(res['MENSAJE']) : toastr.info(res['MENSAJE']);
                    CargarPromociones();
                },
                error: function () {
                    Swal.fire('Promociones', 'No se pudo eliminar la promoción.', 'error');
                }
            });
        });
    }

    function GuardarPromocion() {
        var picker = $('#txt_vigencia').data('daterangepicker');

        $.ajax({
            url: "SavePromocionRuleta",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                Id: $('#txt_id_promocion').val(),
                Nombre: $('#txt_nombre').val(),
                FechaInicio: picker.startDate.format('YYYY-MM-DD'),
                FechaFin: picker.endDate.format('YYYY-MM-DD'),
                Premios: JSON.stringify(LeerPremios()),
                LimiteDiario: $('#txt_limite_diario').val(),
                LimiteSemanal: $('#txt_limite_semanal').val(),
                Activa: $('#chk_activa').is(':checked') ? 1 : 0
            },
            dataType: "json",
            success: function (res) {
                if (res['GUARDADO']) {
                    $('#modal_promocion').modal('hide');
                    toastr.success(res['MENSAJE']);
                    CargarPromociones();
                } else {
                    toastr.info(res['MENSAJE']);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errores = Object.values(xhr.responseJSON.errors).map(function (e) { return e[0]; });
                    Swal.fire('Revisá los datos', errores.join('<br>'), 'warning');
                } else {
                    Swal.fire('Promociones', 'No se pudo guardar la promoción.', 'error');
                }
            }
        });
    }

</script>
