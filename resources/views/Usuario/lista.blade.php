@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_usuarios')
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/usuarios.css') }}?v={{ filemtime(public_path('css/usuarios.css')) }}">

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid us">

      <header class="us-header">
        <div>
          <h1 class="us-title">Usuarios</h1>
          <p class="us-subtitle">{{ $Usuarios->count() }} usuarios del sistema</p>
        </div>
        <button type="button" class="us-btn us-btn--primary" id="btn_form_add_user">
          <i class="fas fa-plus" aria-hidden="true"></i> <span>Nuevo usuario</span>
        </button>
      </header>

      <div class="us-toolbar">
        <div class="us-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <input type="search" class="form-control" id="usSearch" placeholder="Buscar por nombre, email, permiso o zona"
                 aria-label="Buscar usuarios" autocomplete="off" enterkeyhint="search">
        </div>
      </div>

      <div class="us-list">
        <table id="tbl_usrs" class="table us-table no-cards" width="100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Usuario</th>
              <th>Email</th>
              <th>Permiso</th>
              <th>Zona</th>
              <th>Creación</th>
              <th><span class="us-sr-only">Acciones</span></th>
            </tr>
          </thead>
          <tbody>
          @foreach ($Usuarios as $u)
            @php
              $activo = $u->Lock > 0;
              $nombre = strtoupper($u->nombre);
            @endphp
            <tr>
              <td class="c-id">{{ $u->id }}</td>
              <td class="c-name">
                <span class="us-name">{{ $nombre }}</span>
              </td>
              <td class="c-email">{{ strtolower($u->email) }}</td>
              @php $zonaUs = strtoupper($u->Zona->nombre_zona ?? ''); @endphp
              <td class="c-rol" data-zona="{{ $zonaUs ? ' · ' . $zonaUs : '' }}">{{ ucfirst(mb_strtolower($u->RolName->descripcion ?? '')) }}</td>
              <td class="c-zona">{{ $zonaUs }}</td>
              <td class="c-fecha" data-order="{{ $u->created_at }}">{{ Date::parse($u->created_at)->format('d M Y') }}</td>
              <td class="c-actions">
                <div class="us-actions">
                  <button type="button" class="us-btn" onclick="Edit_form({{ $u }})" aria-label="Editar a {{ $nombre }}">
                    <i class="fas fa-pen" aria-hidden="true"></i><span>Editar</span>
                  </button>
                  <button type="button" class="us-btn {{ $activo ? 'us-btn--ok' : 'us-btn--lock' }}" onclick="Lock({{ $u->id }}, {{ $activo ? 'true' : 'false' }})"
                          title="{{ $activo ? 'Desbloqueado: toque para bloquear' : 'Bloqueado: toque para desbloquear' }}"
                          aria-label="{{ $nombre }} está {{ $activo ? 'desbloqueado. Bloquear' : 'bloqueado. Desbloquear' }}">
                    {{-- Verde = desbloqueado, rojo = bloqueado: el botón muestra el ESTADO; la acción se confirma al tocarlo --}}
                    <i class="fas {{ $activo ? 'fa-lock-open' : 'fa-lock' }}" aria-hidden="true"></i><span>{{ $activo ? 'Desbloqueado' : 'Bloqueado' }}</span>
                  </button>
                  <div class="dropdown">
                    <button type="button" class="us-btn us-btn--icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Más acciones para {{ $nombre }}">
                      <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right us-menu">
                      <button type="button" class="dropdown-item us-menu-danger" onclick="Remove_form({{ $u->id }})">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar usuario
                      </button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  {{-- ===================== Hoja: Nuevo / Editar usuario ===================== --}}
  <div class="modal fade us-modal" id="modal_xl_add_user" tabindex="-1" aria-labelledby="usModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="usModalTitle">Nuevo usuario</h2>
          <span id="id_estado" style="display:none">Estado</span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <h3 class="us-group-title">Datos</h3>
          <div class="us-cols us-cols--3">
            <div class="us-field">
              <label for="txtFullName">Nombre completo</label>
              <input type="text" id="txtFullName" class="form-control" autocapitalize="words" autocomplete="off">
            </div>
            <div class="us-field">
              <label for="txtUserName">Email</label>
              <input type="email" id="txtUserName" class="form-control" inputmode="email" autocapitalize="off" autocomplete="off" spellcheck="false" placeholder="usuario@credinstante.com">
            </div>
            <div class="us-field">
              <label for="txtPhone">Teléfono</label>
              <input type="tel" id="txtPhone" class="form-control" inputmode="numeric" data-inputmask="'mask': ['+505-9999-9999']" data-mask>
            </div>
          </div>

          <h3 class="us-group-title">Acceso</h3>
          <div class="us-cols us-cols--2">
            <div class="us-field">
              <label for="txtPassWord_one">Contraseña</label>
              <input type="password" id="txtPassWord_one" class="form-control" autocomplete="new-password">
            </div>
            <div class="us-field">
              <label for="txtPassWord_two">Confirme la contraseña</label>
              <input type="password" id="txtPassWord_two" class="form-control" autocomplete="new-password">
            </div>
            <div class="us-field">
              <label for="sclPrivi">Permiso</label>
              <select class="form-control" id="sclPrivi">
                @foreach ($Roles as $r)
                  <option value="{{ $r->id }}">{{ strtoupper($r->descripcion) }}</option>
                @endforeach
              </select>
            </div>
            <div class="us-field">
              <label for="sclZona">Zona</label>
              <select class="form-control" id="sclZona">
                @foreach ($Zonas as $z)
                  <option value="{{ $z->id_zona }}">{{ strtoupper($z->nombre_zona) }}</option>
                @endforeach
              </select>
            </div>
          </div>

          @if (in_array(Session::get('rol'), [1]))
            <div class="us-field" id="div_zonas_promotor" style="display:none">
              <label for="sclZonasPromotor">Zonas del Promotor</label>
              <select class="form-control select2" id="sclZonasPromotor" multiple="multiple" data-placeholder="Seleccione zonas" style="width: 100%;">
                @foreach ($Zonas as $z)
                  <option value="{{ $z->id_zona }}">{{ strtoupper($z->nombre_zona) }}</option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="us-field">
            <label for="txtComentario">Comentarios</label>
            <textarea class="form-control" id="txtComentario" rows="2" placeholder="Algún comentario…"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="us-btn" data-dismiss="modal">Cancelar</button>
          <button type="button" class="us-btn us-btn--primary" id="btn_save_user"><i class="fas fa-check" aria-hidden="true"></i> Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
