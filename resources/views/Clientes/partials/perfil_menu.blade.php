{{-- Menú "⋯" de un crédito: Editar · Documentos · Eliminar (según rol). Requiere $c, $nAbonos --}}
@php $rolMenu = (string) Session::get('rol'); @endphp
<div class="dropdown pf-more">
  <button type="button" class="pf-btn pf-btn--icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Más acciones del crédito #{{ $c->id_creditos }}">
    <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu dropdown-menu-right pf-menu">
    @if (in_array($rolMenu, ['1', '5']))
      @if ($nAbonos > 0)
        <button type="button" class="dropdown-item" disabled>
          <i class="fas fa-edit" aria-hidden="true"></i>
          <span>Editar crédito<span class="pf-menu-note">No se puede: ya tiene pagos</span></span>
        </button>
      @else
        <button type="button" class="dropdown-item" onclick="EditarCredito({{ $c->id_creditos }})">
          <i class="fas fa-edit" aria-hidden="true"></i> Editar crédito
        </button>
      @endif
      <div class="dropdown-divider"></div>
      <a href="{{ route('PagareALaOrden', ['id' => $c->id_creditos]) }}" target="_blank" rel="noopener" class="dropdown-item">
        <i class="far fa-file-alt" aria-hidden="true"></i> Pagaré
      </a>
      <a href="{{ route('SolicitudCredito', ['id' => $c->id_creditos]) }}" target="_blank" rel="noopener" class="dropdown-item">
        <i class="far fa-file-alt" aria-hidden="true"></i> Solicitud de crédito
      </a>
    @endif
    @if ($rolMenu === '1')
      <div class="dropdown-divider"></div>
      <button type="button" class="dropdown-item pf-menu-danger" onclick="rmItem({{ $c->id_creditos }})">
        <i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar crédito
      </button>
    @endif
  </div>
</div>
