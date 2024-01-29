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
                  <span class=""> RE PRESTAMOS</span>
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
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-xl">
                                    <i class="fa fa-plus"></i>
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
                  <div class="row">
                    
                    <div class="col-sm-2">
                      <div class="form-group">
                          <label>Fecha Inicio</label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" value="{{ date('d/m/y') }}"/>
                              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>                      
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <label>Dia de Visita</label>
                        <select class="form-control" id="slDiaVisita">
                          @foreach ($DiasSemana as $d)
                            <option value="{{$d->id_diassemana}}"> {{strtoupper($d->dia_semana)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ...">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ...">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Telefono:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtTelefono" data-inputmask="'mask': ['+505-9999-9999']" data-mask>
                        </div>
                      </div>

                    </div>

                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Cedula:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-address-card"></i></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask>
                        </div>
                      </div>
                      
                    </div>

                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Departamento</label>
                        <select class="form-control" id="selMunicipio">
                          @foreach ($Municipios as $m)
                            <option value="{{$m->id_municipio}}"> {{strtoupper($m->nombre_municipio)}}</option>
                          @endforeach
                        </select>
                      </div>
                        
                    </div>

                    <div class="col-sm-3">

                      <div class="form-group">
                        <label>Zonas</label>
                        <select class="form-control" id="selZona">
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                    </div>
                    
                  </div>
                  
                  

                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..."></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Monto</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtMonto" class="form-control" placeholder="C$ 0.00"  onkeypress='return isNumberKey(event)'>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Plazo</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="txtPlazo" class="form-control" placeholder="Numero de Meses" onkeypress='return isNumberKey(event)'>
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Interes</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                            </div>
                            <input type="text" id="txtInteres" class="form-control" placeholder="0.00 %" onkeypress='return isNumberKey(event)'>
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>N° Cuotas</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="txtCuotas" class="form-control" placeholder="Numero de Cuotas" onkeypress='return isNumberKey(event)'>
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Intereses </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtIntereses" class="form-control" placeholder="C$ 0.00" disabled>
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Intereses por cuota </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtInteresesPorCuota" class="form-control" placeholder="C$ 0.00" disabled>
                          </div>
                      </div>
                    </div>
                  </div>
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
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('layouts.lyt_footer')
</div>
@endsection