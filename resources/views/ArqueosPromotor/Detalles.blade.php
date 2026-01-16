@extends('layouts.lyt_listas')

@section('metodosjs')
@include('ArqueosPromotor.js_ArqueoDetalle')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <!-- /.card -->
        <small class="badge badge-info mb-2"><i class="fas fa-donate"></i> #: <span id="id_moneda"> {{$ID}}</span></small>
        <div class="card">
            <div class="card-header" >
            <h3 class="card-title" id="IdCardTitle"> - - - - - - - </h3>
            <div class="card-tools">
                <div class="input-group" id="dt-arqueo" data-target-input="nearest">
                    <div class="input-group-append" data-target="#dt-arqueo" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#dt-arqueo" id="dtIni" name="nmIni" />     
                    <span class="input-group-append">
                        <a href="../ExportArqueo/{{$ID}}" class="btn btn-success"><i class="far fa-file-excel"></i></a>
                    </span>
                </div>
            </div>
            
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @csrf
                <div class="row">                  
                    <div class="col-md-3 col-sm-6">
                        <label id="lbl_deposito_dia" >EFEC. ENTREGADO.</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"> C$. </span>
                            </div>
                            <input type="text" class="form-control" id="txt_entregado" 
                            onkeypress='return isNumberKey(event)'
                            placeholder="0.00 "
                            >                        
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label id="lbl_deposito_tranferencia" >EFEC. DESEMBOLSADO.</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">C$.</span>
                            </div>
                            <input type="text" class="form-control" id="txt_desembolsado" placeholder="0.00 " onkeypress='return isNumberKey(event)' disabled >                    
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label>EFEC. SOBRANTE </label><em>(ENTREGADO - DESEMBOLSADO)</em>               
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"> C$. </span>
                            </div>
                            <input type="text" class="form-control" id="id_sobrante" placeholder="0.00 " onkeypress='return isNumberKey(event)' disabled>
                            
                        </div>
                    </div>                
                    <div class="col-md-3 col-sm-6">
                        <label id="lbl_gastos">EFEC. CONCILIADOS.</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">C$.</span>
                            </div>
                            <input type="text" class="form-control" placeholder="0.00 "  id="txt_consiliado" onkeypress='return isNumberKey(event)' disabled>
                            <div class="input-group-append">
                                <div class="input-group-text btn-warning" id="bt_save_arqueo"><i class="fas fa-save"></i></div>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
            <div class="row">                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">     
                            <table class="table table-hover table-bordered" id="tbl_creditos_arqueo"></table>           
                            <div class="row">                        
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comentarios</label>
                                        <textarea class="form-control" rows="5" placeholder="Algun Comentario ..." id="id_commit"> </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer clearfix">
                        <div class="row"> 
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">                            
                                    <a href="#!" class="btn btn-success btn-block" id="btn_procesar" > <i class="fas fa-save"></i>  PROCESAR</a>                            
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">                            
                                    <a href="#!" class="btn btn-danger btn-block" id="btn_remover" > <i class="fas fa-window-close"></i>  CANCELAR</a>                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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