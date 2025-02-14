@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_requests_form')
@endsection

@section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 >INFORMACION DEL FORMULARIO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Solicitudes</li>
              <li class="breadcrumb-item active"> <span id="lbl_titulo_origen"> {{$Origin}} </span> </li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header" style="display:none"></div>
              <div class="card-body">
                <div class="row">                    
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                          <label>Fecha Inicio </label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" 

                              value=" {{ isset($Request->req_start_date) ? date('d/m/Y', strtotime($Request->req_start_date)) :  date('d/m/Y') }} "
                              />
                              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>                      
                    </div>
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Dia de Visita</label>
                        <select class="form-control" id="slDiaVisita">
                          @foreach ($DiasSemana as $d)
                            <option value="{{$d->id_diassemana}}"> {{strtoupper($d->dia_semana)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Promotor</label>
                        <select class="form-control" id="slPromotor">
                          @foreach ($Promo as $p)
                            <option value="{{$p->id}}" {{ (isset($Request->promoter) && $Request->promoter == $p->id) ? 'selected' : '' }}> {{strtoupper($p->nombre)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ..." value="{{ $Request->first_name ?? '' }}">
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ..." value="{{ $Request->last_name ?? '' }}">
                      </div>
                    </div>                  
                    <div class="col-sm-6 col-xl-3">

                      <div class="form-group">
                        <label>Telefono:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtTelefono" onkeypress='return isNumberKey(event)' value="{{ $Request->phone ?? '' }}" maxlength="8">
                        </div>
                      </div>

                    </div>
                    <div class="col-sm-6 col-xl-3">

                      <div class="form-group">
                        <label>Cedula:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-address-card"></i></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{ $Request->num_cedula ?? '' }}">
                        </div>
                      </div>
                      
                    </div>
                    <div class="col-sm-6">

                      <div class="form-group">
                        <label>Departamento</label>
                        <select class="form-control" id="selMunicipio">
                          @foreach ($Municipios as $m)
                            <option value="{{$m->id_municipio}}" {{ (isset($Request->id_department) && $Request->id_department == $m->id_municipio) ? 'selected' : '' }}> {{strtoupper($m->nombre_municipio)}}</option>
                          @endforeach
                        </select>
                      </div>
                        
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Zonas</label>
                        <select class="form-control" id="selZona">
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}" {{ (isset($Request->id_zone) && $Request->id_zone == $z->id_zona) ? 'selected' : '' }}> {{strtoupper($z->nombre_zona)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>                
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..." style="white-space: nowrap;">{{ $Request->client_address ?? '' }}</textarea>
                      </div>
                    </div>                
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Monto</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtMonto" class="form-control"  onkeypress='return isNumberKey(event)' value="{{ $Request->monto ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'"
                            >
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Numero de Mes (Plazo) </label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="txtPlazo" class="form-control"  value="{{ $Request->plazo ?? '' }}"
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Interes</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                            </div>
                            <input type="text" id="txtInteres" class="form-control"  value="{{ $Request->interes_porcent ?? '' }}"
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>N° Cuotas</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="txtCuotas" class="form-control"  value="{{ $Request->num_cuotas ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Total</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="txt" id="txtTotal" class="form-control"  disabled value="{{ $Request->total ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Cuota</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtVlCuota" class="form-control" disabled value="{{ $Request->cuota ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Saldos</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtSaldos" class="form-control"  disabled value="{{ $Request->saldo ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Intereses </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtIntereses" class="form-control"  disabled value="{{ $Request->interes_valor ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Intereses por cuota </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtInteresesPorCuota" class="form-control" disabled value="{{ $Request->intereses_por_cuota ?? '' }}" 
                            data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'">
                          </div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                        <input type="hidden" name="IdRequest" id="IdRequest" value="{{ $Request->id_req ?? 0 }}" >
                        <div class="form-group">
                            
                            <a href="#!" class="btn btn-success btn-block" id="btn_save_prospecto" > <i class="fas fa-save"></i>  PROCESAR</a>
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            
                            <a href="#!" class="btn btn-danger btn-block" id="btn_remove_prospecto" > <i class="fas fa-window-close"></i>  CANCELAR</a>
                            
                        </div>
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
@endsection