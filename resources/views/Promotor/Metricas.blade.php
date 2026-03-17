@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_metricas_promotor')
@endsection
@section('content')
<div class="wrapper">

  <!-- Main Sidebar Container -->
  @include('layouts.lyt_aside')
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" id="lbl_titulo">PROMOTOR</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Promotor</li>
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

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3 bg-info">
                <div class="info-box-content">
                  <span class="info-box-text">CLIENTES NUEVOS</span>
                  <span class="info-box-number"><span id="lblClientesNuevos"></span></span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box " style="background-color: #FF8000;">              
                <div class="info-box-content ">
                  <span class="">REPRESTAMOS</span>
                  <span class="info-box-number"><span id="lblRePrestamo" > 0.00</span></span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3 bg-success">
                <div class="info-box-content">
                  <span class="info-box-text">Reactivaciones </span>
                  <span class="info-box-number"> <span id="lblCountReact"> 0.00 </span></span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3 bg-danger">
                <div class="info-box-content">
                  <span class="info-box-text"> SALDOS COLOCADOS</span>
                  <span class="info-box-number"><small>C$. </small><span id="lblSaldosColocados"> 0.00</span></span>
                </div>
              </div>
            </div>

            

          </div>
        <div class="card">
            
              <div class="card-body">

                <div class="row">
                    <div class="col-md-7">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control form-control-lg" id="tbl_cliente_promotor_buscar" placeholder="Escriba cliente a buscar">
                        <button type="submit" class="btn btn-lg btn-default button_export_excel">
                            <i class="fa fa-file-excel"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <select class="form-control form-control-lg select2" style="width: 100%;" id="IdFilterByZone" name="IdZona">
                            <option value="-1"  selected="selected">Todas...</option>
                            @foreach ($Promo as $p)
                            <option value="{{$p->id}}"> {{strtoupper($p->nombre)}}</option>
                            @endforeach
                        </select>
                        
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <input type="text" class="form-control form-control-lg" name="dt_range" />
                              <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                              <div class="card-tools"  id="IdbtnFilter">
                                  <div class="btn btn-primary form-control-lg" style="display: flex; align-items: center;">
                                      <i class="fa fa-filter" style="margin: auto;"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <table id="tbl_cliente_promotor" class="table table-bordered table-hover"></table>
                    </div>
                  </div>
                </div>
              </div>
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