/* ==========================================================================
   Credin$tante · capa global de UI (acompaña a public/css/ui.css)
   Se carga en layouts/lyt_listas ANTES de los scripts de cada página.

   1. Tablas en celular: en vez de esconder columnas detrás de un "+" (DataTables
      Responsive), cada fila se muestra como tarjeta con etiquetas.
      - Se apaga Responsive solo en pantallas < 768px.
      - Cada celda recibe data-label con el título de su columna.
      - Para excluir una tabla: agregar la clase "no-cards".
   2. Formularios: inputmode adecuado en campos numéricos / teléfono.
   ========================================================================== */
(function ($) {
  'use strict';
  if (!$ || !$.fn) return;

  var mqMovil = window.matchMedia ? window.matchMedia('(max-width: 767.98px)') : { matches: false };

  // Tablas que ya tienen su propio diseño de tarjetas (no se tocan)
  var EXCLUIR = '.no-cards, .cli-table, .sl-table, .sol-table, .sl-pick, .pf-table';

  function esExcluida(tabla) {
    return $(tabla).is(EXCLUIR);
  }

  // --- 1a. Antes de que Responsive se inicialice (el handler en <body> corre antes que el de document)
  $(document.body || 'body').on('preInit.dt', function (e, settings) {
    if (e.namespace !== 'dt' || !settings || esExcluida(settings.nTable)) return;
    $(settings.nTable).addClass('ui-cards');
    if (mqMovil.matches && settings.oInit) {
      settings.oInit.responsive = false;
      $(settings.nTable).removeClass('responsive dt-responsive');
    }
  });

  // --- 1b. Etiquetar cada celda con el título de su columna (para la vista de tarjeta)
  function etiquetar(settings) {
    var tabla = settings.nTable;
    if (!$(tabla).hasClass('ui-cards')) return;
    var api = new $.fn.dataTable.Api(settings);
    var titulos = [];
    api.columns().every(function (i) {
      var h = this.header();
      titulos[i] = h ? $.trim($(h).text()).replace(/^[-–\s]+$/, '') : '';
    });
    api.rows({ page: 'current' }).every(function () {
      var fila = this.node();
      if (!fila) return;
      $(fila).children('td').each(function () {
        if (this.colSpan > 1) return;                 // filas de grupo
        var idx = api.cell(this).index();
        if (!idx) return;
        var t = titulos[idx.column];
        if (t) this.setAttribute('data-label', t);
        else this.removeAttribute('data-label');
      });
    });
    // Pie de tabla (subtotales): misma etiqueta que su columna, si no usa colspan
    $(tabla).children('tfoot').children('tr').each(function () {
      var celdas = $(this).children('td, th');
      if (celdas.filter(function () { return this.colSpan > 1; }).length) return;
      var visibles = api.columns(':visible').indexes().toArray();
      celdas.each(function (i) {
        var t = titulos[visibles[i]];
        if (t) this.setAttribute('data-label', t);
      });
    });

    // Filas de grupo (colspan) se marcan para mostrarlas como encabezado
    $(tabla).find('tbody > tr').each(function () {
      var tds = this.children;
      if (tds.length === 1 && tds[0].colSpan > 1 && !$(tds[0]).hasClass('dataTables_empty')) {
        $(this).addClass('ui-group-row');
      }
    });
  }

  $(document).on('draw.dt', function (e, settings) {
    if (e.namespace !== 'dt' || !settings) return;
    try { etiquetar(settings); } catch (err) { /* nunca romper la página por esto */ }

    // Estado vacío: varias tablas usan "-" como texto; se reemplaza por un mensaje claro
    $(settings.nTable).find('td.dataTables_empty').each(function () {
      var t = $.trim($(this).text());
      if (t === '-' || t === '' || t === '- ') $(this).text('No hay registros para mostrar');
    });
  });

  // --- 3. Tema claro / oscuro (Tómbola). El <head> ya fijó data-theme antes de pintar
  //        (layouts/partials/theme_head). Aquí: botón, persistencia y aviso a la página.
  var raiz = document.documentElement;

  function temaActual() { return raiz.getAttribute('data-theme') === 'dark' ? 'dark' : 'light'; }

  function aplicarTema(tema, animar) {
    if (animar) {
      raiz.classList.add('theme-switching');
      setTimeout(function () { raiz.classList.remove('theme-switching'); }, 320);
    }
    raiz.setAttribute('data-theme', tema);
    // AdminLTE pinta sus propios componentes con la clase "dark-mode"
    if (document.body) document.body.classList.toggle('dark-mode', tema === 'dark');
    var btn = document.getElementById('themeToggle');
    if (btn) btn.setAttribute('aria-label', tema === 'dark' ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro');
    // Para lo que no es CSS (p. ej. colores de Chart.js en el Dashboard)
    try { document.dispatchEvent(new CustomEvent('cs:themechange', { detail: { theme: tema } })); } catch (e) {}
  }
  window.csTema = { actual: temaActual, aplicar: aplicarTema };

  $(function () {
    aplicarTema(temaActual(), false);
    $(document).on('click', '#themeToggle', function () {
      var nuevo = temaActual() === 'dark' ? 'light' : 'dark';
      try { localStorage.setItem('cs-theme', nuevo); } catch (e) {}
      aplicarTema(nuevo, true);
    });
  });

  // Si el usuario nunca eligió, se sigue el tema del sistema en vivo
  if (window.matchMedia) {
    var mqOscuro = window.matchMedia('(prefers-color-scheme: dark)');
    var alCambiarSistema = function (e) {
      var guardado = null;
      try { guardado = localStorage.getItem('cs-theme'); } catch (err) {}
      if (guardado !== 'light' && guardado !== 'dark') aplicarTema(e.matches ? 'dark' : 'light', true);
    };
    if (mqOscuro.addEventListener) mqOscuro.addEventListener('change', alCambiarSistema);
    else if (mqOscuro.addListener) mqOscuro.addListener(alCambiarSistema);
  }

  // --- 2. Teclado adecuado en celular para campos numéricos
  $(function () {
    $('input[onkeypress*="isNumberKey"]').not('[inputmode]').attr('inputmode', 'decimal');
    $('input[type="tel"]').not('[inputmode]').attr('inputmode', 'numeric');
  });

})(window.jQuery);
