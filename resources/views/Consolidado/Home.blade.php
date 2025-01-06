@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_consolidado')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="lbl_titulo_reporte">{{$Titulo}} {{$year}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">{{$Titulo}} </li>
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
            <div class="card">
              
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-8">
                    <h5 class="card-title">CONSOLIDADO DE INDICADORES {{$year}}</h5>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group">                            
                        <select class="form-control select2" id="select_year" name="select_year">
                          @for($year = date('Y'); $year >= 2024; $year--)
                            <option value="{{$year}}">{{$year}}</option>
                          @endfor
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text" id="btn_filter"><i class="fa fa-filter"></i></div>
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" id="btn-consolidado-add">
                              <i class="fas fa-plus-circle "></i>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-export-consolidado">
                              <i class="fas fa-file-excel "></i>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-update-consolidado">
                              <i class="fas fa-sync-alt "></i>
                            </button>
                        </div>
                      
                    </div>
                  </div>

                </div>
            
                <div class="table-responsive scrollbar">
                  <table class="table table-striped border-bottom" id="tbl_consolidado">
                    <thead class="light">
                      <tr class="bg-primary text-white dark__bg-1000">
                        <th></th>
                        <th class="border-0">COMPARATIVO DEL MES</th>
                        @foreach ($Consolidado['header_date'] as $k) 
                          <th class="border-0 text-center">{{$k}}</th>
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($Consolidado['header_date_rows'] as $r)
                        <tr>
                          <td>1</td>
                          <td>{{$r['CONCEPTO']}}</td>
                          @foreach ($Consolidado['header_date'] as $k) 
                            <td class="align-middle text-right">{{ isset($r[$k]) ? $r[$k] : ' - ' }}</td>                                
                          @endforeach
                        </tr>
                      @endforeach
                    </tbody>
                   
                    </tfoot>  
                  </table>
                </div>
              </div>
              
              
            
            </div>
            <!-- /.card -->
          </div>
               
            </div>
                            
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