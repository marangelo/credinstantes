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
              <li class="breadcrumb-item active"> <span id="lbl_titulo_origen"> {{$Request->Origen ?? $Origin}} </span> </li>
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
                            <option value="{{$d->id_diassemana}}" {{ ( (isset($Request->visit_day) ? $Request->visit_day :  date('w') ) == $d->id_diassemana) ? 'selected' : '' }}> {{strtoupper($d->dia_semana)}} </option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Promotor  </label>

                        <select class="form-control" id="slPromotor">

                          @foreach ($Promo as $p)
                            <option 
                              value="{{$p->id}}" {{ ( ( Auth::user()->id == $p->id) || (isset($Request->promoter) && $Request->promoter == $p->id)) ? 'selected' : '' }}> {{strtoupper($p->nombre)}} 
                            </option>
                          @endforeach

                        </select>
                      </div>                      
                    </div>

                    <div class="col-12">

                      <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                              <li class="nav-item">
                                  <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">General</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Info. Negocio</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Datos del Conyugue</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Detalles de Garantias</a>
                              </li>
                          </ul>
                        </div>
                        <div class="card-body">
                          <div class="tab-content" id="custom-tabs-one-tabContent">
                              <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                                <div class="card-body p-0">
                                    <div class="row gx-2">
                                        <div class="col-sm-6 col-md-3 mb-3">
                                            <label class="form-label" for="event-name">Detalle</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                <input class="form-control" type="text" name="nombres" placeholder="Detalles" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>                                                
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-3">
                                            <label class="form-label" for="event-name">Marca</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                <input class="form-control" type="text" name="nombres" placeholder="Marca" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>                                                
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-3">
                                            <label class="form-label" for="event-name">Color</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                <input class="form-control" type="text" name="nombres" placeholder="Color" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>                                                
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-3">
                                            <label class="form-label" for="event-name">Valor</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                <input class="form-control" type="text" name="nombres" placeholder="C$. 0.00" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                                                </span>
                                            </div>                                      
                                        </div>
                                    </div>
                                  
                                    <ul class="products-list product-list-in-card pl-2 pr-2 mt-3">
                                        <div class="col-12">
                                            <h4>
                                                <i class="fas fa-globe"></i> Articulos, No.
                                                <small class="float-right">( 4 )</small>
                                            </h4>
                                        </div>
                                        <li class="item">
                                            <div class="product-img">
                                            <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                            </div>
                                            <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">Samsung TV
                                                <span class="badge badge-warning float-right">C$1800</span></a>
                                            <span class="product-description">
                                                Samsung 32" 1080p 60Hz LED Smart HDTV.
                                            </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                        <li class="item">
                                            <div class="product-img">
                                            <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                            </div>
                                            <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">Bicycle
                                                <span class="badge badge-info float-right">C$700</span></a>
                                            <span class="product-description">
                                                26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                            </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                        <li class="item">
                                            <div class="product-img">
                                            <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                            </div>
                                            <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">
                                                Xbox One <span class="badge badge-danger float-right">
                                                C$350
                                            </span>
                                            </a>
                                            <span class="product-description">
                                                Xbox One Console Bundle with Halo Master Chief Collection.
                                            </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                        <li class="item">
                                            <div class="product-img">
                                            <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                            </div>
                                            <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">PlayStation 4
                                                <span class="badge badge-success float-right">C$399</span></a>
                                            <span class="product-description">
                                                PlayStation 4 500GB Console (PS4)
                                            </span>
                                            </div>
                                        </li>
                                    <!-- /.item -->
                                    </ul>
                                </div>
                                  
                              </div>
                              <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">                                    
                                  <div class="row gx-2">
                                      <div class="col-sm-6 mb-3">
                                          <label class="form-label" for="event-name">Nombres</label>
                                          <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                              <input class="form-control" type="text" name="nombres" placeholder="Nombres de la persona" required="" value="{{ $Employee->first_name ?? '' }}" />
                                              <div class="invalid-feedback">Campo Requerido.</div>
                                          </div>
                                      </div>

                                      <div class="col-sm-6 mb-3">
                                          <label class="form-label" for="event-name">Apellidos</label>
                                          <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                              <input class="form-control" type="text" name="apellidos" placeholder="Apellidos de la persona" required="" value="{{ $Employee->last_name ?? '' }}" />
                                              <div class="invalid-feedback">Campo Requerido.</div>
                                          </div>
                                      </div>
                                      
                                      <div class="col-sm-6 col-md-6 mb-3">
                                          <label class="form-label" for="event-name">Cedula</label>
                                          <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                              <input class="form-control" id="event-name" type="text" name="cedula" placeholder="000-000000-0000A" data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Employee->cedula_number ?? '' }}"/>
                                              <div class="invalid-feedback">Campo Requerido.</div>
                                          </div>
                                      </div>

                                      <div class="col-sm-6 col-md-6 mb-3">
                                          <label class="form-label" for="event-name">Telefono</label>
                                          <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                              <input class="form-control" id="event-name" type="text" name="telefono" placeholder="+505-0000-000" value="{{ $Employee->phone_number ?? '' }}" />
                                          </div>
                                      </div>
                                      
                                      <div class="col-12">
                                          <div class="row">
                                              <div class="col-12">
                                                  <label class="form-label" for="event-description">Nombre y Direccion</label>
                                                  <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Employee->address ?? '' }}</textarea>
                                              </div>                                                
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <div class="row gx-2">
                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label class="form-label" for="event-name">Nombres</label>
                                        <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                            <input class="form-control" type="text" name="nombres" placeholder="Nombres del negocio" required="" value="{{ $Employee->first_name ?? '' }}" />
                                            <div class="invalid-feedback">Campo Requerido.</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label class="form-label" for="event-name">Antiguedad</label>
                                        <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                            <input class="form-control" type="text" name="apellidos" placeholder="Antiguedad del negocio" required="" value="{{ $Employee->last_name ?? '' }}" />
                                            <div class="invalid-feedback">Campo Requerido.</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label" for="event-description">Direccion del negocio</label>
                                                <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Employee->address ?? '' }}</textarea>
                                            </div>                                                
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                  <div class="row gx-2">
                                    <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                    
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
                                  </div>
                                </div>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                    
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Monto</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtMonto" class="form-control"  value="{{ $Request->monto ?? '0.00' }}" 
                            data-mask data-inputmask="'alias': 'currency' "
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
                            <input type="text" id="txtPlazo" class="form-control"  value="{{ $Request->plazo ?? '0.00' }}"
                            data-mask data-inputmask="'alias': 'currency' ">
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
                            <input type="text" id="txtInteres" class="form-control"  value="{{ $Request->interes_porcent ?? '0.00' }}"
                            data-mask data-inputmask="'alias': 'currency'">
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
                            <input type="text" id="txtCuotas" class="form-control"  value="{{ $Request->num_cuotas ?? '0.00' }}" 
                            data-mask data-inputmask="'alias': 'currency' ">
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
                            <input type="txt" id="txtTotal" class="form-control"  disabled value="{{ $Request->total ?? '0.00' }}" 
                            data-mask data-inputmask="'alias': 'currency'  ">
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
                            <input type="text" id="txtVlCuota" class="form-control" disabled value="{{ $Request->cuota ?? '0.00' }}" 
                            data-mask data-inputmask="'alias': 'currency' ">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4 ">
                      <div class="form-group">
                        <label>Saldos</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtSaldos" class="form-control"  disabled value="{{ $Request->saldo ?? '0.00' }}" 
                            data-mask data-inputmask="'alias': 'currency' ">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4 {{ in_array(Session::get('rol'), [1,3])? '' : 'invisible' }}" >
                      <div class="form-group">
                        <label>Intereses </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                              <input 
                                type="text" 
                                id="txtIntereses" 
                                class="form-control"  
                                value="{{ $Request->interes_valor ?? '0.00' }}" 
                                disabled
                                data-mask data-inputmask="'alias': 'currency' ">
                            </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4 {{ in_array(Session::get('rol'), [1,3,5]) ? '' : 'invisible' }}" >
                      <div class="form-group">
                        <label>Intereses por cuota </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                              <input 
                                type="text" 
                                id="txtInteresesPorCuota" 
                                class="form-control" 
                                value="{{ $Request->intereses_por_cuota ?? '0.00' }}" 
                                disabled 
                                data-mask data-inputmask="'alias': 'currency' ">
                          </div>
                      </div>
                    </div>
                    <input type="hidden" name="name_id_Client" id="id_Client" value="{{ $Request->id_cliente ?? 0 }}" >
                    @if ( ( $Request->id_req ?? 0 ) == 0  || in_array(Session::get('rol'), [1,3,5])  )
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
                    @endif


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