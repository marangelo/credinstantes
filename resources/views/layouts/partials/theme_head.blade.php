{{-- Tema "Tómbola" (claro / oscuro). Incluir en el <head> ANTES de cualquier CSS propio.
     1. Fija <html data-theme> antes de pintar (sin parpadeo): preferencia guardada
        en localStorage "cs-theme"; si no hay, la del sistema operativo.
     2. Carga Nunito (títulos) + Heebo (texto) y public/css/theme.css (tokens --t-*). --}}
<script>
  (function () {
    var t = null;
    try { t = localStorage.getItem('cs-theme'); } catch (e) {}
    if (t !== 'light' && t !== 'dark') {
      t = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
    }
    document.documentElement.setAttribute('data-theme', t);
  })();
</script>
<meta name="theme-color" content="#2E8B47" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0F1A14" media="(prefers-color-scheme: dark)">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Nunito:wght@700;800;900&display=swap">
{{-- theme.css se carga aparte, DESPUÉS de adminlte.min.css, para poder sobrescribirlo --}}
