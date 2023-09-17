@extends('layouts.lyt_listas  ')
@section('metodosjs')
@include('jsViews.js_perfil')
@endsection
@section('content')
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{ asset('img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>



  <!-- Main Sidebar Container -->
  @include('layouts.lyt_aside')
 

  <!-- Content Wrapper. Contains page content -->

  

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
              <li class="breadcrumb-item active">Perfil de Usuario</li>
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
                  <span class="username"><a href="#">{{ strtoupper($perfil_cliente->getMunicipio->nombre_municipio) }} / {{ strtoupper($perfil_cliente->getMunicipio->getDepartamentos->nombre_departamento) }}</a></span>
                  <span class="description text-white"> {{ strtoupper($perfil_cliente->direccion_domicilio) }}</span>
                  <span class="description text-white ">Tel. {{ strtoupper($perfil_cliente->telefono) }} - Cedula. {{ strtoupper($perfil_cliente->cedula) }} </span>
                </div>
                <!-- /.user-block -->
                <div class="card-tools">                  
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-xl">
                    Nuevo
                  </button>
                </div>
      
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row">
                  <div class="col-12 table-responsive">             
                    <table class="table table-striped">
                                <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Apertura</th>
                                  <th>Plazo</th>
                                  <th>Monto C$</th>
                                  <th>Total C$</th>
                                  <th>Saldo C$</th>
                                  <th>Cuotas</th>
                                  <th>Ultm. Abono</th>
                                  <th>Saldo Pendiente</th>
                                  <th>Estado</th>
                                  <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                  @foreach ($perfil_cliente->getCreditos as $c)
                                  <tr>
                                    <td>{{$c->id_creditos}}</td>
                                    <td>{{ Date::parse($c->fecha_apertura)->format('D, M d, Y') }}</td>
                                    <td>{{number_format($c->plazo,0)}}</td>
                                    <td>{{number_format($c->monto_credito,2)}}  <span class="text-success"><i class="fas fa-arrow-up text-sm"></i> {{number_format($c->taza_interes,0)}} <small>%</small><span> </td>
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
                                    <td>
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
                                    
                                    <td class="project-actions text-right">
                                        <a class="btn btn-primary btn-sm" href="#"  onclick="getModalHistorico({{$c->id_creditos}})">
                                            <i class="fas fa-history">
                                            </i>                                            
                                        </a>
                                        <a class="btn btn-success btn-sm" href="#"  onclick="getIdCredi({{$c}})"  data-toggle="modal" data-target="#modal-lg">
                                            
                                        <i class="far fa-money-bill-alt"></i>
                                            </i>                                            
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="#" onclick="rmItem({{$c->id_creditos}})">
                                            <i class="fas fa-trash">
                                            </i>                                            
                                        </a>
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
              <h4 class="modal-title">Nuevo Credito</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="row">
                  <div class="col-sm-12">
                  <div class="form-group">
                      <label>Fecha Apertura</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
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
                  <input type="text" class="form-control datetimepicker-input" data-target="#dtAbono" id="IddtApertura"/>
                  <div class="input-group-append" data-target="#dtAbono" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
              </div>
          </div> 
          <div class="col-sm-12 col-md-12">
            <div class="form-group">
              <label>Cuota a pagar</label>
              <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                  </div>
                  <input type="text" id="txt_Total_abono" class="form-control" placeholder="C$ 0.00" onkeypress='return isNumberKey(event)'>
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
    <div class="modal-dialog modal-lg">
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
            <div class="row">
                  <div class="col-12 table-responsive">   
          <table id="tbl_lista_abonos"  class="table table-striped" style="width:100%"></table>
         
          
          </div>    </div>    </div>
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

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{date('Y')}} <a href="http://pullpos.com/">pullpos.com</a>.</strong> All rights reserved.
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
</div>
@endsection