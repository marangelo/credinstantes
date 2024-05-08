@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_promotor')
@endsection
@section('content')
<div class="wrapper">

  <!-- Main Sidebar Container -->
  @include('layouts.lyt_promotor')
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">PROMOTOR</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">PROMOTOR</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">
            <label>Zonas</label>
            <div class="form-group">
              <select class="form-control select2" style="width: 100%;" id="IdFilterByZone" name="IdZona">
                <option value="-1" selected="selected"> Todas </option>
                  @foreach ($Zonas as $z)
                    <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                  @endforeach
              
              </select>
              
            </div>
          </div>

          <div class="col-md-4">
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

          <div class="col-md-4">
            <label>CULMINA</label>
            <div class="form-group">
              <div class="input-group date" id="dt-end" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/>
                  <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                      <div class="input-group-text" id="IdbtnFilter"><i class="fa fa-calendar"></i></div>
                  </div>
                  <div class="input-group-text btn btn-primary"  id="btn-buscar-abonos"><i class="fa fa-filter" ></i></div>

                  
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3 bg-info">

              <div class="info-box-content">
                <span class="info-box-text">CLIENTES NUEVOS</span>
                <span class="info-box-number"><span id="lblClientesNuevos"></span></span>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box " style="background-color: #FF8000;">              
              <div class="info-box-content ">
                <span class="">REPRESTAMOS</span>
                <span class="info-box-number"><span id="lblRePrestamo" > 0.00</span></span>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3 bg-danger">
              <div class="info-box-content">
                <span class="info-box-text"> SALDOS COLOCADOS</span>
                <span class="info-box-number"><small>C$ </small><span id="lblSaldosColocados"> 0.00</span></span>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
            
              <div class="card-body">
                
              <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-lg" id="tbl_cliente_promotor_buscar" placeholder="Escriba cliente a buscar">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="position-relative mb-4">
                  <table id="tbl_cliente_promotor" class="table table-bordered table-hover"></table>
                </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
        
        

        
   
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('layouts.lyt_footer')
</div>
@endsection