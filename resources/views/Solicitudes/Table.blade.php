@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_solicitudes')
@endsection

@section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>SOLICITUDES  {{$lblBtn}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Solicitudes</li>
              <li class="breadcrumb-item active">{{$lblBtn}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
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
                  <div class="col-md-9">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" id="id_txt_buscar" placeholder="Buscar cliente">
                    </div>
                  </div>
                  
                  <div class="col-md-3">
                    <div class="btn-group w-100">
                          
                          
                          <button type="button" class="btn btn-success button_export_excel" id="btn-export-gasto">
                              <i class="fas fa-file-excel "></i> EXCEL
                          </button>
                          <a href="Formulario/{{$lblBtn}} " class="btn btn-warning">
                              <i class="fas fa-user "></i> {{$lblBtn}} 
                          </a>
                      </div>
                  </div>
                </div>
                
                <table id="tbl_ingresos" class="table table-bordered table-striped">
                 
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
  </div>
@endsection