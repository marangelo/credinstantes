@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_perfil')
@endsection
@section('content')
@php
  $rol      = (string) Session::get('rol');
  $cli      = $perfil_cliente;
  $nombre   = trim($cli->nombre . ' ' . $cli->apellidos);
  $iniciales = mb_strtoupper(mb_substr(trim($cli->nombre), 0, 1) . mb_substr(trim($cli->apellidos), 0, 1));
  $tel      = trim((string) $cli->telefono);
  $telLink  = preg_replace('/[^0-9+]/', '', $tel);
  $municipio = $cli->getMunicipio->nombre_municipio ?? '';
  $depto     = $cli->getMunicipio->getDepartamentos->nombre_departamento ?? '';
  $zona      = $cli->getZona->nombre_zona ?? '';

  $tonos    = [1 => 'success', 2 => 'danger', 3 => 'warning'];
  $fmtFecha = function ($f) { return $f ? Date::parse($f)->format('d M Y') : '—'; };

  // Créditos con saldo (activos) primero; los terminados se muestran compactos debajo
  $creditos  = $cli->getCreditos;
  $activos   = $creditos->filter(function ($c) { return in_array($c->estado_credito, [1, 2, 3]) || $c->saldo > 0; });
  $anteriores = $creditos->diff($activos);

  $puedePagar     = $rol !== '4';
  $puedeEditar    = in_array($rol, ['1', '5']);
  $verDocumentos  = in_array($rol, ['1', '5']);
  $puedeEliminar  = $rol === '1';
@endphp

<link rel="stylesheet" href="{{ asset('css/perfil.css') }}?v={{ filemtime(public_path('css/perfil.css')) }}">

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid pf">
    {{-- En PC: ficha del cliente fija a la izquierda, créditos a la derecha. En celular: una columna. --}}
    <div class="pf-layout">
      <aside class="pf-side">

      {{-- ===================== Ficha del cliente ===================== --}}
      <section class="pf-client" aria-labelledby="pfName">
        <p class="pf-crumbs"><a href="javascript:history.back()">Clientes</a> / Perfil</p>
        <div class="pf-client-top">
          <span class="pf-avatar" aria-hidden="true">{{ $iniciales ?: '?' }}</span>
          <div>
            <h1 class="pf-client-name" id="pfName">{{ $nombre }}</h1>
            <p class="pf-client-sub">
              Cliente #{{ $cli->id_clientes }}
              @if ($zona)<span class="pf-sep">·</span>{{ strtoupper($zona) }}@endif
            </p>
          </div>
        </div>

        <dl class="pf-client-details">
          <div class="pf-detail">
            <dt class="pf-sr-only">Dirección</dt>
            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
            <dd class="m-0"><span>{{ strtoupper($cli->direccion_domicilio) ?: 'Sin dirección' }}@if ($municipio || $depto) — {{ strtoupper(trim($municipio . ($depto ? ', ' . $depto : ''))) }}@endif</span></dd>
          </div>
          <div class="pf-detail">
            <dt class="pf-sr-only">Cédula</dt>
            <i class="far fa-id-card" aria-hidden="true"></i>
            <dd class="m-0"><span>{{ strtoupper($cli->cedula) ?: 'Sin cédula' }}</span></dd>
          </div>
        </dl>

        <div class="pf-client-actions">
          @if ($telLink !== '' && $telLink !== '0')
            <a href="tel:{{ $telLink }}" class="pf-btn pf-call" aria-label="Llamar a {{ $nombre }}: {{ $tel }}">
              <i class="fas fa-phone-alt" aria-hidden="true"></i> {{ $tel }}
            </a>
          @else
            <span class="pf-btn is-disabled" aria-disabled="true"><i class="fas fa-phone-slash" aria-hidden="true"></i> Sin teléfono</span>
          @endif
          {{-- Ya no se renueva desde aquí: en lugar de "Nuevo crédito", acceso directo a la notificación de visita del crédito activo --}}
          @php $creditoVisita = $activos->first(); @endphp
          @if ($creditoVisita)
            <button type="button" class="pf-btn" onclick="getNotificacionVisita({{ $creditoVisita->id_creditos }})"
                    aria-label="Notificación de visita del crédito #{{ $creditoVisita->id_creditos }}">
              <i class="fas fa-bell" aria-hidden="true"></i> Notificar visita
            </button>
          @endif
        </div>
      </section>
      </aside>

      <div class="pf-main">
      {{-- ===================== Créditos activos ===================== --}}
      <h2 class="pf-section-title pf-title-active">
        <span>{{ $activos->count() === 1 ? 'Crédito activo' : 'Créditos activos' }}</span>
        <span>{{ $activos->count() }}</span>
      </h2>

      @if ($activos->isEmpty())
        <p class="pf-empty">Este cliente no tiene créditos activos.</p>
      @else
      <div class="pf-credits pf-credits--active">
        @foreach ($activos as $c)
          @php
            $tono      = $tonos[$c->estado_credito] ?? 'neutral';
            $pagado    = max(0, (float) $c->total - (float) $c->saldo);
            $avance    = $c->total > 0 ? min(100, round($pagado / $c->total * 100)) : 0;
            $nAbonos   = (int) $c->abonosCount();
            $ultimo    = $c->abonos->first();
            $pendiente = $ultimo ? (float) $ultimo->saldo_cuota : 0;
            // Cuotas realmente pagadas (tbl_pagosabonos); abonosCount() cuenta también los abonos parciales
            $cuotasPagadas = \App\Models\RefAbonos::where('id_creditos', $c->id_creditos)->where('Pagado', 1)->count();
          @endphp
          <article class="pf-credit pf-credit--{{ $tono }}" aria-labelledby="cr-{{ $c->id_creditos }}">
            <div class="pf-credit-head">
              <h3 class="pf-credit-id" id="cr-{{ $c->id_creditos }}">Crédito #{{ $c->id_creditos }} <small>· {{ number_format($c->taza_interes, 2) }}%</small></h3>
              <span class="pf-badge pf-badge--{{ $tono }}">{{ ucfirst(mb_strtolower($c->Estado->nombre_estado ?? '')) }}</span>
            </div>

            <div class="pf-credit-body">
            <div class="pf-credit-summary">
            <p class="pf-balance">
              <span class="pf-balance-label">Saldo</span>
              <span class="pf-balance-value"><small>C$</small>{{ number_format($c->saldo, 2) }}</span>
            </p>

            <div class="pf-progress">
              <div class="pf-progress-bar" role="progressbar" aria-valuenow="{{ $avance }}" aria-valuemin="0" aria-valuemax="100" aria-label="Avance del pago">
                <div class="pf-progress-fill" style="width: {{ $avance }}%"></div>
              </div>
              <div class="pf-progress-text">
                <span>Pagado C$ {{ number_format($pagado, 2) }} de {{ number_format($c->total, 2) }}</span>
                <span>{{ $avance }}%</span>
              </div>
            </div>

            @if ($pendiente > 0)
              <p class="pf-alert"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> Cuota con saldo pendiente: C$ {{ number_format($pendiente, 2) }}</p>
            @endif
            </div>

            <dl class="pf-facts pf-facts--main">
              <div class="pf-fact"><dt>Cuota</dt><dd>C$ {{ number_format($c->cuota, 2) }}</dd></div>
              <div class="pf-fact"><dt>Cuotas pagadas</dt><dd>{{ $cuotasPagadas }} / {{ number_format($c->numero_cuotas, 0) }}</dd></div>
              <div class="pf-fact"><dt>Último pago</dt><dd>{{ $ultimo ? $fmtFecha($ultimo->fecha_cuota) : '—' }}</dd></div>
              <div class="pf-fact"><dt>Monto</dt><dd>C$ {{ number_format($c->monto_credito, 2) }}</dd></div>
              <div class="pf-fact"><dt>Inicio</dt><dd>{{ $fmtFecha($c->fecha_apertura) }}</dd></div>
              <div class="pf-fact"><dt>Vence</dt><dd>{{ $fmtFecha($c->fecha_ultimo_abono) }}</dd></div>
            </dl>
            </div>

            <div class="pf-credit-actions">
              @if ($puedePagar && $c->saldo > 0)
                <button type="button" class="pf-btn pf-btn--primary pf-btn--lg pf-btn--block" onclick="getIdCredi({{ $c->id_creditos }})">
                  <i class="fas fa-hand-holding-usd" aria-hidden="true"></i> Registrar pago
                </button>
              @endif
              <div class="pf-credit-more">
                <button type="button" class="pf-btn" onclick="getModalHistorico({{ $c->id_creditos }})">
                  <i class="fas fa-history" aria-hidden="true"></i> Historial
                </button>
                <button type="button" class="pf-btn" onclick="getNotificacionVisita({{ $c->id_creditos }})">
                  <i class="fas fa-bell" aria-hidden="true"></i> Visita
                </button>
                @if ($puedeEditar || $verDocumentos || $puedeEliminar)
                  @include('Clientes.partials.perfil_menu', ['c' => $c, 'nAbonos' => $nAbonos])
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
      @endif

      {{-- ===================== Créditos anteriores ===================== --}}
      @if ($anteriores->isNotEmpty())
        <h2 class="pf-section-title pf-title-old">
          <span>Créditos anteriores</span>
          <span>{{ $anteriores->count() }}</span>
        </h2>
        <div class="pf-history-list">
          @foreach ($anteriores as $c)
            @php $nAbonos = (int) $c->abonosCount(); @endphp
            <details class="pf-old">
              <summary>
                <span class="pf-old-main">
                  <span class="pf-old-title">Crédito #{{ $c->id_creditos }}</span>
                  <span class="pf-old-sub">{{ $fmtFecha($c->fecha_apertura) }} → {{ $c->fecha_culmina ? $fmtFecha($c->fecha_culmina) : $fmtFecha($c->fecha_ultimo_abono) }} · {{ ucfirst(mb_strtolower($c->Estado->nombre_estado ?? '')) }}</span>
                </span>
                <span class="pf-old-amount">C$ {{ number_format($c->monto_credito, 2) }}</span>
                <i class="fas fa-chevron-right pf-old-chevron" aria-hidden="true"></i>
              </summary>
              <div class="pf-old-body">
                <dl class="pf-facts">
                  <div class="pf-fact"><dt>Total</dt><dd>C$ {{ number_format($c->total, 2) }}</dd></div>
                  <div class="pf-fact"><dt>Saldo</dt><dd>C$ {{ number_format($c->saldo, 2) }}</dd></div>
                  <div class="pf-fact"><dt>Abonos</dt><dd>{{ $nAbonos }}</dd></div>
                  <div class="pf-fact"><dt>Interés</dt><dd>{{ number_format($c->taza_interes, 2) }}%</dd></div>
                  <div class="pf-fact"><dt>Plazo</dt><dd>{{ number_format($c->plazo, 1) }} meses</dd></div>
                  <div class="pf-fact"><dt>Culminó</dt><dd>{{ $fmtFecha($c->fecha_culmina) }}</dd></div>
                </dl>
                <div class="pf-credit-actions">
                  <div class="pf-credit-more">
                    <button type="button" class="pf-btn" onclick="getModalHistorico({{ $c->id_creditos }})">
                      <i class="fas fa-history" aria-hidden="true"></i> Historial
                    </button>
                    @if ($puedeEditar || $verDocumentos || $puedeEliminar)
                      @include('Clientes.partials.perfil_menu', ['c' => $c, 'nAbonos' => $nAbonos])
                    @endif
                  </div>
                </div>
              </div>
            </details>
          @endforeach
        </div>
      @endif

      </div>{{-- /.pf-main --}}
    </div>{{-- /.pf-layout --}}
    </div>
  </section>
