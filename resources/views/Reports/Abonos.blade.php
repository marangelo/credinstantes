@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_abonos')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Abonos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Morosidad</li>
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
              <div class="row">
                  <div class="col-md-4">
                    <label>Clientes</label>
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_cliente">
                          <option value="-1"  selected="selected">Todos</option>
                        @foreach ($Clientes as $c)
                          <option value="{{$c->id_clientes}}"> {{strtoupper($c->nombre) }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>INICIO</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-ini" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-ini" id="dtIni" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-ini" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>CULMINA</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-end" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              <button type="button" class="btn btn-block bg-gradient-success btn-sm" id="btn-buscar-abonos">Buscar</button>
                          </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_clientes" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>NOMBRE</th>
                    <th>APELLIDO</th>
                    <th>FECHA CUOTA</th>
                    <th>CUOTA COBRADA</th>
                    <th>PAGO A CAPITAL</th>
                    <th>PAGO A INTERES</th>
                  </tr>
                  </thead>
                  <tbody>
                  
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
    </section>
    <!-- /.content -->
  </div>
@endsection