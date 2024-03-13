@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_desembolsado')
@endsection
@section('content')
<div class="wrapper">
  @include('layouts.lyt_promotor')
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{$Titulo}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">{{$Titulo}}</li>
            </ol>
          </div>
        </div>
      </div>

    
  <section class="content">
    <div class="container-fluid">
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
                </div>
              </div>
            </div>
            </div>
            <div class="tab-content p-0">
              <div class="position-relative mb-4">
                <table id="tbl_cliente_promotor" class="table table-bordered table-hover"></table>
              </div>
            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
              <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<aside class="control-sidebar control-sidebar-dark"></aside>

@include('layouts.lyt_footer')
</div>
@endsection