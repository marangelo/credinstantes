@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_arqueo_nuevo')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->
            <small class="badge badge-info mb-1"><i class="fas fa-donate"></i> #: <span id="id_moneda">{{$Arqueo->id_arqueo}}</span></small>
            <div class="card">
              <div class="card-header" >
              <h3 class="card-title">{{strtoupper ( $Arqueo->getZona->nombre_zona ) }} / {{strtoupper ( $Arqueo->getZona->UsuarioCobrador->nombre ) }}</h3>
                <div class="card-tools">
                  <div class="input-group" id="dt-arqueo" data-target-input="nearest">
                    <div class="input-group-append" data-target="#dt-arqueo" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#dt-arqueo" id="dtIni" name="nmIni" value="{{ date('d/m/Y', strtotime($Arqueo->fecha_arqueo)) }}"/>     
                    <span class="input-group-append">
                      <a href="../ExportArqueo/{{$Arqueo->id_arqueo}}" class="btn btn-success"><i class="far fa-file-excel"></i></a>
                    </span>
                  </div>
                </div>
              
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              @csrf
                <div class="row">                  
                  <div class="col-md-3">
                    <label >DESEMBOLSOS DEL DIA.</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-white"> C$. </span>
                      </div>
                      <input type="text" class="form-control" id="txt_deposito_dia" placeholder="0.00 " value="{{ number_format($Arqueo->deposito_dia,2) }}" onkeypress='return isNumberKey(event)'>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label >DEPOSITOS O TRANSFERENCIAS.</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-olive">C$.</span>
                      </div>
                      <input type="text" class="form-control" id="txt_deposito_tranferencia" placeholder="0.00 " value="{{ number_format($Arqueo->deposito_tranferencia,2) }}" onkeypress='return isNumberKey(event)'>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label>SISTEMA </label>    <em>(Capital + Interes)</em>               
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-olive"> C$. </span>
                      </div>
                      <input type="text" class="form-control" id="id_total_sistema" placeholder="0.00 " value="{{ number_format($Cobrado,2) }}" onkeypress='return isNumberKey(event)'>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label>GASTO OPERATI. DEL DIA.</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-purple">C$.</span>
                      </div>
                      <input type="text" class="form-control" placeholder="0.00 " value="{{ number_format($Arqueo->gasto_operacion,2) }}" id="txt_gastos" onkeypress='return isNumberKey(event)'>
                      <div class="input-group-append">
                        <div class="input-group-text btn-success" id="bt_save_arqueo"><i class="fas fa-save"></i></div>
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
                        <table class="table table-hover table-bordered" id="tbl_moneda_nio"></table>
                      </div>
                      <div class="card-footer p-0">
                        <ul class="nav nav-pills flex-column">                  
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              SUB TOTAL CORDOBAS
                              <span class="text-bold text-lg float-right text-success">C$ <span id="ID_TOTAL_NIO">11111</span></span>
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
                          <span class="badge badge-warning"> T/C: C$. <span id="lbl_moneda_tc">{{number_format($Arqueo->tc,2)}}</span></span>
                        </div>
                      </div>
              
                      <div class="card-body">
                        <table class="table table-hover table-bordered" id="tbl_moneda_usd"></table>
                    
                        <div class="row mt-3">                        
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Comentarios</label>
                              <textarea class="form-control" rows="5" placeholder="Algun Comentario ..." id="id_commit"> {{$Arqueo->comentario}}</textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer p-0">
                        <ul class="nav nav-pills flex-column">                  
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              SUB TOTAL DOLARES - CORDOBAS
                              <span class="text-bold text-lg float-right text-success">C$ <span id="id_lbl_total_usd"></span></span>
                            </a>
                          </li>                 
                        </ul>
                      </div>
                    </div>


                  </div>
                </div>

                <div class="card-footer">
                  <div class="row"> 
                    <div class="col-sm-6 col-md-6">
                      <div class="description-block border-right">
                        <h5 class="description-header text-warning">C$ <span id="id_lbl_total_final">0.00</span></h5>
                        <span class="description-text">TOTAL </span>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                      <div class="description-block ">
                        <h5 class="description-header text-orange" >C$ <span id="TOTAL_SYS_VS_CASH" >0.00</span></h5>
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