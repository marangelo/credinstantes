@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_arqueo_nuevo')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <span class="badge badge-info"> #: {{$Arqueo->id_arqueo}}</span>
            <h1> {{strtoupper ( $Arqueo->getZona->nombre_zona ) }} / ESTA PENDIENTE EL NOMBRE</h1>
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
                  <div class="col-md-3">
                    <label>FECHA ARQUEO</label>
                    
                    <div class="form-group">
                      <div class="input-group date" id="dt-arqueo" data-target-input="nearest">
                          <div class="input-group-append" data-target="#dt-arqueo" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-arqueo" id="dtIni" name="nmIni" value="{{ date('d/m/Y', strtotime($Arqueo->fecha_arqueo)) }}"/>                          
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label >DEP. DEL DIA C$.</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-white"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="text" class="form-control" id="" placeholder="0.00 " value="{{$Arqueo->deposito_dia}}">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label >DEP O TRANSF. C$.</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-olive"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="text" class="form-control" id="" placeholder="0.00 " value="{{$Arqueo->deposito_tranferencia}}" >
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label>GASTO OPERATI. DEL DIA C$.</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-purple">
                          <i class="fas fa-dollar-sign"></i>
                        </span>
                      </div>
                      <input type="text" class="form-control" placeholder="0.00 " value="{{$Arqueo->gasto_operacion}}">
                      <div class="input-group-append">
                        <div class="input-group-text btn-success"><i class="fas fa-plus-circle"></i></div>
                      </div>
                    </div>

                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">BILLETES CORDOBAS</h3>
                      </div>
              
                      <div class="card-body">
                        <table class="table table-hover table-bordered" id="tbl_moneda_nio">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th class="text-center">DENOMINACION</th>
                              <th class="text-center">CANTIDAD</th>
                              <th class="text-center">TOTAL</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php($m_nio=1)
                            @foreach ($Arqueo->getDetalles as $m)
                              @if($m->moneda === 'NIO')
                                <tr>
                                  <td>{{$m_nio}}.</td>
                                  <td class="text-right">C$ {{number_format($m->denominacion,2)}}</td>
                                  <td class="text-right">{{number_format($m->cantidad,2)}}</td>
                                  <td class="text-right">C$ {{number_format($m->total,2)}}</td>
                                </tr>
                                @php($m_nio++)
                              @endif                            
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="card-footer p-0">
                        <ul class="nav nav-pills flex-column">                  
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              SUB TOTAL CORDOBAS
                              <span class="text-bold text-lg float-right text-success">C$ 0.00</span>
                            </a>
                          </li>                 
                        </ul>
                      </div>                    
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">DOLARES</h3>
                        <div class="card-tools">
                          <span class="badge badge-warning"> T/C: C$. 36.00</span>
                        </div>
                      </div>
              
                      <div class="card-body">
                        <table class="table table-hover table-bordered" id="tbl_moneda_usd">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th class="text-center">DENOMINACION</th>
                              <th class="text-center">CANTIDAD</th>
                              <th class="text-center">TOTAL</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php($m_usd=1)
                            @foreach ($Arqueo->getDetalles as $m)
                              @if($m->moneda === 'USD')
                                <tr>
                                  <td>{{$m_usd}}.</td>
                                  <td class="text-right">$ {{number_format($m->denominacion,2)}}</td>
                                  <td class="text-right">{{number_format($m->cantidad,2)}}</td>
                                  <td class="text-right">C$ {{number_format($m->total,2)}}</td>
                                </tr>
                                @php($m_usd++)
                              @endif                            
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="card-footer p-0">
                        <ul class="nav nav-pills flex-column">                  
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              SUB TOTAL DOLARES - CORDOBAS
                              <span class="text-bold text-lg float-right text-success">C$ 0.00</span>
                            </a>
                          </li>                 
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <div class="row"> 
                    <div class="col-sm-4 col-md-4">
                      <div class="description-block border-right">
                        <h5 class="description-header text-warning">C$ <span id="xxxxxx">0.00</span></h5>
                        <span class="description-text">TOTAL </span>
                      </div>
                    </div>    
                    <div class="col-sm-4 col-md-4">
                      <div class="description-block border-right">
                        <h5 class="description-header text-warning">C$ <span id="xxxxxx">0.00</span></h5>
                        <span class="description-text">SISTEMA </span>
                      </div>
                    </div>             
                    <div class="col-sm-4 col-md-4">
                      <div class="description-block ">
                        <h5 class="description-header text-orange" >C$ <span id="xxxxxx" >0.00</span></h5>
                        <span class="description-text">SISTEMA vs EFECTIVO</span>
                      </div>
                    </div>
                </div>
                <!-- /.row -->
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