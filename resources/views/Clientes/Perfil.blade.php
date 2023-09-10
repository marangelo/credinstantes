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
            <h1>Perfil</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Perfil de Usuario</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                        src="{{ asset('img/user.png')}}"
                        alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $perfil_cliente->nombre}} {{ $perfil_cliente->apellidos}}</h3>

                <p class="text-muted text-center">{{ strtoupper($perfil_cliente->getMunicipio->nombre_municipio) }} / {{ strtoupper($perfil_cliente->getMunicipio->getDepartamentos->nombre_departamento) }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Indicador 01</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Indicador 02</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Indicador 03</b> <a class="float-right">13,287</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Cedula</strong>

                <p class="text-muted">
                {{ strtoupper($perfil_cliente->cedula) }}
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Direccion</strong>

                <p class="text-muted">{{ strtoupper($perfil_cliente->direccion_domicilio) }}</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Telefono </strong>

                <p class="text-muted">
                {{ strtoupper($perfil_cliente->telefono) }}
                </p>
                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> --</strong>

                <p class="text-muted">
                  ---------
                </p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#credits" data-toggle="tab">Creditos</a></li>
                  <li class="nav-item"><a class="nav-link" href="#new_credit" data-toggle="tab">Agregar Credito</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="credits">

                  <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Apertura</th>
                      <th>Monto</th>
                      <th>Ultm. Abono</th>
                      <th>Salud</th>
                      <th>Estado</th>
                      <th></th>
                      <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                      @foreach ($perfil_cliente->getCreditos as $c)
                      <tr>
                        <td>{{$c->id_creditos}}</td>
                        <td>{{$c->fecha_apertura}}</td>
                        <td>C$ {{$c->monto_credito}}</td>
                        <td>{{$c->fecha_ultimo_abono}}</td>
                        <td>{{$c->salud_credito}}</td>
                        <td>{{$c->estado_credito}}</td>

                        <td><button type="button" class="btn btn-block bg-gradient-primary btn-sm"><a href="{{ route('voucher')}}" class="text-white" target="_blank">Imprimir</a></button></td>
                        <td><button type="button" onclick="getIdCredi({{$c->id_creditos}})" class="btn btn-block bg-gradient-success btn-sm" data-toggle="modal" data-target="#modal-lg">Abonar</button></td>
                      </tr>
                      @endforeach
                      
                    
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
                    
                  </div>
                  <div class="tab-pane" id="new_credit">
                    <form class="form-horizontal">

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Dia de Pago</label>
                        <select class="form-control" id="selDiaSemana">
                          @foreach ($DiasSemana as $d)
                            <option value="{{$d->id_diassemana}}"> {{strtoupper($d->dia_semana)}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label>Monto</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                              </div>
                              <input type="text" id="txtMonto" class="form-control" placeholder="C$ 0.00" >
                            </div>
                          </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label>Plazo</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                              </div>
                              <input type="text" id="txtPlazo" class="form-control" placeholder="Numero de Meses">
                            </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label>Interes</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                              </div>
                              <input type="text" id="txtInteres" class="form-control" placeholder="0.00 %">
                            </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label>N° Cuotas</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                              </div>
                              <input type="text" id="txtCuotas" class="form-control" placeholder="Numero de Cuotas">
                            </div>
                        </div>
                      </div>
                    </div>


                    <div class="row">
                      <div class="col-sm-6 col-md-3">
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
                      <div class="col-sm-6 col-md-3">
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
                      <div class="col-sm-6 col-md-3">
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
                      <div class="col-sm-6 col-md-3">
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
                    </div>
                        
                      <div class="form-group row">
                        <div class="col-sm-10">
                          <button type="submit" class="btn btn-danger">Aplicar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label>Capital</label>
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="text" id="txt_Capital" class="form-control" placeholder="C$ 0.00">
                    </div>
                </div>
              </div>
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label>Interes</label>
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="text" id="txt_Interes" class="form-control" placeholder="C$ 0.00">
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-success">Aplicar</button>
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