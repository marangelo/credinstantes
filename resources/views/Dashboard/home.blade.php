@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_dashboard')
@endsection
@section('content')
<div class="wrapper">


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">INDICADORES</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">INDICADORES</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if( Session::get('rol') == '1' || Session::get('rol') == '2' || Session::get('rol') == '3' )
        <div class="row">        
          <div class="col-md-6">
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
          <div class="col-md-6">
            <label>CULMINA</label>
            <div class="form-group">
              <div class="input-group date" id="dt-Ini" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#dt-Ini" id="dtIni" value="{{ date('d/m/Y') }}"/>
                  <div class="input-group-append" data-target="#dt-Ini" data-toggle="datetimepicker">
                      <div class="input-group-text" ><i class="fa fa-calendar"></i></div>
                  </div>
                  <div class="input-group-text btn btn-primary"  id="IdbtnFilter" ><i class="fa fa-filter" ></i></div>
                  <table id="tbl_metrias_home" class="table table-bordered table-striped"></table>
              </div>
            </div>
          </div>          
        </div>
        @endif

        @switch(Session::get('rol'))
            @case(1)
            <div class="row">
              <div class="col-12 col-sm-6 col-md-2">
                <div class="info-box " style="background-color: #008000;">
                  
                  <div class="info-box-content ">
                    <span class="info-box-text">INGRESO NETO</span>
                    <span class="info-box-number"><small>C$ </small><span id="lblIngreso" ></span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            
              <div class="col-12 col-sm-6 col-md-2">
                <div class="info-box mb-3" style="background-color: #00BFFF;">

                  <div class="info-box-content">
                    <span class="info-box-text">CAPITAL</span>
                    <span class="info-box-number"><small>C$ </small><span id="lblCapital"></span>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>    

              <div class="col-12 col-sm-6 col-md-2">
                <div class="info-box mb-3 bg-warning" >

                  <div class="info-box-content">
                    <span class="info-box-text">UTILIDAD BRUTA</span>
                    <span class="info-box-number"><small>C$ </small><span id="lblInteres"></span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-12 col-sm-6 col-md-2">
                <div class="info-box mb-3" style="background-color: #008000;" >

                  <div class="info-box-content">
                    <span class="info-box-text">UTILIDAD NETA</span>
                    <span class="info-box-number"><small>C$ </small><span id="lbl_ultil_neta">C$ 0.00</span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>              
              
              <div class="col-12 col-sm-6 col-md-2">
                <div class="info-box mb-3 bg-info">

                  <div class="info-box-content">
                    <span class="info-box-text">CREDITOS ACTIVOS</span>
                    <span class="info-box-number"><span id="lblClientes"></span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-12 col-sm-12 col-md-2">
                <div class="info-box mb-3" style="background-color: #FFA0AB;">

                  <div class="info-box-content">
                    <span class="info-box-text">SALDO DE CARTERA</span>
                    <span class="info-box-number"><small>C$ </small><span id="id_saldos_cartera"> 0.00 </span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>

              <div class="row">
                <div class="col-6 col-sm-6 col-md-6">
                  <div class="info-box " style="background-color: #FF8000;">              
                    <div class="info-box-content ">
                      <span class=""> MORA ATRASADA </span>
                      <span class="info-box-number"><small>C$ </small><span id="lblMoraAtrasada" > 0.00</span>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-6 col-sm-6 col-md-6">
                  <div class="info-box mb-3 bg-danger">

                    <div class="info-box-content">
                      <span class="info-box-text"> MORA VENCIDA </span>
                      <span class="info-box-number"><small>C$ </small><span id="lblMoraVencida"> 0.00</span>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </div>
                @break
        
            @case(3)
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box " style="background-color: #008000;">
                  
                  <div class="info-box-content ">
                    <span class="info-box-text">INGRESO NETO</span>
                    <span class="info-box-number"><small>C$ </small><span id="lblIngreso" ></span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3 bg-info">

                  <div class="info-box-content">
                    <span class="info-box-text">CREDITOS ACTIVOS</span>
                    <span class="info-box-number"><span id="lblClientes"></span></span>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3" style="background-color: #FFA0AB;">
                  <div class="info-box-content">
                    <span class="info-box-text">SALDO DE CARTERA</span>
                    <span class="info-box-number"><small>C$ </small><span id="id_saldos_cartera"> 0.00 </span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box " style="background-color: #FF8000;">              
                  <div class="info-box-content ">
                    <span class=""> MORA ATRASADA </span>
                    <span class="info-box-number"><small>C$ </small><span id="lblMoraAtrasada" > 0.00</span>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3 bg-danger">

                  <div class="info-box-content">
                    <span class="info-box-text"> MORA VENCIDA </span>
                    <span class="info-box-number"><small>C$ </small><span id="lblMoraVencida"> 0.00</span>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </div>
            @break
        
            @case(2)
            <div class="row">
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3 bg-info">

                  <div class="info-box-content">
                    <span class="info-box-text">CREDITOS ACTIVOS</span>
                    <span class="info-box-number"><span id="lblClientes"></span></span>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3" style="background-color: #FFA0AB;">
                  <div class="info-box-content">
                    <span class="info-box-text">SALDO DE CARTERA</span>
                    <span class="info-box-number"><small>C$ </small><span id="id_saldos_cartera"> 0.00 </span></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box " style="background-color: #FF8000;">              
                  <div class="info-box-content ">
                    <span class=""> MORA ATRASADA </span>
                    <span class="info-box-number"><small>C$ </small><span id="lblMoraAtrasada" > 0.00</span>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3 bg-danger">

                  <div class="info-box-content">
                    <span class="info-box-text"> MORA VENCIDA </span>
                    <span class="info-box-number"><small>C$ </small><span id="lblMoraVencida"> 0.00</span>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              </div>
        @endswitch

        
  

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">INGRESO POR MES</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              
                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="200"></canvas>
                </div>

                
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row" style="display:none">
                  <div class="col-sm-6 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header text-warning">C$ 35,210.43</h5>
                      <span class="description-text">MORA ATRASADA</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header text-danger">C$10,390.90</h5>
                      <span class="description-text">MORA VENCIDA</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6" style="display:none">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">C$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6" style="display:none">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

   
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