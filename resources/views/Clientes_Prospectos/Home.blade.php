@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_prospectos')
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
              <li class="breadcrumb-item active">Prospectos</li>
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
                    <label>Buscar</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" id="id_txt_buscar">
                    </div>
                  </div>
                 
                  <div class="col-md-5">
                    <label>Zonas</label>
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_zona" name="IdZona">
                          <option value="-1"  selected="selected">Todos</option>
                        @foreach ($Zonas as $z)
                          <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona) }}</option>
                        @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-2">
                    <label>Filtrar</label>
                    <div class="form-group">
                      <div class="input-group date" id="dt-end" data-target-input="nearest">
                          <button type="submit" class="btn btn-success button_export_excel"><i class="fa fa-file-excel"></i></button>
                          
                          <div class="input-group-text"  id="btn-buscar-abonos"><i class="fa fa-filter" ></i></div>        
                          
                          <a href="FormPospecto/0" class="btn btn-primary " id="btn_save_prospecto" > <i class="fas fa-user"></i>  NUEVO</a>
                                          
                      </div>
                      
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