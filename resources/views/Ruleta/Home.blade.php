@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_ruleta')
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
            <h1 class="m-0">GIROS DE LA RULETA
              <span class="badge badge-warning align-middle ml-2" id="lbl_demo" style="display:none; font-size: .9rem;">DATOS DE EJEMPLO</span>
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item">Ruleta</li>
              <li class="breadcrumb-item active">Dashboard</li>
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
          <div class="col-md-4">
            <label>Zonas</label>
            <div class="form-group">
              <select class="form-control select2" style="width: 100%;" id="IdFilterByZone">
                <option value="-1" selected="selected"> Todas </option>
                @foreach ($Zonas as $z)
                  <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label>Periodo</label>
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control float-right" name="dt_range">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label>Buscar</label>
            <div class="form-group">
              <input type="text" class="form-control" id="tbl_ruleta_giros_buscar" placeholder="Cliente, promotor o código">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="tbl_ruleta_giros" class="table table-bordered table-striped" style="width:100%"></table>
              </div>
            </div>
          </div>
        </div>

      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('layouts.lyt_footer')
</div>
@endsection
