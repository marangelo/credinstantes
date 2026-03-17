@extends('layouts.lyt_listas')

@section('metodosjs')
@include('ArqueosPromotor.js_ArqueosPromotor')
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
                  <div class="col-md-7">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar" aria-label="Buscar">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_zona" name="IdZona">
                          <option value="-1"  selected="selected">Promotores...</option>
                          @foreach ($Promotores as $p)
                            <option value="{{ $p->id }}"> {{ strtoupper($p->nombre) }} </option>
                          @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                          <input type="text" class="form-control" name="dt_range" />
                            <!-- <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/> -->
                            <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" id="btn-buscar-abonos">
                                    <i class="fa fa-filter"></i>
                                </button>
                                <button type="button" class="btn btn-warning" id="btn-add-arqueo">
                                    <i class="fas fa-plus-circle "></i>
                                </button>
                            </div>
                          
                        </div>
                    </div>
                  </div>
                </div>
                
                <table id="tbl_ingresos" class="table table-bordered table-striped"></table>
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