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
            <h1 id="lbl_titulo_reporte">{{$Titulo}}</h1>
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
            <div class="card">
              <div class="card-header">
              <h5 class="card-title">CONSOLIDADO DE INDICADORES</h5>
                <div class="card-tools">
                  <div class="btn-group">
                    <div class="input-group date" id="dt-end" data-target-input="nearest">                            
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" id="btn-consolidado-add">
                                <i class="fas fa-plus-circle "></i>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-export-gasto">
                                <i class="fas fa-file-excel "></i>
                            </button>
                        </div>
                      </div>    
                    </div>
                  </div>
                </div>
              <div class="card-body">
                
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
                            <td class="align-middle text-right">{{ $r[$k] }}</td>                                
                          @endforeach
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                    
                        <tr>
                          <td></td>
                          <td>TOTAL</td>
                          @foreach ($Consolidado['header_date'] as $r)
                            <!-- @foreach ($Consolidado['header_date'] as $k) 
                             
                            @endforeach -->
                            <td class="align-middle text-right">0.00</td> 
                        @endforeach
                      </tr>
                     
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