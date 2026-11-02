@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_clientes')
@endsection

@section('content')
@php
  $rol         = (int) Session::get('rol');
  $esActivos   = request()->is('Activos/*');
  $esInactivos = request()->is('Inactivos/*');
  $zonaActual  = (string) request()->route('id');

  // Mismas filas que antes: en Activos se omiten los créditos inactivos (estado 4)
  $filas = $Clientes->filter(function ($c) use ($esInactivos) {
      $e = $c->getCreditos->first();
      return $e && ($e->estado_credito != 4 || $esInactivos);
  });

  $tonos = [1 => 'success', 2 => 'danger', 3 => 'warning', 4 => 'neutral'];

  $verAcciones = $esActivos ? in_array($rol, [1, 3, 5]) : $rol === 1;
  $verFiltros  = in_array($rol, [1, 2, 3, 5]);   // igual que antes: en Activos e Inactivos
  $verZonas    = in_array($rol, [1, 3, 5]);
  $verNuevo    = $esActivos && in_array($rol, [1, 3, 5]);
@endphp

<link rel="stylesheet" href="{{ asset('css/clientes.css') }}?v={{ filemtime(public_path('css/clientes.css')) }}">

<div class="content-wrapper">
  <span id="id_rol_user" class="d-none"> {{ Session::get('rol') }}</span>

  <section class="content">
    <div class="container-fluid cli">

      <header class="cli-header">
        <div>
          <h1 class="cli-title">{{ $Titulo }}</h1>
          <p class="cli-subtitle"><span id="cliTotal">{{ number_format($filas->count()) }}</span> clientes</p>
        </div>
        @if ($verNuevo)
          <button type="button" class="cli-btn cli-btn--primary" data-toggle="modal" data-target="#modal-xl">
            <i class="fas fa-plus" aria-hidden="true"></i> Nuevo crédito
          </button>
        @endif
      </header>

      {{-- Buscar + zona + exportar --}}
      <div class="cli-toolbar">
        <div class="cli-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <input type="search" id="cliSearch" class="form-control" placeholder="Buscar por nombre, teléfono o dirección"
                 aria-label="Buscar clientes" autocomplete="off" enterkeyhint="search">
        </div>
        @if ($verZonas)
          <div class="cli-zone">
            <label for="id_select_zona" class="cli-sr-only">Zona</label>
            <select class="form-control" id="id_select_zona" name="IdZona">
              <option value="0" {{ $zonaActual === '0' ? 'selected' : '' }}>Todas las zonas</option>
              @foreach ($Zonas as $z)
                <option value="{{ $z->id_zona }}" {{ $zonaActual === (string) $z->id_zona ? 'selected' : '' }}>{{ strtoupper($z->nombre_zona) }}</option>
              @endforeach
            </select>
          </div>
        @endif
        <div class="cli-export" id="cliExport"></div>
      </div>

      {{-- Filtro por estado (los conteos los llena el JS) --}}
      @if ($verFiltros)
        <div class="cli-filters" role="group" aria-label="Filtrar por estado">
          <button type="button" class="cli-filter" data-estado="" aria-pressed="true">Todos</button>
          <button type="button" class="cli-filter cli-filter--success" data-estado="AL DIA" aria-pressed="false">
            <span class="cli-dot" aria-hidden="true"></span> Al día <span class="cli-count" id="id_al_dia_count">0</span>
          </button>
          <button type="button" class="cli-filter cli-filter--danger" data-estado="EN MORA" aria-pressed="false">
            <span class="cli-dot" aria-hidden="true"></span> En mora <span class="cli-count" id="id_al_mora_count">0</span>
          </button>
          <button type="button" class="cli-filter cli-filter--warning" data-estado="VENCIDO" aria-pressed="false">
            <span class="cli-dot" aria-hidden="true"></span> Vencido <span class="cli-count" id="id_al_vencido_count">0</span>
          </button>
        </div>
      @endif

      <div class="cli-list">
        <table id="tbl_clientes" class="table cli-table" width="100%">
          <thead>
            <tr>
              <th>Estado</th>
              <th>Cliente</th>
              <th>Teléfono</th>
              <th>Departamento</th>
              <th>Zona</th>
              <th>Dirección</th>
              <th class="text-right">Saldo C$</th>
              @if ($verAcciones)
                <th class="text-right"><span class="cli-sr-only">Acciones</span></th>
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach ($filas as $c)
              @php
                $cred    = $c->getCreditos->first();
                $estado  = $cred->estado_credito ? strtoupper($cred->Estado->nombre_estado) : ' - ';
                $tono    = $tonos[$cred->estado_credito] ?? 'neutral';
                $nombre  = trim(strtoupper($c->nombre) . ' ' . strtoupper($c->apellidos));
                $saldo   = $c->getCreditos->sum('saldo');
                $tel     = trim((string) $c->telefono);
                $depto   = $c->getMunicipio->nombre_municipio ?? '';
                $zona    = $c->getZona->nombre_zona ?? '';
              @endphp
              <tr>
                <td>{{ $estado }}</td>
                <td class="c-name">
                  <a href="../Perfil/{{ $c->id_clientes }}" class="cli-name">{{ $nombre }}</a>
                  <span class="cli-meta-line">
                    <span class="cli-id">#{{ $c->id_clientes }}</span>
                    <span class="cli-badge cli-badge--{{ $tono }}">{{ ucfirst(mb_strtolower($cred->Estado->nombre_estado ?? '-')) }}</span>
                  </span>
                </td>
                <td class="c-phone">
                  @if ($tel !== '' && $tel !== '0')
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $tel) }}" class="cli-tel" aria-label="Llamar a {{ $nombre }}: {{ $tel }}">
                      <i class="fas fa-phone-alt" aria-hidden="true"></i> {{ $tel }}
                    </a>
                  @else
                    <span class="cli-empty">Sin teléfono</span>
                  @endif
                </td>
                <td class="c-dept">{{ strtoupper($depto) }}</td>
                <td class="c-zone">{{ strtoupper($zona) }}</td>
                <td class="c-addr">{{ strtoupper($c->direccion_domicilio) }}</td>
                <td class="c-saldo text-right" data-order="{{ $saldo }}"><span class="cli-currency">C$</span>{{ number_format($saldo, 2) }}</td>
                @if ($verAcciones)
                  <td class="c-actions">
                    <div class="cli-actions">
                      @if ($esActivos)
                        <button type="button" class="cli-btn cli-btn--ghost" onclick="eCliente({{ $c }})" aria-label="Editar a {{ $nombre }}">
                          <i class="fas fa-pencil-alt" aria-hidden="true"></i><span>Editar</span>
                        </button>
                        @if ($rol === 1)
                          <div class="dropdown">
                            <button type="button" class="cli-btn cli-btn--ghost cli-btn--icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Más acciones para {{ $nombre }}">
                              <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right cli-menu">
                              <button type="button" class="dropdown-item" onclick="ArchivarCliente({{ $c->id_clientes }}, 1)">
                                <i class="fas fa-archive" aria-hidden="true"></i> Archivar
                              </button>
                              <div class="dropdown-divider"></div>
                              <button type="button" class="dropdown-item cli-menu-danger" onclick="rmItem({{ $c->id_clientes }})">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar cliente
                              </button>
                            </div>
                          </div>
                        @endif
                      @else
                        <button type="button" class="cli-btn cli-btn--ghost" onclick="ArchivarCliente({{ $c->id_clientes }}, 1)" aria-label="Archivar a {{ $nombre }}">
                          <i class="fas fa-archive" aria-hidden="true"></i><span>Archivar</span>
                        </button>
                      @endif
                    </div>
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>

      {{-- ===================== Modal: Nuevo crédito ===================== --}}
      <div class="modal fade cli-modal" id="modal-xl" tabindex="-1" aria-labelledby="mdlNuevoTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title" id="mdlNuevoTitle">Nuevo crédito</h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <h3 class="cli-form-group-title">Cliente</h3>
              <div class="cli-cols cli-cols--2">
                <div class="cli-field">
                  <label for="txtNombre">Nombre</label>
                  <input type="text" id="txtNombre" class="form-control" autocapitalize="words">
                </div>
                <div class="cli-field">
                  <label for="txtApellido">Apellido</label>
                  <input type="text" id="txtApellido" class="form-control" autocapitalize="words">
                </div>
              </div>
              <div class="cli-cols cli-cols--4">
                <div class="cli-field">
                  <label for="txtTelefono">Teléfono</label>
                  <input type="tel" class="form-control" id="txtTelefono" inputmode="numeric" placeholder="8 dígitos" onkeypress="return isNumberKey(event)">
                </div>
                <div class="cli-field">
                  <label for="txtCedula">Cédula</label>
                  <input type="text" class="form-control" id="txtCedula" autocapitalize="characters" placeholder="000-000000-0000A" data-inputmask="'mask': ['999-999999-9999A']" data-mask>
                </div>
                <div class="cli-field">
                  <label for="selMunicipio">Departamento</label>
                  <select class="form-control" id="selMunicipio">
                    @foreach ($Municipios as $m)
                      <option value="{{ $m->id_municipio }}">{{ strtoupper($m->nombre_municipio) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="cli-field">
                  <label for="selZona">Zona</label>
                  <select class="form-control" id="selZona">
                    @foreach ($Zonas as $z)
                      <option value="{{ $z->id_zona }}">{{ strtoupper($z->nombre_zona) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="cli-field">
                <label for="txtDireccion">Dirección</label>
                <textarea class="form-control" id="txtDireccion" rows="2"></textarea>
              </div>

              <h3 class="cli-form-group-title">Crédito</h3>
              <div class="cli-cols cli-cols--4">
                <div class="cli-field">
                  <label for="txtMonto">Monto</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">C$</span></div>
                    <input type="text" id="txtMonto" class="form-control" inputmode="decimal" placeholder="0.00" onkeypress="return isNumberKey(event)">
                  </div>
                </div>
                <div class="cli-field">
                  <label for="txtPlazo">Plazo (meses)</label>
                  <input type="text" id="txtPlazo" class="form-control" inputmode="decimal" onkeypress="return isNumberKey(event)">
                </div>
                <div class="cli-field">
                  <label for="txtInteres">Interés</label>
                  <div class="input-group">
                    <input type="text" id="txtInteres" class="form-control" inputmode="decimal" placeholder="0.00" onkeypress="return isNumberKey(event)">
                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                  </div>
                </div>
                <div class="cli-field">
                  <label for="txtCuotas">N.º de cuotas</label>
                  <input type="text" id="txtCuotas" class="form-control" inputmode="decimal" onkeypress="return isNumberKey(event)">
                </div>
              </div>
              <div class="cli-results">
                <div class="cli-result cli-result--total"><label for="txtTotal">Total</label><input type="text" id="txtTotal" class="form-control" placeholder="0.00" disabled></div>
                <div class="cli-result"><label for="txtVlCuota">Cuota</label><input type="text" id="txtVlCuota" class="form-control" placeholder="0.00" disabled></div>
                <div class="cli-result"><label for="txtSaldos">Saldo</label><input type="text" id="txtSaldos" class="form-control" placeholder="0.00" disabled></div>
                <div class="cli-result"><label for="txtIntereses">Intereses</label><input type="text" id="txtIntereses" class="form-control" placeholder="0.00" disabled></div>
                <div class="cli-result"><label for="txtInteresesPorCuota">Int. por cuota</label><input type="text" id="txtInteresesPorCuota" class="form-control" placeholder="0.00" disabled></div>
              </div>

              <h3 class="cli-form-group-title">Asignación</h3>
              <div class="cli-cols cli-cols--3">
                <div class="cli-field">
                  <label for="dtApertura">Fecha de inicio</label>
                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" inputmode="numeric" value="{{ date('d/m/y') }}"/>
                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                      <span class="input-group-text" aria-hidden="true"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="cli-field">
                  <label for="slDiaVisita">Día de visita</label>
                  <select class="form-control" id="slDiaVisita">
                    @foreach ($DiasSemana as $d)
                      <option value="{{ $d->id_diassemana }}">{{ ucfirst(mb_strtolower($d->dia_semana)) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="cli-field">
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
              <button type="button" class="cli-btn cli-btn--ghost" data-dismiss="modal">Cancelar</button>
              <button type="button" class="cli-btn cli-btn--primary" id="btn_save_credito"><i class="fas fa-check" aria-hidden="true"></i> Guardar crédito</button>
            </div>
          </div>
        </div>
      </div>

      {{-- ===================== Modal: Editar cliente ===================== --}}
      <div class="modal fade cli-modal" id="mdl_edit_cliente" tabindex="-1" aria-labelledby="mdlEditTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title" id="mdlEditTitle">Editar cliente</h2>
              <span id="edtIdClient" style="display:none"></span>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="cli-cols cli-cols--2">
                <div class="cli-field">
                  <label for="edtNombre">Nombre</label>
                  <input type="text" id="edtNombre" class="form-control" autocapitalize="words">
                </div>
                <div class="cli-field">
                  <label for="edtApellido">Apellido</label>
                  <input type="text" id="edtApellido" class="form-control" autocapitalize="words">
                </div>
              </div>
              <div class="cli-cols cli-cols--4">
                <div class="cli-field">
                  <label for="edtTelefono">Teléfono</label>
                  <input type="tel" class="form-control" id="edtTelefono" inputmode="numeric" onkeypress="return isNumberKey(event)">
                </div>
                <div class="cli-field">
                  <label for="edtCedula">Cédula</label>
                  <input type="text" class="form-control" id="edtCedula" autocapitalize="characters" data-inputmask="'mask': ['999-999999-9999A']" data-mask>
                </div>
                <div class="cli-field">
                  <label for="edtMunicipio">Departamento</label>
                  <select class="form-control" id="edtMunicipio">
                    @foreach ($Municipios as $m)
                      <option value="{{ $m->id_municipio }}">{{ strtoupper($m->nombre_municipio) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="cli-field">
                  <label for="edtZonas">Zona</label>
                  <select class="form-control" id="edtZonas">
                    @foreach ($Zonas as $z)
                      <option value="{{ $z->id_zona }}">{{ strtoupper($z->nombre_zona) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="cli-field">
                <label for="edtDireccion">Dirección</label>
                <textarea class="form-control" id="edtDireccion" rows="2"></textarea>
              </div>

              <h3 class="cli-form-group-title">Créditos del cliente</h3>
              <div class="table-responsive">
                <table class="table cli-subtable" id="tblCreditosCliente" style="width:100%"></table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="cli-btn cli-btn--ghost" data-dismiss="modal">Cancelar</button>
              <button type="button" class="cli-btn cli-btn--primary" id="btn_update_credito"><i class="fas fa-check" aria-hidden="true"></i> Guardar cambios</button>
            </div>
          </div>
        </div>
      </div>

  </section>
</div>
@endsection
