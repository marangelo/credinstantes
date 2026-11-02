@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_requests_form')
@endsection

@section('content')
@php
  $rol = (int) Session::get('rol');

  $v = [
    'origen'       => $Origin,
    'fecha'        => date('d/m/Y'),
    'dia_visita'   => date('w'),
    'promotor'     => Auth::user()->id,
    'nombre'       => $Cliente->nombre ?? '',
    'apellido'     => $Cliente->apellidos ?? '',
    'telefono'     => $Cliente->telefono ?? '',
    'cedula'       => $Cliente->cedula ?? '',
    'municipio'    => $Cliente->id_municipio ?? null,
    'zona'         => $Cliente->id_zona ?? null,
    'estado_civil' => $Cliente->estado_civil ?? '',
    'direccion'    => $Cliente->direccion_domicilio ?? '',
    'negocio'      => $Cliente ? $Cliente->getNegocio : null,
    'conyugue'     => $Cliente ? $Cliente->getConyugue : null,
    'fiador'       => $Cliente ? $Cliente->getFiador : null,
    'referencias'  => $Cliente ? $Cliente->getReferencias : [],
    'garantias'    => $Cliente ? $Cliente->getGarantias : [],
    // En una renovación las condiciones del crédito se capturan de cero
    'monto'        => '', 'respaldo' => '', 'plazo' => '', 'interes' => '', 'cuotas' => '',
    'total'        => '', 'cuota' => '', 'saldo' => '', 'intereses' => '', 'int_cuota' => '',
    'id_req'       => 0,
    'id_cliente'   => $Cliente->id_clientes ?? 0,
  ];

  $nombreCliente = trim(($Cliente->nombre ?? '') . ' ' . ($Cliente->apellidos ?? ''));

  $p = [
    'titulo'              => 'Solicitud de renovación',
    'subtitulo'           => $nombreCliente !== '' ? $nombreCliente : null,
    'volver_url'          => route('Solicitudes/Lista/Renovaciones'),
    'volver_texto'        => 'Renovaciones',
    'puede_editar'        => true,
    'fecha_bloqueada'     => false,
    'dia_bloqueado'       => in_array($rol, [2, 4, 5]),
    'respaldo_editable'   => $rol == 1,
    'ver_intereses'       => $rol == 1,
    'ver_int_cuota'       => $rol == 1,
    'puede_agregar_filas' => false,   // referencias y garantías vienen del cliente
    'mostrar_acciones'    => true,
    'mostrar_eliminar'    => false,
    'texto_guardar'       => 'Procesar renovación',
  ];
@endphp

@include('Solicitudes.partials.form', ['v' => $v, 'p' => $p])
@endsection
