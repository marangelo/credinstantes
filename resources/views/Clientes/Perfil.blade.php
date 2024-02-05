@extends('layouts.lyt_listas  ')
@section('metodosjs')
@include('jsViews.js_perfil')
@endsection
@section('content')
<div class="wrapper">
  <!-- Main Sidebar Container -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $perfil_cliente->nombre}} {{ $perfil_cliente->apellidos}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Perfil</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

      <div class="card card-widget">
              <div class="card-header">
                <div class="user-block">
                  <img class="img-circle" src="{{asset('img/user.png')}}" alt="User Image">
                  <span class="username"><a href="#">
                  @if(isset($perfil_cliente->getMunicipio->nombre_municipio) && $perfil_cliente->getMunicipio->nombre_municipio)
                    {{ strtoupper($perfil_cliente->getMunicipio->nombre_municipio) }} 
                  @endif 
                  / 

                  @if(isset($perfil_cliente->getMunicipio->getDepartamentos->nombre_departamento) && $perfil_cliente->getMunicipio->getDepartamentos->nombre_departamento)
                    {{ strtoupper($perfil_cliente->getMunicipio->getDepartamentos->nombre_departamento) }} 
                  @endif 
                </a></span>
                  <span class="description text-white"> {{ strtoupper($perfil_cliente->direccion_domicilio) }}</span>
                  <span class="description text-white ">Tel. {{ strtoupper($perfil_cliente->telefono) }} - Cedula. {{ strtoupper($perfil_cliente->cedula) }} </span>
                </div>
                <!-- /.user-block -->
                <div class="card-tools">
                  

                    @if(in_array(Session::get('rol'), array(1, 3, 4)))
                    <button type="button" class="btn btn-success" data-toggle="modal" id="btn_mdl_credito">
                        Nuevo
                      </button>
                    @endif
                  </div>
                </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row">
                  <div class="col-12 table-responsive">             
                    <table class="table table-striped" id="tbl_abonos_creditos">
                                <thead>
                                <tr>
                                  <th>COD/ESTADO</th>
                                  <th>INICIO</th>
                                  <th>FIN</th>
                                  <th>CULMINO</th>
                                  <th>PLAZO</th>
                                  <th>MONTO C$</th>
                                  <th>TOTAL C$</th>
                                  <th>SALDO C$</th>
                                  <th>CUOTAS</th>
                                  <th>ULTM. ABONO</th>
                                  <th>PENDIENTE C$</th>
                                  <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                  @foreach ($perfil_cliente->getCreditos as $c)
                                  <tr>
                                    <td>#{{$c->id_creditos}}
                                        <span class="badge @switch($c->estado_credito)
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
                                        @endswitch">{{ strtoupper($c->Estado->nombre_estado) }}</span>
                                    </td>
                                    <td>{{ Date::parse($c->fecha_apertura)->format('D, M d, Y') }}</td>
                                    <td>{{ Date::parse($c->fecha_ultimo_abono)->format('D, M d, Y') }}</td>
                                    <td>{{ is_null($c->fecha_culmina) ? '-' : Date::parse($c->fecha_culmina)->format('D, M d, Y')   }}</td>
                                    <td>{{number_format($c->plazo,1)}}</td>
                                    <td>{{number_format($c->monto_credito,2)}}  <span class="text-success"><i class="fas fa-arrow-up text-sm"></i> {{number_format($c->taza_interes,2)}} <small>%</small><span> </td>
                                    <td>{{number_format($c->total,2)}}</td>
                                    <td>{{number_format($c->saldo,2)}}</td>
                                    <td>{{number_format($c->abonosCount(), 0)}} / {{number_format($c->numero_cuotas, 0)}}</td>
                                    <td>
                                        @if($c->abonos->isNotEmpty())
                                        {{Date::parse($c->abonos->first()->fecha_cuota)->format('D, M d, Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($c->abonos->isNotEmpty())
                                          {{number_format($c->abonos->first()->saldo_cuota,2)}}
                                        @else
                                          -
                                        @endif
                                    </td>
                                    
                                    
                                    <td class="project-actions text-right">

                                      <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-block btn-sm" onclick="getModalHistorico({{$c->id_creditos}})"><i class="fas fa-history"></i> </button>
                                        </div>
                                        <div class="col-md-12">
                                          @if( Session::get('rol') != '4')
                                            <button type="button" class="btn btn-success btn-block btn-sm" onclick="getIdCredi({{$c->id_creditos}})"><i class="fas fa-money-bill-alt"></i> </button>
                                          @endif    
                                        </div>
                                        
                                        <div class="col-md-12">
                                          @if( Session::get('rol') == '1')
                                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="rmItem({{$c->id_creditos}})"><i class="fas fa-trash"></i> </button>
                                          @endif                                          
                                        </div>                                        
                                      </div>



                                    
                                    </td>
                                  </tr>
                                  @endforeach
                                  
                                
                                </tbody>
                    </table>
                  </div>
                </div>
                </div>
                <!-- /.card-body -->
      
              </div>

      
       
    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Credito en paralelo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Fecha Apertura</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" value="{{ date('d/m/y') }}"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Dia de Visita</label>
                      <select class="form-control" id="slDiaVisita">
                        @foreach ($DiasSemana as $d)
                          <option value="{{$d->id_diassemana}}"> {{strtoupper($d->dia_semana)}}</option>
                        @endforeach
                      </select>                        
                    </div> 
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Promotor</label>
                      <select class="form-control" id="slPromotor">
                        @foreach ($Promo as $p)
                          <option value="{{$p->id}}"> {{strtoupper($p->nombre)}}</option>
                        @endforeach
                      </select>                        
                    </div> 
                  </div>
                  <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                      <label>Monto</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                          </div>
                          <input type="text" id="txtMonto" class="form-control" placeholder="C$ 0.00"  onkeypress='return isNumberKey(event)'  >
                          
                        </div>
                      </div>
                  </div>

                  <div class="col-sm-6 col-md-4">
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
                  <div class="col-sm-6 col-md-4">
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
                  <div class="col-sm-6 col-md-4">
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
                  <div class="col-sm-6 col-md-4">
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
                  <div class="col-sm-6 col-md-4">
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
                  <div class="col-sm-6 col-md-4">
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
                  <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                      <label>Intereses</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                          </div>
                          <input type="text" id="txtIntereses" class="form-control" placeholder="C$ 0.00" disabled>
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                      <label>Intereses por cuota</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                          </div>
                          <input type="text" id="txtInteresesPorCuota" class="form-control" placeholder="C$ 0.00" disabled>
                        </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btn_add_credito">Aplicar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
 
  <div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <div class="user-block">
              <img class="img-circle" src="{{asset('img/user.png')}}" alt="User Image">
              <span class="username"><a href="#">{{ $perfil_cliente->nombre}} {{ $perfil_cliente->apellidos}}</a></span>
              <span class="description"># Cliente : <span>{{ $perfil_cliente->id_clientes}}</span> - # Credito: <span id="lbl_credito"> 0 </span></span></span>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label>Fecha Abono</label>
                <div class="input-group date" id="dtAbono" data-target-input="nearest">
                    <div class="input-group-append" data-target="#dtAbono" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#dtAbono" id="IddtApertura" value="{{ date('d/m/y') }}"/>
                </div>
            </div> 

            <div class="form-group">
              <label>Numero de Abono:</label>
              <div class="input-group date">
                <div class="input-group-append" >
                    <div class="input-group-text"><i class="fa fa-dollar-sign"></i></div>
                </div>
                <select class="form-control" id="lista_pagos">
                  
                </select>   
              </div>
            </div>

            <div class="form-group">
              <label id="id_lbl_cuota">Cuota a pagar</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <input type="text" id="txt_Total_abono" class="form-control" placeholder="C$ 0.00" onkeypress='return isNumberKey(event)'>
              </div>
            </div>

            <div class="form-group">
              <label>Tipo de Abono: <span id="id_mdl_saldo_pendiente" style="display:none">0.00</span></label>
              <div class="input-group date">
                <div class="input-group-append" >
                    <div class="input-group-text"><i class="fa fa-dollar-sign"></i></div>
                </div>
                <select class="form-control" id="slTipoAbono">
                  <option value="0">SIMPLE</option>
                  <option value="2">MULTIPLES</option>
                  <option value="1">CANCELACION</option>
                </select>   
              </div>
            </div>
            <div id="lbl_cancelacion">

            
            @if (Session::get('rol') == '1')
              <div class="form-group">
                <label id="id_lbl_cuota">Descuento C$.:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="text" id="txt_descuento" class="form-control" placeholder="C$ 0.00" onkeypress='return isNumberKey(event)'>
                </div>
              </div>
            @endif

            <div class="row" >
              
              <div class="col-sm-6 col-6">
                <div class="description-block border-right">
                  <h5 class="description-header text-success" id="lbl_cancel_capital">C$ 0.00</h5>
                  <span class="description-text">CAPITAL</span>
                </div>
              </div>
              
              <div class="col-sm-6 col-6">
                <div class="description-block">
                  <h5 class="description-header text-warning" id="lbl_cancel_interes">C$ 0.00</h5>
                  <span class="description-text">INTERESES</span>
                </div>
              </div>
              
            </div>
            </div>
           

          
          
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="btn_save_abono">Aplicar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-historico">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
            <div class="user-block">
              <img class="img-circle" src="{{asset('img/user.png')}}" alt="User Image">
              <span class="username"><a href="#">{{ $perfil_cliente->nombre}} {{ $perfil_cliente->apellidos}} </a> | [HISTORICO] </span>
              <span class="description"># Cliente : <span>{{ $perfil_cliente->id_clientes}}</span> - # Credito: <span id="lbl_mdl_id_credito"> 0 </span></span></span>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">PAGOS REALIZADOS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">HISTORICOS</a>
            </li>

          </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <h5 class="card-title mb-3"></h5>
                <div class="col-12 table-responsive">   
                  <table id="tbl_lista_abonos"  class="table table-striped " style="width:100%"></table>
                </div>  
              </div>
              <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                  <div class="col-12 table-responsive">   
                    <table id="tbl_pagos_realizados"  class="table table-striped " style="width:100%"></table>
                  </div>
              </div>
             
            </div>
        
        </div>
          
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" >Aplicar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->


</div>
@endsection