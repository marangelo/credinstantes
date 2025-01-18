@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_historial_pagos')
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
              <li class="breadcrumb-item active">Historial</li>
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
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" id="id_txt_buscar" placeholder="Buscar por nombre o id" >
                    </div>
                  </div>
                 
                  <div class="col-md-5">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_zona" name="IdZona">
                          <option value="-1"  selected="selected">Zonas</option>
                        @foreach ($Zonas as $z)
                          <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona) }}</option>
                        @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-primary" id="btn-buscar-abonos">
                            <i class="fa fa-filter"></i> FILTRAR
                        </button>
                        
                        <button type="button" class="btn btn-success button_export_excel" id="btn-export-gasto">
                            <i class="fas fa-file-excel "></i> EXCEL
                        </button>
                    </div>                  
                  </div>
                </div>
                
                <table id="tbl_ingresos" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>NOMBRE</th>
                        <th>HISTORIAL</th>
                    </tr>
                  </thead>
                  <tbody>
                  
                  </tbody>
                </table>
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

    <div class="modal fade" id="modal-historico">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
            <div class="user-block">
              <img class="img-circle" src="{{asset('img/user.png')}}" alt="User Image">
              <span class="username"><a href="#" id="lbl_nombre_cliente"> NOMBRE  CLIENTE </a> | [HISTORIAL DE CREDITOS] </span>
              <span class="description"># Cliente : <span id="lbl_id_cliente">ID_CLIENTE </span> </span></span>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="col-12 table-responsive">   
                <table id="tbl_creditos"  class="table table-striped " style="width:100%"></table>
            </div>
        </div>
          
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
    <!-- /.content -->
  </div>
@endsection