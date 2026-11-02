{{--
  Cuerpo compartido de "Nueva solicitud" (Form) y "Solicitud de renovación" (RequestRenovacion).
  Recibe:
    $v  valores ya normalizados (ver cada vista)
    $p  permisos / variaciones por vista y rol
  IMPORTANTE: los id / name los usa jsViews/js_requests_form; no cambiarlos.

  Distribución (compacta, como el formulario original):
    1. Cliente  (4 + 3 columnas + dirección)
    2. Crédito  (5 columnas + resultados en una franja)
    3. Asignación (3 columnas)
    4. Información adicional en pestañas: Negocio · Referencias · Cónyuge · Garantías · Fiador
--}}
@php
  $estadosCiviles = ['0' => 'Seleccione un estado civil', '1' => 'Soltero(a)', '2' => 'Casado(a)', '3' => 'Divorciado(a)', '4' => 'Viudo(a)', '5' => 'Unión libre'];
  $neg = $v['negocio'];
  $con = $v['conyugue'];
  $fia = $v['fiador'];
@endphp

<link rel="stylesheet" href="{{ asset('css/solicitudes.css') }}?v={{ filemtime(public_path('css/solicitudes.css')) }}">

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid sol">

      <header class="sol-header">
        <div>
          <p class="sol-crumbs"><a href="{{ $p['volver_url'] }}">{{ $p['volver_texto'] }}</a> / {{ $p['titulo'] }}</p>
          <h1 class="sol-title">{{ $p['titulo'] }}</h1>
          @if (!empty($p['subtitulo']))<p class="sol-subtitle">{{ $p['subtitulo'] }}</p>@endif
        </div>
        @if (!$p['puede_editar'])
          <span class="sol-chip sol-chip--lock"><i class="fas fa-lock" aria-hidden="true"></i> Solo lectura</span>
        @endif
        {{-- El JS envía este texto como _Origin; se conserva tal cual (con sus espacios). --}}
        <span class="sol-origin" id="lbl_titulo_origen"> {{ $v['origen'] }} </span>
      </header>

      {{-- Barra fija: secciones + resumen en vivo + acción principal (siempre a la vista) --}}
      <div class="sol-toolbar">
        <nav class="sol-nav" aria-label="Secciones del formulario">
          <a href="#sec-cliente">Cliente</a>
          <a href="#sec-credito">Crédito</a>
          <a href="#sec-asignacion">Asignación</a>
          <a href="#sec-adicional">Info. adicional</a>
        </nav>
        <div class="sol-toolbar-end">
          <p class="sol-live" id="solLive">
            <span class="sol-live-who" id="solLiveWho">{{ trim($v['nombre'] . ' ' . $v['apellido']) ?: 'Sin cliente' }}</span>
            <span class="sol-live-amount" id="solLiveAmount">C$ {{ number_format((float) str_replace(',', '', $v['monto'] ?: 0), 2) }}</span>
          </p>
          @if ($p['mostrar_acciones'])
            <a href="#!" role="button" class="sol-btn sol-btn--primary" id="btn_save_prospecto">
              <i class="fas fa-check" aria-hidden="true"></i> <span class="sol-btn-text">{{ $p['texto_guardar'] }}</span><span class="sol-btn-short">Procesar</span>
            </a>
          @endif
        </div>
      </div>

      <fieldset class="sol-fieldset" {{ $p['puede_editar'] ? '' : 'disabled' }}>
      <div class="sol-main">

        {{-- ============ 1. Cliente ============ --}}
        <section class="sol-section sol-section--key" id="sec-cliente" aria-labelledby="h-cliente">
          <div class="sol-section-head">
            <h2 class="sol-section-title" id="h-cliente"><span class="sol-step" aria-hidden="true">1</span>¿A quién le presta?</h2>
            <p class="sol-section-hint"><span class="sol-req" aria-hidden="true">*</span> Obligatorio</p>
          </div>
          <div class="sol-section-body">
            <div class="sol-cols sol-cols--4">
              <div class="sol-field">
                <label class="sol-label" for="txtNombre">Nombre<span class="sol-req" aria-hidden="true">*</span></label>
                <input type="text" id="txtNombre" class="form-control" autocomplete="off" autocapitalize="words" required value="{{ $v['nombre'] }}">
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtApellido">Apellido<span class="sol-req" aria-hidden="true">*</span></label>
                <input type="text" id="txtApellido" class="form-control" autocomplete="off" autocapitalize="words" required value="{{ $v['apellido'] }}">
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtTelefono">Teléfono</label>
                <input type="tel" class="form-control" id="txtTelefono" inputmode="numeric" maxlength="8" placeholder="8 dígitos"
                       onkeypress="return isNumberKey(event)" value="{{ $v['telefono'] }}">
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtCedula">Cédula</label>
                <input type="text" class="form-control" id="txtCedula" autocapitalize="characters" placeholder="000-000000-0000A"
                       data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{ $v['cedula'] }}">
              </div>
            </div>
            <div class="sol-cols sol-cols--3">
              <div class="sol-field">
                <label class="sol-label" for="selMunicipio">Departamento<span class="sol-req" aria-hidden="true">*</span></label>
                <select class="form-control" id="selMunicipio">
                  @foreach ($Municipios as $m)
                    <option value="{{ $m->id_municipio }}" {{ $v['municipio'] == $m->id_municipio ? 'selected' : '' }}>{{ strtoupper($m->nombre_municipio) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="selZona">Zona</label>
                <select class="form-control" id="selZona">
                  @foreach ($Zonas as $z)
                    <option value="{{ $z->id_zona }}" {{ $v['zona'] == $z->id_zona ? 'selected' : '' }}>{{ strtoupper($z->nombre_zona) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="selectEstadoCivil">Estado civil</label>
                <select class="custom-select" name="selectEstadoCivil" id="selectEstadoCivil">
                  @foreach ($estadosCiviles as $k => $txt)
                    <option value="{{ $k }}" {{ (string) $v['estado_civil'] === (string) $k ? 'selected' : '' }}>{{ $txt }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="sol-field">
              <label class="sol-label" for="txtDireccion">Dirección</label>
              <textarea class="form-control" id="txtDireccion" rows="2" autocomplete="off">{{ $v['direccion'] }}</textarea>
            </div>
          </div>
        </section>

        {{-- ============ 2. Crédito ============ --}}
        <section class="sol-section sol-section--key sol-credit" id="sec-credito" aria-labelledby="h-credito">
          <div class="sol-section-head">
            <h2 class="sol-section-title" id="h-credito"><span class="sol-step" aria-hidden="true">2</span>¿Cuánto le presta?</h2>
            <p class="sol-section-hint">El total y la cuota se calculan solos.</p>
          </div>
          <div class="sol-section-body">
            <div class="sol-cols sol-cols--5">
              <div class="sol-field">
                <label class="sol-label" for="txtMonto">Monto</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
                  <input type="text" id="txtMonto" class="form-control" inputmode="decimal" value="{{ $v['monto'] }}"
                         data-mask data-inputmask="'alias': 'currency'">
                </div>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtRespaldo">Respaldo</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
                  <input type="text" id="txtRespaldo" class="form-control" inputmode="decimal" value="{{ $v['respaldo'] }}"
                         {{ $p['respaldo_editable'] ? '' : 'readonly' }}
                         title="{{ $p['respaldo_editable'] ? 'Se sugiere según el monto; puede ajustarlo.' : 'Se calcula según el monto.' }}"
                         data-mask data-inputmask="'alias': 'currency'">
                </div>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtPlazo">Plazo (meses)</label>
                <input type="text" id="txtPlazo" class="form-control" inputmode="decimal" value="{{ $v['plazo'] }}"
                       data-mask data-inputmask="'alias': 'currency'">
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtInteres">Interés</label>
                <div class="input-group">
                  <input type="text" id="txtInteres" class="form-control" inputmode="decimal" value="{{ $v['interes'] }}"
                         data-mask data-inputmask="'alias': 'currency'">
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="txtCuotas">N.º de cuotas</label>
                <input type="text" id="txtCuotas" class="form-control" inputmode="decimal" value="{{ $v['cuotas'] }}"
                       data-mask data-inputmask="'alias': 'currency'">
              </div>
            </div>

            {{-- Resultados: inputs que lee el JS, presentados como valores --}}
            <div class="sol-results" aria-label="Resultado del cálculo">
              <div class="sol-result sol-result--total">
                <label for="txtTotal">Total</label>
                <input type="text" id="txtTotal" class="form-control" disabled value="{{ $v['total'] }}" data-mask data-inputmask="'alias': 'currency'">
              </div>
              <div class="sol-result">
                <label for="txtVlCuota">Cuota</label>
                <input type="text" id="txtVlCuota" class="form-control" disabled value="{{ $v['cuota'] }}" data-mask data-inputmask="'alias': 'currency'">
              </div>
              <div class="sol-result">
                <label for="txtSaldos">Saldo</label>
                <input type="text" id="txtSaldos" class="form-control" disabled value="{{ $v['saldo'] }}" data-mask data-inputmask="'alias': 'currency'">
              </div>
              <div class="sol-result {{ $p['ver_intereses'] ? '' : 'd-none' }}">
                <label for="txtIntereses">Intereses</label>
                <input type="text" id="txtIntereses" class="form-control" disabled value="{{ $v['intereses'] }}" data-mask data-inputmask="'alias': 'currency'">
              </div>
              <div class="sol-result {{ $p['ver_int_cuota'] ? '' : 'd-none' }}">
                <label for="txtInteresesPorCuota">Intereses por cuota</label>
                <input type="text" id="txtInteresesPorCuota" class="form-control" disabled value="{{ $v['int_cuota'] }}" data-mask data-inputmask="'alias': 'currency'">
              </div>
            </div>
          </div>
        </section>

        {{-- ============ 3. Asignación ============ --}}
        <section class="sol-section" id="sec-asignacion" aria-labelledby="h-asignacion">
          <div class="sol-section-head">
            <h2 class="sol-section-title" id="h-asignacion">Asignación</h2>
          </div>
          <div class="sol-section-body">
            <div class="sol-cols sol-cols--3">
              <div class="sol-field">
                <label class="sol-label" for="dtApertura">Fecha de inicio</label>
                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura"
                         inputmode="numeric" autocomplete="off" value="{{ $v['fecha'] }}" {{ $p['fecha_bloqueada'] ? 'disabled' : '' }}>
                  <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                    <span class="input-group-text" aria-hidden="true"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="slDiaVisita">Día de visita</label>
                <select class="form-control" id="slDiaVisita" {{ $p['dia_bloqueado'] ? 'disabled' : '' }}>
                  @foreach ($DiasSemana as $d)
                    <option value="{{ $d->id_diassemana }}" {{ $v['dia_visita'] == $d->id_diassemana ? 'selected' : '' }}>{{ ucfirst(mb_strtolower($d->dia_semana)) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="sol-field">
                <label class="sol-label" for="slPromotor">Promotor</label>
                <select class="form-control" id="slPromotor" name="promoter">
                  @foreach ($Promo as $pr)
                    <option value="{{ $pr->id }}" {{ (string) $v['promotor'] === (string) $pr->id ? 'selected' : '' }}>{{ strtoupper($pr->nombre) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </section>

        {{-- ============ 4. Información adicional (pestañas) ============ --}}
        <section class="sol-section sol-tabs" id="sec-adicional" aria-label="Información adicional">
          <ul class="nav sol-tablist" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="tab-negocio" data-toggle="tab" href="#pane-negocio" role="tab" aria-controls="pane-negocio" aria-selected="true">Negocio</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-referencias" data-toggle="tab" href="#pane-referencias" role="tab" aria-controls="pane-referencias" aria-selected="false">Referencias</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-conyuge" data-toggle="tab" href="#pane-conyuge" role="tab" aria-controls="pane-conyuge" aria-selected="false">Cónyuge</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-garantias" data-toggle="tab" href="#pane-garantias" role="tab" aria-controls="pane-garantias" aria-selected="false">Garantías</a></li>
            <li class="nav-item"><a class="nav-link" id="tab-fiador" data-toggle="tab" href="#pane-fiador" role="tab" aria-controls="pane-fiador" aria-selected="false">Fiador</a></li>
          </ul>

          <div class="tab-content sol-section-body">

            {{-- Negocio --}}
            <div class="tab-pane fade show active" id="pane-negocio" role="tabpanel" aria-labelledby="tab-negocio">
              <form action="#!" method="post" id="frm_info_cliente_negocio" onsubmit="return false;">
                <div class="sol-cols sol-cols--2">
                  <div class="sol-field">
                    <label class="sol-label" for="neg_nombre">Nombre del negocio</label>
                    <input class="form-control" type="text" name="nombre_negocio" id="neg_nombre" value="{{ $neg->nombre_negocio ?? '' }}">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="neg_antiguedad">Antigüedad</label>
                    <input class="form-control" type="text" name="Antiguedad_negocio" id="neg_antiguedad" placeholder="Ej. 2 años" value="{{ $neg->antiguedad ?? '' }}">
                  </div>
                </div>
                <div class="sol-field">
                  <label class="sol-label" for="neg_direccion">Dirección del negocio</label>
                  <textarea class="form-control" rows="2" name="direccion_negocio" id="neg_direccion">{{ $neg->direccion ?? '' }}</textarea>
                </div>
              </form>
            </div>

            {{-- Referencias --}}
            <div class="tab-pane fade" id="pane-referencias" role="tabpanel" aria-labelledby="tab-referencias">
              @if ($p['puede_agregar_filas'])
              <form action="#!" method="post" id="frm_add_referencias" class="sol-adder" onsubmit="return false;" aria-label="Agregar referencia">
                <div class="sol-cols sol-cols--adder">
                  <div class="sol-field">
                    <label class="sol-label" for="id_nombre_ref">Nombre</label>
                    <input class="form-control" type="text" name="nombre_ref" id="id_nombre_ref" autocapitalize="words">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="id_telefono_ref">Teléfono</label>
                    <input class="form-control" type="tel" name="telefono_ref" id="id_telefono_ref" inputmode="numeric" maxlength="8" placeholder="8 dígitos" onkeypress="return isNumberKey(event)">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="id_direcion_ref">Dirección</label>
                    <input class="form-control" type="text" name="direcion_ref" id="id_direcion_ref">
                  </div>
                  <div class="sol-field sol-field--action">
                    <button type="button" class="sol-btn sol-btn--secondary" id="btn_add_referencia"><i class="fas fa-plus" aria-hidden="true"></i> Agregar</button>
                  </div>
                </div>
              </form>
              @endif

              <div class="sol-table-wrap">
                <table class="table sol-table" id="tbl_refencias" width="100%">
                  <thead>
                    <tr>
                      <th class="sol-col-num">No.</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Dirección</th>
                      <th><span class="sol-sr-only">Acciones</span></th>
                      <th>Nuevo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($v['referencias'] as $key => $ref)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $ref->nombre_ref }}</td>
                        <td>{{ $ref->telefono_ref }}</td>
                        <td>{{ $ref->direccion_ref }}</td>
                        <td></td>
                        <td>S</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Cónyuge --}}
            <div class="tab-pane fade" id="pane-conyuge" role="tabpanel" aria-labelledby="tab-conyuge">
              <p class="sol-pane-hint">Opcional. Solo si el cliente es casado(a) o en unión libre.</p>
              <form action="#!" method="post" id="frm_info_conyugue" onsubmit="return false;">
                <div class="sol-cols sol-cols--4">
                  <div class="sol-field">
                    <label class="sol-label" for="con_nombres">Nombres</label>
                    <input class="form-control" type="text" name="nombres_conyugue" id="con_nombres" autocapitalize="words" value="{{ $con->nombres ?? '' }}">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="con_apellidos">Apellidos</label>
                    <input class="form-control" type="text" name="apellidos_conyugue" id="con_apellidos" autocapitalize="words" value="{{ $con->apellidos ?? '' }}">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="con_cedula">Cédula</label>
                    <input class="form-control" type="text" name="cedula_conyugue" id="con_cedula" autocapitalize="characters" placeholder="000-000000-0000A"
                           data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{ $con->no_cedula ?? '' }}">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="con_telefono">Teléfono</label>
                    <input class="form-control" type="tel" name="telefono_conyugue" id="con_telefono" inputmode="numeric" maxlength="8" placeholder="8 dígitos"
                           onkeypress="return isNumberKey(event)" value="{{ $con->telefono ?? '' }}">
                  </div>
                </div>
                <div class="sol-field">
                  <label class="sol-label" for="con_trabajo">Lugar de trabajo</label>
                  <textarea class="form-control" rows="2" name="direccion_conyugue" id="con_trabajo">{{ $con->direccion_trabajo ?? '' }}</textarea>
                </div>
              </form>
            </div>

            {{-- Garantías --}}
            <div class="tab-pane fade" id="pane-garantias" role="tabpanel" aria-labelledby="tab-garantias">
              @if ($p['puede_agregar_filas'])
              <form action="#!" method="post" id="frm_add_garantias" class="sol-adder" onsubmit="return false;" aria-label="Agregar garantía">
                <div class="sol-cols sol-cols--adder5">
                  <div class="sol-field">
                    <label class="sol-label" for="detalles">Artículo</label>
                    <input class="form-control" type="text" name="detalles" id="detalles" placeholder="Ej. Televisor">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="marca">Marca</label>
                    <input class="form-control" type="text" name="marca" id="marca">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="color">Color</label>
                    <input class="form-control" type="text" name="color" id="color">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="valor">Valor</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
                      <input class="form-control" type="text" name="valor" id="valor" inputmode="decimal" placeholder="0.00">
                    </div>
                  </div>
                  <div class="sol-field sol-field--action">
                    <button type="button" class="sol-btn sol-btn--secondary" id="btn_add_garantias"><i class="fas fa-plus" aria-hidden="true"></i> Agregar</button>
                  </div>
                </div>
              </form>
              @endif

              <div class="sol-table-wrap">
                <table class="table sol-table" id="tbl_garantias" width="100%">
                  <thead>
                    <tr>
                      <th class="sol-col-num">No.</th>
                      <th>Artículo (<span id="total_garantias">{{ count($v['garantias']) }}</span>)</th>
                      <th>Marca</th>
                      <th>Color</th>
                      <th>Valor C$</th>
                      <th><span class="sol-sr-only">Acciones</span></th>
                      <th>Nuevo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($v['garantias'] as $key => $garantia)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $garantia->detalle_articulo }}</td>
                        <td>{{ $garantia->marca }}</td>
                        <td>{{ $garantia->color }}</td>
                        <td>{{ $garantia->valor_recomendado }}</td>
                        <td></td>
                        <td>S</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Fiador --}}
            <div class="tab-pane fade" id="pane-fiador" role="tabpanel" aria-labelledby="tab-fiador">
              <p class="sol-pane-hint">Opcional. Si escribe el nombre del fiador, todos sus datos pasan a ser obligatorios.</p>
              <form action="#!" method="post" id="frm_info_fiador" onsubmit="return false;">
                <div class="sol-cols sol-cols--2">
                  <div class="sol-field">
                    <label class="sol-label" for="id_nombres_fiador">Nombres</label>
                    <input class="form-control" type="text" name="nombres_fiador" id="id_nombres_fiador" autocapitalize="words" value="{{ $fia->nombre_fiador ?? '' }}">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="id_apellidos_fiador">Apellidos</label>
                    <input class="form-control" type="text" name="apellidos_fiador" id="id_apellidos_fiador" autocapitalize="words" value="{{ $fia->apellidos_fiador ?? '' }}">
                  </div>
                </div>
                <div class="sol-cols sol-cols--3">
                  <div class="sol-field">
                    <label class="sol-label" for="id_cedula_fiador">Cédula</label>
                    <input class="form-control" type="text" name="cedula_fiador" id="id_cedula_fiador" autocapitalize="characters" placeholder="000-000000-0000A"
                           data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{ $fia->Cedula_fiador ?? '' }}">
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="id_estado_civil_fiador">Estado civil</label>
                    {{-- name="selectEstadoCivil" es la llave que espera el backend en iFiador --}}
                    <select class="custom-select" name="selectEstadoCivil" id="id_estado_civil_fiador">
                      @foreach ($estadosCiviles as $k => $txt)
                        <option value="{{ $k }}" {{ (string) ($fia->estado_civil_fiador ?? '') === (string) $k ? 'selected' : '' }}>{{ $txt }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="id_telefono_fiador">Teléfono</label>
                    <input class="form-control" type="tel" name="telefono_fiador" id="id_telefono_fiador" inputmode="numeric" maxlength="8" placeholder="8 dígitos"
                           onkeypress="return isNumberKey(event)" value="{{ $fia->Telefono_fiador ?? '' }}">
                  </div>
                </div>
                <div class="sol-cols sol-cols--2">
                  <div class="sol-field">
                    <label class="sol-label" for="id_dir_domicilio_fiador">Dirección de domicilio</label>
                    <textarea class="form-control" rows="2" name="dir_domicilio_fiador" id="id_dir_domicilio_fiador">{{ trim($fia->dir_domicilio_fiador ?? '') }}</textarea>
                  </div>
                  <div class="sol-field">
                    <label class="sol-label" for="id_dir_trabajo_fiador">Lugar de trabajo</label>
                    <textarea class="form-control" rows="2" name="dir_trabajo_fiador" id="id_dir_trabajo_fiador">{{ trim($fia->dir_trabajo_fiador ?? '') }}</textarea>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </section>

        <input type="hidden" name="IdRequest" id="IdRequest" value="{{ $v['id_req'] }}">
        <input type="hidden" name="name_id_Client" id="id_Client" value="{{ $v['id_cliente'] }}">

        {{-- Cierre: al terminar de llenar, la acción también está aquí --}}
        @if ($p['mostrar_acciones'])
          <div class="sol-end-actions">
            @if ($p['mostrar_eliminar'])
              <a href="#!" role="button" class="sol-btn sol-btn--danger" id="btn_remove_prospecto">
                <i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar solicitud
              </a>
            @endif
            <button type="button" class="sol-btn sol-btn--primary" data-proxy-click="#btn_save_prospecto">
              <i class="fas fa-check" aria-hidden="true"></i> {{ $p['texto_guardar'] }}
            </button>
          </div>
        @else
          <p class="sol-readonly-note"><i class="fas fa-lock" aria-hidden="true"></i> Esta solicitud ya fue registrada. Solo Administración u Operaciones pueden modificarla.</p>
        @endif
      </div>
      </fieldset>

    </div>
  </section>
</div>
