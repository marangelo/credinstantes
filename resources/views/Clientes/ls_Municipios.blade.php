@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_municipio')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Municipios</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Municipios</li>
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
                <h3 class="card-title">Listado de Municipios</h3>
                <div class="card-tools">                  
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-xl">
                    Nuevo
                </button>
                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_clientes" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nombre</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($Municipios as $m)  
                  <tr>
                    <td>{{ strtoupper($m->nombre_municipio) }} </td>
                  </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nombre</th>
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
      <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Municipio</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <div class="card card-warning">
            
              <!-- /.card-header -->
              <div class="card-body">
                <form>
            
                  <div class="row">
                    <div class="col-sm-12">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre_Municipio" class="form-control" placeholder="Nombre ...">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Municipio</label>
                        <select class="form-control" id="selDepartamento">
                          @foreach ($Departamentos as $d)
                            <option value="{{$m->id_departamento}}"> {{strtoupper($d->nombre_departamento)}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                </form>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btn_save_credito">Aplicar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection