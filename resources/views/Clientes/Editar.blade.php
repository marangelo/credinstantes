@extends('layouts.lyt_listas')

@section('metodosjs')
    @include('jsViews.js_editar_credito')
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$Titulo}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">{{$Titulo}}</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">CREDITO #: <span id="id_credito">{{$IdCredito}}</span> </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success" id="btn_actualizar_credito">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">                    
                                <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Fecha Inicio: </label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" value="{{ date('d/m/y', strtotime($Credito->fecha_apertura)) }}"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>                      
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Dia de Visita:</label>
                                        <select class="form-control" id="slDiaVisita">
                                            @foreach ($DiasSemana as $d)
                                                <option value="{{$d->id_diassemana}}" {{($Credito->id_diassemana == $d->id_diassemana) ? 'selected' : ''}}>
                                                    {{strtoupper($d->dia_semana)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                      
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Promotor</label>
                                        <select class="form-control" id="slPromotor">                                    
                                            @foreach ($Promo as $p)
                                                <option value="{{$p->id}}" {{($Credito->asignado == $p->id) ? 'selected' : ''}}>
                                                    {{strtoupper($p->nombre)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                      
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ..." value="{{$Credito->Clientes->nombre}}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Apellido</label>
                                        <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ..." value="{{$Credito->Clientes->apellidos}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Telefono:</label>
                                        <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="txtTelefono" onkeypress='return isNumberKey(event)' value="{{$Credito->Clientes->telefono}}" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Cedula:</label>
                                        <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-address-card"></i></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{$Credito->Clientes->cedula}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Departamento: </label>
                                        <select class="form-control" id="selMunicipio">
                                            @foreach ($Municipios as $m)
                                                <option value="{{ $m->id_municipio }}" {{($Credito->Clientes->id_municipio == $m->id_municipio) ? 'selected' : ''}}>
                                                    {{strtoupper($m->nombre_municipio)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Zonas: </label>
                                        <select class="form-control" id="selZona">
                                            @foreach ($Zonas as $z)
                                                <option value="{{ $z->id_zona }}" {{($Credito->Clientes->id_zona == $z->id_zona) ? 'selected' : ''}}>
                                                    {{strtoupper($z->nombre_zona)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                                
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>DIRECCION</label>
                                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ...">
                                            {{trim($Credito->Clientes->direccion_domicilio)}}
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Monto</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" id="txtMonto" class="form-control" placeholder="C$ 0.00"  onkeypress='return isNumberKey(event)' value="{{number_format($Credito->monto_credito,2,'.','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Plazo</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" id="txtPlazo" class="form-control" placeholder="Numero de Meses" onkeypress='return isNumberKey(event)' value="{{number_format($Credito->plazo,0,'','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Interes</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                            </div>
                                            <input type="text" id="txtInteres" class="form-control" placeholder="0.00 %" onkeypress='return isNumberKey(event)' value="{{number_format($Credito->taza_interes,2,'.','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>N° Cuotas</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" id="txtCuotas" class="form-control" placeholder="Numero de Cuotas" onkeypress='return isNumberKey(event)' value="{{number_format($Credito->numero_cuotas,0,'','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Total</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="txt" id="txtTotal" class="form-control" placeholder="C$ 0.00" disabled value="{{number_format($Credito->total,2,'.','')}}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Cuota</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" id="txtVlCuota" class="form-control" placeholder="C$ 0.00" disabled value="{{number_format($Credito->cuota,2,'.','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Saldos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" id="txtSaldos" class="form-control" placeholder="C$ 0.00" disabled value="{{number_format($Credito->saldo,2,'.','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Intereses </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" id="txtIntereses" class="form-control" placeholder="C$ 0.00" disabled value="{{number_format($Credito->interes,2,'.','')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Intereses por cuota </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" id="txtInteresesPorCuota" class="form-control" placeholder="C$ 0.00" disabled value="{{number_format($Credito->intereses_por_cuota,2,'.','')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection