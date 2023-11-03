@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_clientes')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Clientes</h1>
          </div>
       
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Clientes</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <span id="id_rol_user"> {{Session::get('rol')}}</span>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Listado de Clientes</h3>
                <div class="card-tools">  
                  @if (request()->is('Activos'))
                      @if (Session::get('rol') == '1' || Session::get('rol') == '3')
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-xl">
                              NUEVO
                          </button>
                      @endif
                  @endif
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_clientes" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Departamento</th>
                    <th>Zona</th>
                    <th>Direccion</th>                    
                    @if (request()->is('Activos'))
                      @if (Session::get('rol') == '1' || Session::get('rol') == '3')
                        <th></th>
                      @endif
                    @endif
                  </tr>
                  </thead>
                  <tbody>

                  @foreach ($Clientes as $c)  
                    <tr>
                      <td>
                        <a href="Perfil/{{ strtoupper($c->id_clientes) }}" class=""><strong>#{{ strtoupper($c->id_clientes) }} </strong> : {{ strtoupper($c->nombre) }} : {{ strtoupper($c->apellidos) }}</a>
                          @if ($c->tieneCreditoVencido->isNotEmpty())
                              <span class="badge @switch($c->tieneCreditoVencido->first()->estado_credito)
                                              @case(1)
                                                  bg-success
                                                  @break
                                              @case(2)
                                                  bg-danger
                                                  @break
                                              @case(3)
                                                  bg-warning
                                                  @break
                                              @default
                                                  ''
                                          @endswitch">{{ $c->tieneCreditoVencido->first()->Estado->nombre_estado }}</span>
                          @else
                            @if ($c->getCreditos->isNotEmpty())
                                <span class="badge @switch($c->getCreditos->first()->estado_credito)
                                              @case(1)
                                                  bg-success
                                                  @break
                                              @case(2)
                                                  bg-danger
                                                  @break
                                              @case(3)
                                                  bg-warning
                                                  @break
                                              @default
                                                  ''
                                          @endswitch">{{ $c->getCreditos->first()->Estado->nombre_estado }}</span>
                            @else 
                                <p>- </p>
                            @endif
                          @endif
                      </td>
                      <td>{{ strtoupper($c->telefono) }} </td>
                      <td>
                        @if(isset($c->getMunicipio->nombre_municipio) && $c->getMunicipio->nombre_municipio)
                          {{ strtoupper($c->getMunicipio->nombre_municipio) }} 
                        @endif 
                      </td>
                      <td>
                        @if(isset($c->getZona->nombre_zona) && $c->getZona->nombre_zona)
                          {{ strtoupper($c->getZona->nombre_zona) }} 
                        @endif 
                        
                      </td>
                      <td>{{ strtoupper($c->direccion_domicilio) }}  </td>  
                      
                        @if (request()->is('Activos'))
                          @if (Session::get('rol') == '1' || Session::get('rol') == '3')
                          <td>
                            <div class="card-tools text-center">  
                                <a class="btn btn-primary btn-sm" href="#"  onclick="eCliente({{$c}})">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Editar
                                </a>
                                
                                <a class="btn btn-danger btn-sm" href="#" onclick="rmItem({{$c->id_clientes}})">
                                    <i class="fas fa-trash">
                                    </i>
                                    Remover
                                </a>
                              </div>
                              </td>
                          @endif
                        @endif
                      
                    </tr>
                    @endforeach


                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
      <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Credito</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <div class="card card-warning">
            
              <!-- /.card-header -->
              <div class="card-body">                  
                  <div class="row">
                    
                    <div class="col-sm-2">
                      <div class="form-group">
                          <label>Fecha Inicio</label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" value="{{ date('d/m/y') }}"/>
                              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>                      
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <label>Dia de Visita</label>
                        <select class="form-control" id="slDiaVisita">
                          @foreach ($DiasSemana as $d)
                            <option value="{{$d->id_diassemana}}"> {{strtoupper($d->dia_semana)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ...">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ...">
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
                          <input type="text" class="form-control" id="txtTelefono" data-inputmask="'mask': ['+505-9999-9999']" data-mask>
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
                          <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask>
                        </div>
                      </div>
                      
                    </div>

                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Departamento</label>
                        <select class="form-control" id="selMunicipio">
                          @foreach ($Municipios as $m)
                            <option value="{{$m->id_municipio}}"> {{strtoupper($m->nombre_municipio)}}</option>
                          @endforeach
                        </select>
                      </div>
                        
                    </div>

                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Zonas</label>
                        <select class="form-control" id="selZona">
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                    </div>
                    
                  </div>
                  
                  

                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..."></textarea>
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
                            <input type="text" id="txtMonto" class="form-control" placeholder="C$ 0.00"  onkeypress='return isNumberKey(event)'>
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
                            <input type="text" id="txtPlazo" class="form-control" placeholder="Numero de Meses" onkeypress='return isNumberKey(event)'>
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
                            <input type="text" id="txtInteres" class="form-control" placeholder="0.00 %" onkeypress='return isNumberKey(event)'>
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
                            <input type="text" id="txtCuotas" class="form-control" placeholder="Numero de Cuotas" onkeypress='return isNumberKey(event)'>
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
                            <input type="txt" id="txtTotal" class="form-control" placeholder="C$ 0.00" disabled>
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
                            <input type="text" id="txtVlCuota" class="form-control" placeholder="C$ 0.00" disabled>
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
                            <input type="text" id="txtSaldos" class="form-control" placeholder="C$ 0.00" disabled>
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
                            <input type="text" id="txtIntereses" class="form-control" placeholder="C$ 0.00" disabled>
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
                            <input type="text" id="txtInteresesPorCuota" class="form-control" placeholder="C$ 0.00" disabled>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btn_save_credito">Aplicar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <!-- /.container-fluid -->
      <div class="modal fade" id="mdl_edit_cliente">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editar Cliente</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span id="edtIdClient" style="display:none" ></span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card card-warning">
            
              <!-- /.card-header -->
              <div class="card-body">                  
                  <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="edtNombre" class="form-control" placeholder="Nombre ...">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="edtApellido" class="form-control" placeholder="Apellido ...">
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
                          <input type="text" class="form-control" id="edtTelefono" data-inputmask="'mask': ['999-9999-9999', '+099 999 9999']" data-mask>
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
                          <input type="text" class="form-control" id="edtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask>
                        </div>
                      </div>
                      
                    </div>
                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Municipio</label>
                        <select class="form-control" id="edtMunicipio">
                          @foreach ($Municipios as $m)
                            <option value="{{$m->id_municipio}}"> {{strtoupper($m->nombre_municipio)}}</option>
                          @endforeach
                        </select>
                      </div>
                        
                    </div>
                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Zonas</label>
                        <select class="form-control" id="edtZonas">
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                          @endforeach
                        </select>
                      </div>
                        
                    </div>
                    
                    
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="edtDireccion" rows="3" placeholder="Direcion ..."></textarea>
                      </div>
                    </div>
                  </div>

                 
                  <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table " id="tblCreditosCliente" style="width:100%">
                          
                        </table>
                      </div>
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btn_edit_credito">Aplicar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection