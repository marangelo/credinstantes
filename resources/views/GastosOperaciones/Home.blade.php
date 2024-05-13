@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_gastos_operaciones')
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
              @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar" aria-label="Buscar">
                    </div>
                  </div>
                 
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group date" id="dt-ini" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-ini" id="dtIni" name="nmIni" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-ini" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                          
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/>
                            <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" id="btn-buscar-abonos">
                                    <i class="fa fa-filter"></i>
                                </button>
                                <button type="button" class="btn btn-warning" id="btn-openModal-gasto">
                                    <i class="fas fa-plus-circle "></i>
                                </button>
                                <button type="button" class="btn btn-success" id="btn-export-gasto">
                                    <i class="fas fa-file-excel "></i>
                                </button>
                            </div>
                          
                        </div>
                    </div>
                  </div>
                </div>
                
                <table id="tbl_gastos_operaciones" class="table table-bordered table-striped"></table>
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

    <div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <div class="user-block">
              <img class="img-circle" src="{{asset('img/user.png')}}" alt="User Image">
              <span class="username"><a href="#"> Nuevo Gasto </a></span>
              <span class="description"># Ingrese informacion para gastos de operacion</span>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label>Fecha Gasto</label>
                <div class="input-group date" id="dtAbono" data-target-input="nearest">
                    <div class="input-group-append" data-target="#dtAbono" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#dtAbono" id="IdFechaGasto" value="{{ date('d/m/y') }}"/>
                </div>
            </div> 

           

            <div class="form-group">
              <label id="id_lbl_cuota">Monto C$. </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="C$ 0.00" id="txt_Total_monto" onkeypress='return isNumberKey(event)'>
              </div>
            </div>

            <div class="form-group">
              <label>Concepto:</label>
              <div class="input-group date">
                <textarea class="form-control" rows="3" placeholder="Escribir ..." id="txt_concepto"></textarea> 
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="btn_save_abono">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection