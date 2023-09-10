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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Clientes</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

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
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-xl">
                    Nuevo
                </button>
                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_clientes" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Municipio</th>
                    <th>Departamento</th>
                    <th>Direccion</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($Clientes as $c)  
                  <tr>
                    <td><a href="Perfil/{{ strtoupper($c->id_clientes) }}" class=""><strong>#{{ strtoupper($c->id_clientes) }} </strong> : {{ strtoupper($c->nombre) }} : {{ strtoupper($c->apellidos) }}</a></td>
                    <td>{{ strtoupper($c->telefono) }} </td>
                    <td> {{ strtoupper($c->getMunicipio->nombre_municipio) }} </td>
                    <td> {{ strtoupper($c->getMunicipio->getDepartamentos->nombre_departamento) }} </td>
                    <td>{{ strtoupper($c->direccion_domicilio) }}  </td>   
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Municipio</th>
                    <th>Direccion</th>
                    <th>Monto</th>
                  </tr>
                  </tfoot>
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
                <form>
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
                    <div class="col-sm-12">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre" class="form-control" placeholder="Enter ...">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="txtApellido" class="form-control" placeholder="Enter ...">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Numero de Telefono:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                      </div>
                      <input type="text" class="form-control" id="txtTelefono" data-inputmask="'mask': ['999-9999-9999', '+099 999 9999']" data-mask>
                    </div>
                    <!-- /.input group -->
                  </div>
                  <div class="form-group">
                    <label>Numero de Cedula:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                      </div>
                      <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999', '+099 999999 999999']" data-mask>
                    </div>
                    <!-- /.input group -->
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..."></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Municipio</label>
                      <select class="form-control" id="selMunicipio">
                        @foreach ($Municipios as $m)
                          <option value="{{$m->id_municipio}}"> {{strtoupper($m->nombre_municipio)}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
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

                  


          


                </form>
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
    </section>
    <!-- /.content -->
  </div>
@endsection