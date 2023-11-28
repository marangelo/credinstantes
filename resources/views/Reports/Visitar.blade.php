@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_visita')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Visitar</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Visitar</li>
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
                <h3 class="card-title" id="lbl_periodo">Clientes a Visitar <span id="lbl_visitar" ></span>  </h3>
                <div class="card-tools">

                
                <form action="{{ route('exportVisita') }}" method="POST">@csrf
                <div class="row">
                  <div class="col-md-3" style="display:none">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="IdDiaW" name="IdDiaW">
                          <option value="-1"  selected="selected">Todos</option>
                        @foreach ($DiasW as $dw)
                          <option value="{{$dw->id_diassemana}}"> {{strtoupper($dw->dia_semana) }}</option>
                        @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="IdZona" name="name_Zona">
                          <option value="-1"  selected="selected">Todos</option>
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona) }}</option>
                          @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-6 mt-1">                      
                      <div class="form-group">                      
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" name="Fecha_" value="{{ date('d/m/Y') }}"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <div class="input-group-text" id="btn-buscar"><i class="fa fa-filter"></i></div>
                            <!-- <button type="submit" class="btn btn-success"><i class="fa fa-file-excel"></i></button> -->
                        </div>
                        
                      </div>
                    
                  </div>
                </div>
                </form>

                
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_clientes_visita" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                  </tr>
                </thead>
                <tfoot >
                  <tr>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
                </table>
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