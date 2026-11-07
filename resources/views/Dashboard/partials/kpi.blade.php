{{-- Tarjeta KPI. Requiere: $id, $label, $tone, $icon, $currency --}}
<article class="kpi kpi--{{ $tone }}">
  <div class="kpi-head">
    <h3 class="kpi-label" id="{{ $id }}-label">{{ $label }}</h3>
    <span class="kpi-icon" aria-hidden="true"><i class="fas {{ $icon }}"></i></span>
  </div>
  <p class="kpi-value">
    @if ($currency)<span class="kpi-currency">C$</span>@endif<span class="kpi-number" id="{{ $id }}">{{ $currency ? '0.00' : '0' }}</span>
  </p>
</article>
