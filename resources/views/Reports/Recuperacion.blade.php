@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_recuperacion')
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
              <li class="breadcrumb-item active">{{$Titulo}}</li>
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
              <form action="{{ route('exportAbonos') }}" method="POST">
              @csrf
                <div class="row">
                  
                  <div class="col-md-6">
                    <label>INICIO</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-ini" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-ini" id="dtIni" name="nmIni" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-ini" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                          
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label>CULMINA</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-end" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                          <div class="input-group-text"  id="btn-buscar-abonos"><i class="fa fa-filter" ></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                
                </form>
                
              </div>
              <div class="card-footer">
                <div class="row">
                <div class="col-lg-12 col-12">
            <!-- small box -->
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3> C$ <span id="IdCalcRecuperacion">0.00</span> </h3>

                        <p>Recuperado</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                        
                    </div>
                    <a href="#" class="small-box-footer">- </i></a>
                    </div>
                </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
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