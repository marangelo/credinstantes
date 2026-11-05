@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_dashboard')
@endsection
@section('content')
@php
  // Catálogo de indicadores. Los "id" son los que llena js_dashboard; no cambiarlos.
  $kpi = [
    'ingreso'       => ['id' => 'lblIngreso',        'label' => 'Ingreso neto',     'tone' => 'ingreso', 'icon' => 'fa-hand-holding-usd', 'currency' => true],
    'capital'       => ['id' => 'lblCapital',        'label' => 'Capital',          'tone' => 'capital', 'icon' => 'fa-coins',            'currency' => true],
    'recuperacion'  => ['id' => 'lblCapital',        'label' => 'Recuperación',     'tone' => 'capital', 'icon' => 'fa-coins',            'currency' => true],
    'util_bruta'    => ['id' => 'lblInteres',        'label' => 'Utilidad bruta',   'tone' => 'util-bruta', 'icon' => 'fa-chart-line',       'currency' => true],
    'util_neta'     => ['id' => 'lbl_ultil_neta',    'label' => 'Utilidad neta',    'tone' => 'ingreso', 'icon' => 'fa-piggy-bank',       'currency' => true],
    'creditos'      => ['id' => 'lblClientes',       'label' => 'Créditos activos', 'tone' => 'creditos', 'icon' => 'fa-users',            'currency' => false],
    'saldo_cartera' => ['id' => 'id_saldos_cartera', 'label' => 'Saldo de cartera', 'tone' => 'saldo', 'icon' => 'fa-wallet',           'currency' => true],
    'saldo_capital' => ['id' => 'lbl_saldo_capital', 'label' => 'Saldo capital',    'tone' => 'saldo', 'icon' => 'fa-university',       'currency' => true],
    'mora_atrasada' => ['id' => 'lblMoraAtrasada',   'label' => 'Mora atrasada',    'tone' => 'mora-atrasada', 'icon' => 'fa-clock',            'currency' => true],
    'mora_vencida'  => ['id' => 'lblMoraVencida',    'label' => 'Mora vencida',     'tone' => 'mora-vencida',  'icon' => 'fa-exclamation-triangle', 'currency' => true],
    'dispensa'      => ['id' => 'lbl_dispensa',      'label' => 'Dispensa',         'tone' => 'dispensa', 'icon' => 'fa-percent',          'currency' => true],
  ];

  // Qué ve cada rol, agrupado por tema (mismo contenido que antes, ahora con jerarquía).
  $grupos_por_rol = [
    1 => ['Resultados' => ['ingreso', 'capital', 'util_bruta', 'util_neta'],
          'Cartera'    => ['creditos', 'saldo_cartera', 'saldo_capital'],
          'Riesgo'     => ['mora_atrasada', 'mora_vencida', 'dispensa']],
    2 => ['Cobranza'   => ['recuperacion'],
          'Cartera'    => ['creditos', 'saldo_cartera'],
          'Riesgo'     => ['mora_atrasada', 'mora_vencida']],
    3 => ['Resultados' => ['ingreso'],
          'Cartera'    => ['creditos', 'saldo_cartera'],
          'Riesgo'     => ['mora_atrasada', 'mora_vencida']],
    5 => ['Resultados' => ['ingreso'],
          'Cartera'    => ['creditos', 'saldo_cartera'],
          'Riesgo'     => ['mora_atrasada', 'mora_vencida', 'dispensa']],
  ];
  $grupos = $grupos_por_rol[(int) Session::get('rol')] ?? [];
@endphp

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid dash">

      <header class="dash-header">
        <div>
          <h1 class="dash-title" id="IdCardTitle">Indicadores</h1>
          <p class="dash-subtitle">Resumen de la cartera al corte seleccionado</p>
        </div>
      </header>

      @if (in_array(Session::get('rol'), [1, 2, 3, 5]))
      <form class="dash-filters" id="dashFilters" role="search" aria-label="Filtros de indicadores" onsubmit="return false;">
        <div class="form-group">
          <label for="IdFilterByZone">Zona</label>
          <select class="form-control" id="IdFilterByZone" name="IdZona">
            <option value="-1" selected>Todas las zonas</option>
            @foreach ($Zonas as $z)
              <option value="{{ $z->id_zona }}">{{ strtoupper($z->nombre_zona) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="dtIni">Fecha de corte</label>
          <div class="input-group date" id="dt-Ini" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#dt-Ini" id="dtIni"
                   value="{{ date('d/m/Y') }}" inputmode="numeric" autocomplete="off" aria-describedby="dtIniHelp"/>
            <div class="input-group-append" data-target="#dt-Ini" data-toggle="datetimepicker">
              <span class="input-group-text" aria-hidden="true"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <span id="dtIniHelp" class="dash-sr-only">Formato día/mes/año</span>
        </div>

        <div class="dash-actions">
          <button type="submit" class="dash-btn" id="IdbtnFilter" aria-busy="false">
            <i class="fa fa-filter dash-btn-icon" aria-hidden="true"></i>
            <span class="spinner-border spinner-border-sm dash-spinner" aria-hidden="true"></span>
            <span>Aplicar</span>
          </button>
          <div class="dash-export">
            <table id="tbl_metrias_home" class="table" aria-hidden="true"></table>
          </div>
        </div>
      </form>
      @endif

      <div class="dash-kpis" id="dashKpis" aria-busy="false">
        @foreach ($grupos as $titulo => $claves)
          <section class="dash-group" style="--n: {{ count($claves) }}" aria-labelledby="grp-{{ \Illuminate\Support\Str::slug($titulo) }}">
            <h2 class="dash-group-title" id="grp-{{ \Illuminate\Support\Str::slug($titulo) }}">{{ $titulo }}</h2>
            <div class="dash-grid">
              @foreach ($claves as $clave)
                @include('Dashboard.partials.kpi', $kpi[$clave])
              @endforeach
            </div>
          </section>
        @endforeach
      </div>

      <section class="dash-card" aria-labelledby="chartTitle">
        <div class="dash-card-header">
          <h2 class="dash-card-title" id="chartTitle">Ingreso por mes</h2>
          <span class="dash-card-hint">Montos en C$</span>
        </div>
        <div class="dash-card-body">
          <div class="dash-chart">
            <canvas id="sales-chart" role="img" aria-label="Gráfico de barras del ingreso por mes"></canvas>
          </div>
        </div>
      </section>

    </div>
  </section>
</div>
@endsection
