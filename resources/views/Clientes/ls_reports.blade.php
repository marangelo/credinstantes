@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_reports')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Reportes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Reportes</li>
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
              <div class="card-header">
                <h3 class="card-title">Reporte de XXXXXXX</h3>
                <div class="card-tools">                  
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation">
                  </div>
                  <!-- /.input group -->
                </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_clientes" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>- </td> 
                  </tr>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                  </tr>
                  </tfoot>
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