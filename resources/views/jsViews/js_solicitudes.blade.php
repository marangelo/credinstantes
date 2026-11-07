<script type="text/javascript">
    $(document).ready(function () {

        var type_form = $("#lbl_titulo_reporte").text().replace(/ /g,'');

        InitTable(type_form);

        // Búsqueda con una pequeña espera para no redibujar en cada tecla
        var tBuscar = null;
        $('#id_txt_buscar').on('input', function() {
            var q = this.value;
            clearTimeout(tBuscar);
            tBuscar = setTimeout(function () { $('#tbl_ingresos').DataTable().search(q).draw(); }, 150);
        });

        $("#btn_open_modal_renovacion").click(function(e){
            e.preventDefault();
            if(type_form == 'RENOVAR'){
                ModalRenovacion();
            }else{
                window.location.href = "Formulario/0";
            }
        })

        var tBuscarRen = null;
        $("#txt_search_renovaciones").on('input', function() {
            var q = this.value;
            clearTimeout(tBuscarRen);
            tBuscarRen = setTimeout(function () { $('#tbl_renovaciones').DataTable().search(q).draw(); }, 150);
        });

        // En PC el cursor queda listo en el buscador al abrir la hoja de renovación
        $('#modal-renovacion').on('shown.bs.modal', function () {
            if (window.matchMedia && window.matchMedia('(pointer: fine)').matches) {
                $('#txt_search_renovaciones').trigger('focus');
            }
        });
    })

    $('.button_export_excel').click(() => {
        $('#tbl_ingresos').DataTable().buttons(0,0).trigger()
    })

    // ---------- Utilidades de presentación ----------
    function esc(v) {
        return $('<div>').text(v == null ? '' : String(v)).html();
    }
    function telLink(tel) {
        var t = $.trim(tel || '');
        var limpio = t.replace(/[^0-9+]/g, '');
        if (!limpio || limpio === '0' || /^0+$/.test(limpio)) return '<span class="sl-muted">Sin teléfono</span>';
        return '<a href="tel:' + limpio + '" class="sl-tel"><i class="fas fa-phone-alt" aria-hidden="true"></i> ' + esc(t) + '</a>';
    }
    function fechaCorta(f) {
        var m = moment(f, 'YYYY-MM-DD');
        return m.isValid() ? m.format('DD MMM YYYY') : esc(f);
    }

    // ---------- Hoja de renovación: clientes inactivos ----------
    function ModalRenovacion(){
        $('#modal-renovacion').modal('show');
        $('#tbl_renovaciones').addClass('is-loading');

        $.get( "getClientesInactivos", function( data ) {
            initTable_historico('#tbl_renovaciones',data.Clientes_Inactivos);
        }).always(function () {
            $('#tbl_renovaciones').removeClass('is-loading');
        });
    }

    function initTable_historico(id,Clientes_Inactivos){

        $(id).DataTable({
            "data": Clientes_Inactivos,
            "destroy": true,
            "info": false,
            "responsive": false,
            "bPaginate": true,
            "pageLength": 10,
            "searching": true,
            "ordering": false,
            "dom": "rt<'sl-pager'p>",
            "language": {
                "zeroRecords": "No hay clientes que coincidan",
                "emptyTable": "No hay clientes inactivos para renovar",
                "paginate": { "next": "Siguiente", "previous": "Anterior" }
            },
            'columns':  [
                {"title": "Cliente","data": "nombre", "className": "sl-pick-main", "render": function(data, type, row) {
                    var nombre = (row.nombre + ' ' + row.apellidos).toUpperCase();
                    if (type !== 'display') return nombre + ' ' + (row.telefono || '') + ' ' + (row.cedula || '');
                    var sub = [];
                    if (row.telefono) sub.push(esc(row.telefono));
                    if (row.direccion_domicilio) sub.push(esc(row.direccion_domicilio.toUpperCase()));
                    return '<span class="sl-pick-name">' + esc(nombre) + ' <span class="sl-muted">#' + row.id_clientes + '</span></span>' +
                           (sub.length ? '<span class="sl-pick-sub">' + sub.join(' · ') + '</span>' : '');
                }},
                {"title": "","data": "id_clientes", "className": "sl-pick-action", "render": function(data, type, row) {
                    // Antes era un ícono de impresora; ahora dice lo que hace
                    return '<a href="CreditoRenovacion/' + row.id_clientes + '" class="sl-btn sl-btn--primary">' +
                           '<i class="fas fa-redo" aria-hidden="true"></i> Renovar</a>';
                }},
            ],
        });
    }

    // ---------- Lista principal ----------
    function InitTable(type_form) {

        var slZna   = $("#id_select_zona option:selected").val();
        slZna       = isValue(slZna,-1,true)

        $("#tbl_ingresos").DataTable({
            "responsive": false,          // en celular cada fila es una tarjeta (sol-lista.css)
            "lengthChange": false,
            "destroy": true,
            "autoWidth": false,
            "info": true,
            "pageLength": 25,
            "order": [[5, 'desc']],       // las más recientes primero
            "dom": "rt<'sl-pager'ip>",
            "language": {
                "zeroRecords": "No hay solicitudes que coincidan con la búsqueda",
                "emptyTable": "No hay solicitudes pendientes",
                "info": "_START_–_END_ de _TOTAL_",
                "infoEmpty": "0 solicitudes",
                "infoFiltered": "(de _MAX_)",
                "loadingRecords": "Cargando…",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "search": "Buscar"
            },
            "ajax":{
                "url": "{{ route('getSolicitudes')}}",
                "type": 'POST',
                'dataSrc': function (json) {
                    $('#slTotal').text(numeral(json.length).format('0,0'));
                    return json;
                },
                "data": {
                    IdZna   : slZna,
                    tyForm  : type_form,
                    _token  : "{{ csrf_token() }}"
                }
            },
            buttons: [{
                extend: 'excelHtml5',
                title: (type_form == 'RENOVAR') ? 'Solicitudes de renovación' : 'Solicitudes nuevas',
                exportOptions: { columns: ':not(.c-accion)', orthogonal: 'export' }
            }],
            'columns': [
                {"title": "Cliente","data": "Nombre", "className": "c-name", "render": function(data, type, row) {
                    var nombre = row.Nombre + ' ' + row.apellido;
                    if (type !== 'display') return '[ ' + row.id_clientes + ' ] - ' + nombre;
                    return '<a href="Formulario/' + row.id_clientes + '" class="sl-name">' + esc(nombre) + '</a>' +
                           '<span class="sl-id">Solicitud #' + row.id_clientes + '</span>';
                }},
                {"title": "Cédula","data": "Cedula", "className": "c-ced", "defaultContent": ""},
                {"title": "Dirección","data": "Direccion", "className": "c-addr", "defaultContent": ""},
                {"title": "Teléfono","data": "Telefono", "className": "c-tel", "defaultContent": "", "render": function(data, type) {
                    return type === 'display' ? telLink(data) : (data || '');
                }},
                {"title": "Zona","data": "Zona", "className": "c-zone", "defaultContent": ""},
                {"title": "Fecha","data": "Fecha_registro", "className": "c-date", "render": function(data, type) {
                    return type === 'display' ? fechaCorta(data) : data;
                }},
                {"title": "Monto","data": "Monto_promedio", "className": "c-monto text-right", "render": function(data, type) {
                    if (type !== 'display') return data;
                    return '<span class="sl-currency">C$</span>' + numeral(data).format('0,0.00');
                }},
                {"title": "Creado por","data": "Usuario", "className": "c-user", "defaultContent": ""},
                {"title": "Estado","data": "Estado", "className": "c-estado", "render": function(data, type) {
                    var txt = $.trim($('<div>').html(data || '').text());
                    return type === 'display' ? '<span class="sl-badge"><i class="far fa-clock" aria-hidden="true"></i> ' + esc(txt) + '</span>' : txt;
                }},
                {"title": "","data": "id_clientes", "className": "c-accion", "orderable": false, "render": function(data, type, row) {
                    return '<a href="Formulario/' + row.id_clientes + '" class="sl-btn" aria-label="Revisar solicitud de ' + esc(row.Nombre + ' ' + row.apellido) + '">' +
                           '<i class="fas fa-clipboard-check" aria-hidden="true"></i> Revisar</a>';
                }},
            ],
        })
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

</script>
