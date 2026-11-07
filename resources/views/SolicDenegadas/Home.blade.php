@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_solic_denegadas')
@endsection

@section('content')
  <div class="content-wrapper">
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
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header" style="display:none"></div>
              <div class="card-body">
              @csrf
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar" aria-label="Buscar">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group date" id="dt-ini" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-ini" id="dtIni" name="nmIni" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-ini" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/>
                            <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control select2" id="id_cbo_usuario">
                            <option value="">- Usuario -</option>
                            @foreach($Usuarios as $u)
                            <option value="{{$u->id}}">{{strtoupper($u->nombre)}} ({{strtoupper($u->RolName->descripcion ?? '')}})</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-primary" id="btn-buscar-solic">
                                <i class="fa fa-filter"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-success" id="btn-export-solic">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>
                  </div>
                </div>

                <table id="tbl_solic_denegadas" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>CLIENTE</th>
                    <th>FECHA SOLICITUD</th>
                    <th>MONTO C$.</th>
                    <th>CREADO POR.</th>
                    <th>ACCIÓN</th>
                  </tr>
                  </thead>
                  <tfoot>
                  <tr>
                    <th>TOTAL</th>
                    <th colspan="3"></th>
                    <th></th>
                    <th></th>
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
