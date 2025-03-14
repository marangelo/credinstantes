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
            <h1 >SOLICITUDE DE RENOVACION</h1>
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
                          <label>Fecha Inicio</label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" value=" {{ $Request->req_start_date ?? date('d/m/y') }} "/>
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
                            <option 
                            {{ ( (isset($Request->visit_day) ? $Request->visit_day :  date('w') ) == $d->id_diassemana) ? 'selected' : '' }}
                            value="{{$d->id_diassemana}}"
                            > {{strtoupper($d->dia_semana)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Promotor</label>
                        <select class="form-control" id="slPromotor">
                          @foreach ($Promo as $p)
                            <option 
                              value="{{$p->id}}" {{ ( ( Auth::user()->id == $p->id) ) ? 'selected' : '' }} > 
                              {{strtoupper($p->nombre)}}
                            </option>
                            
                          @endforeach
                        </select>
                      </div>                      
                    </div>



                    <dic class="col-12">

                      <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                              <li class="nav-item">
                                  <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">GENERAL</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">INFO. NEGOCIO</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link"  id="ref-personales-tab" 
                                  data-toggle="pill" 
                                  href="#tabs-ref-personales" 
                                  role="tab" 
                                  aria-controls="custom-tabs-one-messages" 
                                  aria-selected="false">
                                  REF. PERSONALES
                              </a>

                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">DATOS DEL CONYUGUE</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">DETALLES DE GARANTIAS</a>
                              </li>
                          </ul>
                        </div>
                        <div class="card-body">
                          <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <div class="row gx-2">
                                  <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                  
                                    <div class="form-group">
                                      <label>Nombre</label>
                                      <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ..." value="{{ $Cliente->nombre ?? '' }}">
                                    </div>
                                  </div>
                                  <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                                    <!-- text input -->
                                    <div class="form-group">
                                      <label>Apellido</label>
                                      <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ..." value="{{ $Cliente->apellidos ?? '' }}">
                                    </div>
                                  </div>                  
                                  <div class="col-sm-6 col-xl-3">

                                    <div class="form-group">
                                      <label>Telefono:</label>
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="txtTelefono" onkeypress='return isNumberKey(event)' value="{{ $Cliente->telefono ?? '' }}" maxlength="8">
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
                                        <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{ $Cliente->cedula ?? '' }}">
                                      </div>
                                    </div>
                                    
                                  </div>
                                  <div class="col-sm-6">

                                    <div class="form-group">
                                      <label>Departamento</label>
                                      <select class="form-control" id="selMunicipio">
                                        @foreach ($Municipios as $m)
                                          <option value="{{$m->id_municipio}}" {{ (isset($Cliente->id_municipio) && $Cliente->id_municipio == $m->id_municipio) ? 'selected' : '' }}> {{strtoupper($m->nombre_municipio)}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                      
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label>Zonas</label>
                                      <select class="form-control" id="selZona">
                                        @foreach ($Zonas as $z)
                                          <option value="{{$z->id_zona}}" {{ (isset($Cliente->id_zona) && $Cliente->id_zona == $z->id_zona) ? 'selected' : '' }}> {{strtoupper($z->nombre_zona)}}</option>
                                        @endforeach
                                      </select>
                                    </div>                      
                                  </div>                
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label>DIRECCION</label>
                                      <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..." style="white-space: nowrap;">{{ $Cliente->direccion_domicilio ?? '' }}</textarea>
                                    </div>
                                  </div>  
                                </div>
                              </div>

                              <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <form action="#!" method="post" id="frm_info_cliente_negocio">
                                      <div class="row gx-2">
                                          <div class="col-sm-12 col-md-6 mb-3">
                                              <label class="form-label" for="event-name">Nombre</label>
                                              <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                  <input class="form-control" type="text" name="nombre_negocio" placeholder="Nombres del negocio" required="" 
                                                  
                                                    value="{{ $Cliente->getNegocio->nombre_negocio ?? '' }}" 
                                                  
                                                  />
                                                  <div class="invalid-feedback">Campo Requerido.</div>
                                              </div>
                                          </div>

                                          <div class="col-sm-12 col-md-6 mb-3">
                                              <label class="form-label" for="event-name">Antiguedad</label>
                                              <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                  <input class="form-control" type="text" name="Antiguedad_negocio" placeholder="Antiguedad del negocio" required="" value="{{ $Cliente->getNegocio->antiguedad ?? '' }}" />
                                                  <div class="invalid-feedback">Campo Requerido.</div>
                                              </div>
                                          </div>
                                          
                                          <div class="col-12">
                                              <div class="row">
                                                  <div class="col-12">
                                                      <label class="form-label" for="event-description">Direccion del negocio: </label>
                                                      <textarea class="form-control" rows="6" required="" name="direccion_negocio" >{{ $Cliente->getNegocio->direccion ?? '' }}</textarea>
                                                  </div>                                                
                                              </div>
                                          </div>
                                      </div>
                                  </form>
                              </div>


                              <div class="tab-pane fade" id="tabs-ref-personales" role="tabpanel" aria-labelledby="ref-personales-tab">
                                  <div class="card-body p-0">
                                      <div class="card">
                                          <div class="card-header border-transparent">
                                              <form action="#!" method="post" id="frm_add_referencias" >
                                                  <div class="row gx-2" style="display:none">
                                                      <div class="col-sm-6 col-md-3 mb-3">
                                                          <label class="form-label" for="event-name">NOMBRE</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="nombre_ref" id="id_nombre_ref" placeholder="Nombre de la referencia" value="" />
                                                          </div>                                                
                                                      </div>                                                            
                                                      <div class="col-sm-6 col-md-3 mb-3">
                                                          <label class="form-label" for="event-name">TELEFONO</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="telefono_ref" id="id_telefono_ref" placeholder="Telefono de la referencia" onkeypress='return isNumberKey(event)' maxlength="8" value="" />
                                                          </div>                                                
                                                      </div>  
                                                      <div class="col-sm-12 col-md-6 mb-3">
                                                          <label class="form-label" for="event-name">DIRECCION</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="direcion_ref" id="id_direcion_ref" placeholder="Direccion de la referencia" value="" />
                                                              <span class="input-group-append">
                                                                  <button type="button" class="btn btn-primary" id="btn_add_referencia"><i class="fas fa-plus"></i> Agregar</button>
                                                              </span>
                                                          </div>                                                
                                                      </div>                                                          
                                                  </div>
                                              </form>
                                          </div>
                                          <div class="card-body p-0">
                                              <div class="table-responsive">
                                                  <table class="table table-hover text-nowrap" id="tbl_refencias" width="100%">
                                                      <thead>
                                                          <tr>
                                                              <th>No.</th>
                                                              <th>NOMBRE</th>
                                                              <th>TELEFONO</th>
                                                              <th>DIRECCION</th>
                                                              <th></th>
                                                              <th>Nuevo</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                      @if(isset($Cliente) && $Cliente != null)
                                                        @foreach ($Cliente->getReferencias as $key => $ref)
                                                          <tr>
                                                              <td>{{ $key + 1 }}</td>
                                                              <td>{{ $ref->nombre_ref }}</td>
                                                              <td>{{ $ref->telefono_ref }}</td>
                                                              <td>{{ $ref->direccion_ref }}</td>
                                                              <td></td>
                                                              <td>S</td>
                                                          </tr>
                                                        @endforeach
                                                      @endif
                                                    </tbody>
                                                  </table>
                                              </div>
                                          </div>            
                                      </div>
                                  </div>
                              </div>
                            
                              <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">    
                                  <form action="#!" method="post" id="frm_info_conyugue">                                
                                      <div class="row gx-2">
                                          <div class="col-sm-6 mb-3">
                                              <label class="form-label" for="event-name">Nombres:</label>
                                              <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                  <input class="form-control" type="text" name="nombres_conyugue" placeholder="Nombres de la persona" required="" value="{{ $Cliente->getConyugue->nombres ?? '' }}" />
                                              </div>
                                          </div>

                                          <div class="col-sm-6 mb-3">
                                              <label class="form-label" for="event-name">Apellidos:</label>
                                              <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                  <input class="form-control" type="text" name="apellidos_conyugue" placeholder="Apellidos de la persona" required="" value="{{ $Cliente->getConyugue->apellidos ?? '' }}" />
                                              </div>
                                          </div>
                                          
                                          <div class="col-sm-6 col-md-6 mb-3">
                                              <label class="form-label" for="event-name">Cedula:</label>
                                              <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                  <input class="form-control" id="event-name" type="text" name="cedula_conyugue" placeholder="000-000000-0000A" 
                                                  data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Cliente->getConyugue->no_cedula ?? '' }}"/>
                                              </div>
                                          </div>

                                          <div class="col-sm-6 col-md-6 mb-3">
                                              <label class="form-label" for="event-name">Telefono:</label>
                                              <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                  <input class="form-control" id="event-name" type="text" name="telefono_conyugue" onkeypress='return isNumberKey(event)' maxlength="8" value="{{ $Cliente->getConyugue->telefono ?? '' }}" />
                                              </div>
                                          </div>
                                          
                                          <div class="col-12">
                                              <div class="row">
                                                  <div class="col-12">
                                                      <label class="form-label" for="event-description">Lugar de trabajo:</label>
                                                      <textarea class="form-control" rows="6" required="" name="direccion_conyugue" >{{ $Cliente->getConyugue->direccion_trabajo ?? '' }}</textarea>
                                                  </div>                                                
                                              </div>
                                          </div>
                                      </div>
                                  </form>
                              </div>

                              <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                                  <div class="card-body p-0">
                                      <div class="card">
                                          <div class="card-header border-transparent">
                                              <form action="#!" method="post" id="frm_add_garantias">
                                                  <div class="row gx-2" style="display:none">
                                                      <div class="col-sm-6 col-md-3 mb-3">
                                                          <label class="form-label" for="event-name">Detalle</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="detalles" id="detalles" placeholder="Detalles" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                          </div>                                                
                                                      </div>
                                                      <div class="col-sm-6 col-md-3 mb-3">
                                                          <label class="form-label" for="event-name">Marca</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="marca" id="marca" placeholder="Marca" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                          </div>                                                
                                                      </div>
                                                      <div class="col-sm-6 col-md-3 mb-3">
                                                          <label class="form-label" for="event-name">Color</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="color" id="color" placeholder="Color" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                          </div>                                                
                                                      </div>
                                                      <div class="col-sm-6 col-md-3 mb-3">
                                                          <label class="form-label" for="event-name">Valor</label>
                                                          <div class="input-group">
                                                              <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                              <input class="form-control" type="text" name="valor" id="valor"
                                                              placeholder="C$. 0.00"  />
                                                              <span class="input-group-append">
                                                                  <button type="button" class="btn btn-primary" id="btn_add_garantias"><i class="fas fa-plus"></i> Agregar</button>
                                                              </span>
                                                          </div>    
                                                                                                      
                                                      </div>
                                                  </div>
                                              </form>
                                          </div>
                                          <div class="card-body p-0">
                                              <div class="table-responsive">
                                                  <table class="table table-hover text-nowrap" id="tbl_garantias" width="100%">
                                                      <thead>
                                                          <tr>
                                                              <th>No.</th>
                                                              <th>Articulos, No. ( <span id="total_garantias">0</span> )</th>
                                                              <th>Marca</th>
                                                              <th>Color</th>
                                                              <th>Valor C$.</th>
                                                              <th> - </th>
                                                              <th>Nuevo</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                        @if(isset($Cliente) && $Cliente != null)
                                                          @foreach ($Cliente->getGarantias as $key => $garantia)
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
                                                        @endif
                                                      </tbody>
                                                  </table>
                                              </div>
                                          </div>            
                                      </div>
                                  </div>
                                      
                              </div>
                              

                          </div>
                        </div>
                        
                      </div>
                      


                    </dic>

                    
                   
                    
                    
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Monto</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtMonto" class="form-control" data-mask data-inputmask="'alias': 'currency' ">
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
                            <input type="text" id="txtPlazo" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" >
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
                            <input type="text" id="txtInteres" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" >
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
                            <input type="text" id="txtCuotas" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" >
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
                            <input type="txt" id="txtTotal" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" disabled >
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
                            <input type="text" id="txtVlCuota" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" disabled >
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
                            <input type="text" id="txtSaldos" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" disabled >
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4 {{ (Session::get('rol') == 1) ? '' : 'invisible' }}" >
                      <div class="form-group">
                        <label>Intereses </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtIntereses" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" disabled >
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4 {{ (Session::get('rol') == 1) ? '' : 'invisible' }}" >
                      <div class="form-group">
                        <label>Intereses por cuota </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtInteresesPorCuota" class="form-control" data-mask data-inputmask="'alias': 'currency' , 'placeholder': '0.00'" disabled >
                          </div>
                      </div>
                    </div>
                    @if ( ( $Request->id_req ?? 0 ) == 0  || in_array(Session::get('rol'), [1,3,5])  )
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="hidden" name="IdRequest" id="IdRequest" value="0" >
                            <input type="hidden" name="name_id_Client" id="id_Client" value="{{ $Cliente->id_clientes ?? 0 }}" >
                            <a href="#!" class="btn btn-danger btn-block" id="btn_save_prospecto" > <i class="fas fa-save"></i>  PROCESAR SOLICITUD</a>
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