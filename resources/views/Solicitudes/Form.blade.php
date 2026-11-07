@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_requests_form')
@endsection

@section('content')
@php
  $rol    = (int) Session::get('rol');
  $idReq  = $Request->id_req ?? 0;
  // Una solicitud ya registrada solo la modifican Admin (1) y Operaciones (3)
  $puedeEditar = $idReq == 0 || in_array($rol, [1, 3]);

  $v = [
    'origen'       => $Request->Origen ?? $Origin,
    'fecha'        => isset($Request->req_start_date) ? date('d/m/Y', strtotime($Request->req_start_date)) : date('d/m/Y'),
    'dia_visita'   => $Request->visit_day ?? date('w'),
    'promotor'     => old('promoter', $Request->promoter ?? Auth::user()->id),
    'nombre'       => $Request->first_name ?? '',
    'apellido'     => $Request->last_name ?? '',
    'telefono'     => $Request->phone ?? '',
    'cedula'       => $Request->num_cedula ?? '',
    'municipio'    => $Request->id_department ?? null,
    'zona'         => $Request->id_zone ?? null,
    'estado_civil' => $Request->estado_civil ?? '',
    'direccion'    => $Request->client_address ?? '',
    'comentario'   => $Request->comentario ?? '',
    'negocio'      => $Request ? $Request->getNegocio : null,
    'conyugue'     => $Request ? $Request->getConyugue : null,
    'fiador'       => $Request ? $Request->getFiador : null,
    'referencias'  => $Request ? $Request->getReferencias : [],
    'garantias'    => $Request ? $Request->getGarantias : [],
    'monto'        => $Request->monto ?? '0.00',
    'respaldo'     => $Request->Seguro ?? '0.00',
    'plazo'        => $Request->plazo ?? '0.00',
    'interes'      => $Request->interes_porcent ?? '0.00',
    'cuotas'       => $Request->num_cuotas ?? '0.00',
    'total'        => $Request->total ?? '0.00',
    'cuota'        => $Request->cuota ?? '0.00',
    'saldo'        => $Request->saldo ?? '0.00',
    'intereses'    => $Request->interes_valor ?? '0.00',
    'int_cuota'    => $Request->intereses_por_cuota ?? '0.00',
    'id_req'       => $idReq,
    'id_cliente'   => $Request->id_cliente ?? 0,
  ];

  $nombreCliente = trim(($Request->first_name ?? '') . ' ' . ($Request->last_name ?? ''));

  $p = [
    'titulo'              => $idReq > 0 ? 'Solicitud #' . $idReq : 'Nueva solicitud',
    'subtitulo'           => $nombreCliente !== '' ? $nombreCliente : 'Complete los datos del cliente y las condiciones del crédito.',
    'volver_url'          => route('Solicitudes/Lista/Nuevos'),
    'volver_texto'        => 'Solicitudes',
    'puede_editar'        => $puedeEditar,
    'fecha_bloqueada'     => in_array($rol, [2, 4, 5]),
    'dia_bloqueado'       => in_array($rol, [2, 4, 5]),
    'respaldo_editable'   => $rol == 1,
    'ver_intereses'       => in_array($rol, [1, 3]),
    'ver_int_cuota'       => in_array($rol, [1, 3, 5]),
    'puede_agregar_filas' => true,
    'mostrar_acciones'    => $puedeEditar,
    'mostrar_eliminar'    => $puedeEditar,
    'texto_guardar'       => 'Procesar solicitud',
  ];
@endphp

@include('Solicitudes.partials.form', ['v' => $v, 'p' => $p])
@endsection
