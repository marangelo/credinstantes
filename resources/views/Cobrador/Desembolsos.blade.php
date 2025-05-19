@extends('layouts.lyt_listas')

@section('metodosjs')
@include('Cobrador.js_cobrador_desembolsos')
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
              <li class="breadcrumb-item active">Ingresos</li>
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
                    <div class="col-md-4">
                      <label>TIPO DE CREDITO</label>
                      <div class="input-group">
                      <select class="form-control " style="width: 100%;" id="IdFilterByType" >
                          <option value="RePrestamo"> RePrestamo </option>
                          <option value="Nuevo"> Nuevo </option>
                          <option value="Reactivacion"> Reactivacion </option>
                          
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
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <div class="input-group-text"  id="btn-buscar-abonos"><i class="fa fa-filter" ></i></div>

                            
                        </div>
                      </div>
                    </div>


                  
                    <div class="col-12 col-sm-6 col-md-3">
                      <div class="info-box mb-3 bg-info">

                        <div class="info-box-content">
                          <span class="info-box-text">CLIENTES NUEVOS</span>
                          <span class="info-box-number"><span id="lblClientesNuevos">0</span></span>
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
                          <span class="info-box-text">Reactivaciones ( <span id="lblCountReact">0</span> )</span>
                          <span class="info-box-number"><span id="lblReactivacionesValue"> C$ 0.00 </span></span>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                      <div class="info-box mb-3 bg-danger">
                        <div class="info-box-content">
                          <span class="info-box-text"> SALDOS COLOCADOS</span>
                          <span class="info-box-number"><small>C$ </small><span id="lblSaldosColocados"> 0.00</span></span>
                        </div>
                      </div>
                    </div>

                    
                </div>
                
                <table id="tbl_ingresos" class="table table-bordered table-striped">
                 
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