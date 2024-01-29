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
      <div class="card-header">
          <h3 class="card-title" id="IdCardTitle"></h3>
          <div class="card-tools">
            <div class="input-group input-group-sm">
              <select class="custom-select" style="width: auto;" id="IdFilterByZone" >
                <option value="-1" selected="selected"> Todas </option>
                @foreach ($Zonas as $z)
                  <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                @endforeach
                
              </select>
              <div class="input-group-append">
                <div class="btn btn-primary" id="IdbtnFilter">
                  <i class="fa fa-filter"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
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
                  <span class=""> PRESTAMOS</span>
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
        

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Cliente para promotoria</h5>
              </div>
              <!-- /.card-header -->
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
                
                <div class="position-relative mb-4">
                  <table id="tbl_cliente_promotor" class="table table-bordered table-hover"> </table>
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
                      <span class="description-text">MORA VENCIDAD</span>
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