</div>

{{-- ===================== Hoja: Registrar pago ===================== --}}
<div class="modal fade pf-modal" id="modal-lg" tabindex="-1" aria-labelledby="pfPayTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h2 class="modal-title" id="pfPayTitle">Registrar pago</h2>
          <p class="pf-modal-sub">{{ $nombre }} · Crédito #<span id="lbl_credito"> 0 </span></p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

        {{-- 1. Tipo de pago (define el monto) --}}
        <div class="pf-field">
          <span class="pf-label" id="pfTipoLbl">Tipo de pago</span>
          <div class="pf-segmented" role="radiogroup" aria-labelledby="pfTipoLbl" id="pfTipoSeg">
            <button type="button" role="radio" aria-checked="true"  data-tipo="0">Simple</button>
            <button type="button" role="radio" aria-checked="false" data-tipo="2">Multiples</button>
            <button type="button" role="radio" aria-checked="false" data-tipo="1">Cancelación</button>
          </div>
          {{-- El JS original lee este select; el selector segmentado lo controla --}}
          <select class="pf-sr-only" id="slTipoAbono" tabindex="-1" aria-hidden="true">
            <option value="0">SIMPLE</option>
            <option value="2">MULTIPLES</option>
            <option value="1">CANCELACION</option>
          </select>
        </div>

        {{-- 2. Cuota que se paga --}}
        <div class="pf-field">
          <label for="lista_pagos">Cuota N.º</label>
          <select class="form-control" id="lista_pagos"></select>
        </div>

        <p class="pf-note" id="pfPendNote" hidden>
          <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
          <span>La cuota anterior tiene saldo pendiente de C$ <strong id="id_mdl_saldo_pendiente">0.00</strong>. Con "Varias cuotas" primero debe completarse.</span>
        </p>

        {{-- 3. Monto --}}
        <div class="pf-field pf-amount">
          <label for="txt_Total_abono" id="id_lbl_cuota">Monto a pagar</label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
            <input type="text" id="txt_Total_abono" class="form-control" inputmode="decimal" autocomplete="off" placeholder="0.00" onkeypress="return isNumberKey(event)">
          </div>
        </div>

        {{-- 4. Fecha --}}
        <div class="pf-field">
          <label for="IddtApertura">Fecha del pago</label>
          {{-- Solo lectura: el pago se aplica con la fecha del día, no se puede cambiar --}}
          <div class="input-group">
            <input type="text" class="form-control" id="IddtApertura" value="{{ date('d/m/Y') }}" readonly tabindex="-1" aria-readonly="true"/>
            <div class="input-group-append">
              <span class="input-group-text" aria-hidden="true"><i class="fas fa-lock"></i></span>
            </div>
          </div>
        </div>

        {{-- Solo para cancelación --}}
        <div id="lbl_cancelacion">
          @if (in_array($rol, ['1', '5']))
            <div class="pf-field">
              <label for="txt_descuento">Descuento</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
                <input type="text" id="txt_descuento" class="form-control" inputmode="decimal" placeholder="0.00" onkeypress="return isNumberKey(event)">
              </div>
            </div>
          @endif
          <div class="pf-split" aria-label="Desglose de la cancelación">
            <div><span>Capital</span><strong class="is-capital" id="lbl_cancel_capital">C$ 0.00</strong></div>
            <div><span>Intereses</span><strong class="is-interes" id="lbl_cancel_interes">C$ 0.00</strong></div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="pf-btn" data-dismiss="modal">Cancelar</button>
        <button type="button" class="pf-btn pf-btn--primary" id="btn_save_abono" aria-busy="false">
          <span class="pf-spinner" aria-hidden="true"></span>
          <i class="fas fa-check pf-btn-icon" aria-hidden="true"></i>
          <span id="pfPayLabel">Registrar pago</span>
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===================== Hoja: Nuevo crédito en paralelo ===================== --}}
<div class="modal fade pf-modal" id="modal-xl" tabindex="-1" aria-labelledby="pfNewTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h2 class="modal-title" id="pfNewTitle">Nuevo crédito en paralelo</h2>
          <p class="pf-modal-sub">{{ $nombre }}</p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <h3 class="pf-group-title">Crédito</h3>
        <div class="pf-cols pf-cols--2">
          <div class="pf-field">
            <label for="txtMonto">Monto</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
              <input type="text" id="txtMonto" class="form-control" inputmode="decimal" placeholder="0.00" onkeypress="return isNumberKey(event)">
            </div>
          </div>
          <div class="pf-field">
            <label for="txtPlazo">Plazo (meses)</label>
            <input type="text" id="txtPlazo" class="form-control" inputmode="decimal" onkeypress="return isNumberKey(event)">
          </div>
          <div class="pf-field">
            <label for="txtInteres">Interés</label>
            <div class="input-group">
              <input type="text" id="txtInteres" class="form-control" inputmode="decimal" placeholder="0.00" onkeypress="return isNumberKey(event)">
              <div class="input-group-append"><span class="input-group-text">%</span></div>
            </div>
          </div>
          <div class="pf-field">
            <label for="txtCuotas">N.º de cuotas</label>
            <input type="text" id="txtCuotas" class="form-control" inputmode="decimal" onkeypress="return isNumberKey(event)">
          </div>
        </div>
        <div class="pf-results">
          <div class="pf-result pf-result--total"><label for="txtTotal">Total</label><input type="text" id="txtTotal" class="form-control" placeholder="0.00" disabled></div>
          <div class="pf-result"><label for="txtVlCuota">Cuota</label><input type="text" id="txtVlCuota" class="form-control" placeholder="0.00" disabled></div>
          <div class="pf-result"><label for="txtSaldos">Saldo</label><input type="text" id="txtSaldos" class="form-control" placeholder="0.00" disabled></div>
          <div class="pf-result"><label for="txtIntereses">Intereses</label><input type="text" id="txtIntereses" class="form-control" placeholder="0.00" disabled></div>
          <div class="pf-result"><label for="txtInteresesPorCuota">Int. por cuota</label><input type="text" id="txtInteresesPorCuota" class="form-control" placeholder="0.00" disabled></div>
        </div>

        <h3 class="pf-group-title">Asignación</h3>
        <div class="pf-cols pf-cols--3">
          <div class="pf-field">
            <label for="dtApertura">Fecha de apertura</label>
            <div class="input-group date" id="reservationdate" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" inputmode="numeric" value="{{ date('d/m/Y') }}"/>
              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                <span class="input-group-text" aria-hidden="true"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="pf-field">
            <label for="slDiaVisita">Día de visita</label>
            <select class="form-control" id="slDiaVisita">
              @foreach ($DiasSemana as $d)
                <option value="{{ $d->id_diassemana }}">{{ ucfirst(mb_strtolower($d->dia_semana)) }}</option>
              @endforeach
            </select>
          </div>
          <div class="pf-field">
            <label for="slPromotor">Promotor</label>
            <select class="form-control" id="slPromotor">
              @foreach ($Promo as $p)
                <option value="{{ $p->id }}">{{ strtoupper($p->nombre) }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="pf-btn" data-dismiss="modal">Cancelar</button>
        <button type="button" class="pf-btn pf-btn--primary" id="btn_add_credito"><i class="fas fa-check" aria-hidden="true"></i> Guardar crédito</button>
      </div>
    </div>
  </div>
</div>

{{-- ===================== Hoja: Notificación de visita ===================== --}}
<div class="modal fade pf-modal" id="modal-notificacion-visita" tabindex="-1" aria-labelledby="pfNtfTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h2 class="modal-title" id="pfNtfTitle">Notificación de visita</h2>
          <p class="pf-modal-sub">{{ $nombre }} · Crédito #<span id="lbl_ntf_id_credito"> 0 </span></p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="pf-cols pf-cols--2">
          <div class="pf-field">
            <label for="txt_ntf_fecha">Fecha de la visita</label>
            <input type="date" class="form-control" id="txt_ntf_fecha">
          </div>
          <div class="pf-field">
            <label for="txt_ntf_hora">Hora de la visita</label>
            <input type="time" class="form-control" id="txt_ntf_hora">
          </div>
          <div class="pf-field">
            <label for="txt_ntf_monto">Monto atrasado</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
              <input type="text" class="form-control" id="txt_ntf_monto" inputmode="decimal" placeholder="0.00" onkeypress="return isNumberKey(event)">
            </div>
          </div>
          <div class="pf-field">
            <label for="txt_ntf_proxima">Próxima visita</label>
            <input type="date" class="form-control" id="txt_ntf_proxima">
          </div>
        </div>
        <div class="pf-field">
          <label for="txt_ntf_comentario">Comentario <span class="pf-help">(<span id="lbl_ntf_contador">0</span>/300)</span></label>
          <textarea class="form-control" id="txt_ntf_comentario" rows="3" maxlength="300" placeholder="Comentario corto…"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="pf-btn" data-dismiss="modal">Cerrar</button>
        <button type="button" class="pf-btn pf-btn--primary" id="btn_print_notificacion"><i class="fas fa-print"></i> Imprimir</button>
      </div>
    </div>
  </div>
</div>

{{-- ===================== Hoja: Historial ===================== --}}
<div class="modal fade pf-modal" id="modal-historico" tabindex="-1" aria-labelledby="pfHisTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h2 class="modal-title" id="pfHisTitle">Historial</h2>
          <p class="pf-modal-sub">{{ $nombre }} · Crédito #<span id="lbl_mdl_id_credito"> 0 </span></p>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <ul class="nav pf-tabs" role="tablist">
          <li class="nav-item"><a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pagos realizados</a></li>
          <li class="nav-item"><a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Plan de pagos</a></li>
          <li class="nav-item"><a class="nav-link" id="custom-content-below-notif-tab" data-toggle="pill" href="#custom-content-below-notif" role="tab" aria-controls="custom-content-below-notif" aria-selected="false">Notificaciones</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
            <table id="tbl_lista_abonos" class="table pf-table" style="width:100%"></table>
          </div>
          <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
            <table id="tbl_pagos_realizados" class="table pf-table" style="width:100%"></table>
          </div>
          <div class="tab-pane fade" id="custom-content-below-notif" role="tabpanel" aria-labelledby="custom-content-below-notif-tab">
            <table id="tbl_notificaciones_cliente" class="table pf-table" style="width:100%"></table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="pf-btn" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection
