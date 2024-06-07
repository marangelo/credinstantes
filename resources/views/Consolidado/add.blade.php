@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_consolidado_agregar')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="lbl_num_row">Consolidados Manuales</h1>
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
                <div class="col-md-4">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_indicadores" name="IdIndicadores">
                          <option value="-1"  selected="selected">Todos</option>
                          @foreach($Indicadores as $Indicador)
                          <option value="{{$Indicador->key}}">{{$Indicador->descripcion}}</option>
                          @endforeach
                      </select>
                      
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" id="id_txt_valor" placeholder="0.00" >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/>
                            <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" id="btn_save_abono">
                                    <i class="fas fa-plus-circle "></i>
                                </button>
                            </div>
                          
                        </div>
                    </div>
                  </div>

                </div>
                
                <table id="tbl_gastos_operaciones" class="table table-bordered table-striped">
                  <tfoot>
                    <tr>
                      <th>TOTAL</th>
                      <th colspan="2"> - </th>
                      <th colspan="2"></th>
                      
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