@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_dispensa')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="lbl_titulo_reporte"></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Dispensa</li>
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
              <div class="card-header" style="display:none">
              
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              @csrf
                <div class="row">
                  <div class="col-md-5">
                    <label>Buscar</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" id="id_txt_buscar">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label>Zonas</label>
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_zona" name="IdZona">
                          <option value="-1"  selected="selected">Todos</option>
                        @foreach ($Zonas as $z)
                          <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona) }}</option>
                        @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-2">
                    <label>INICIO</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-ini" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-ini" id="dtIni" name="nmIni" value="{{ date('01/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-ini" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                          
                          <button type="submit" class="btn btn-success button_export_excel"><i class="fa fa-file-excel"></i></button>
                          
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <label>CULMINA</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-end" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('t/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                          <div class="input-group-text"  id="btn-buscar-abonos"><i class="fa fa-filter" ></i></div>

                          
                      </div>
                    </div>
                  </div>
                </div>
                
                <table id="tbl_prox_vencer" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NOMBRE</th>
                            <th>ZONA</th>
                            <th>VENCIMIENTO</th>
                            <th class="text-center">SALDO CREDITO</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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