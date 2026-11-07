
<div class="modal fade" id="mdl-form-extra-lg">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">TITULO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item ">
                    <a class="nav-link active" id="custom-content-desembolso-tab" data-toggle="pill" href="#custom-content-desembolso" role="tab" aria-controls="custom-content-desembolso" aria-selected="true"> DESEMBOLSOS DE RECUPERACION. </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="custom-content-transferencias-tab" data-toggle="pill" href="#custom-content-transferencias" role="tab" aria-controls="custom-content-transferencias" aria-selected="false"> DEPOSITOS O TRANSFERENCIAS. </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="custom-content-depositos-tab" data-toggle="pill" href="#custom-content-depositos" role="tab" aria-controls="custom-content-depositos" aria-selected="false"> DEPOSITOS DE CLIENTES. </a>
                    </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-desembolso" role="tabpanel" aria-labelledby="custom-content-desembolso-tab">                    
                    
                        <div class="row mt-3"> 
                            <div class="col-md-5">
                                <div class="form-group">                      
                                    <label>CLIENTE. </label> 
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="name_cliente" placeholder="Nombre del Cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>MONTO. </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"> C$. </span>
                                    </div>
                                    <input type="text" class="form-control" id="txt_desembolso" placeholder="0.00 " onkeypress='return isNumberKey(event)'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label> Total : <span class="text-success" id="total_desembolso" >( 0.00 )</span></label>
                                <div class="input-group">                          
                                    <button type="button" class="btn btn-block bg-gradient-info" id="btn_save_desembolso">Guardar</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="custom-content-transferencias" role="tabpanel" aria-labelledby="custom-content-transferencias-tab">
                        
                        <div class="row mt-3"> 
                            <div class="col-md-7">
                                <div class="form-group">                      
                                    <label>CUENTAS PAR DEPT.</label>                      
                                    <select class="form-control select2" style="width: 100%;" id="id_select_transferencia">
                                        @foreach($Cuentas as $c)
                                        <option value="{{$c['id_cuenta']}}">{{$c['Descripcion']}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>MONTO. </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"> C$. </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="0.00" id="txt_transferencia" onkeypress='return isNumberKey(event)'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label> Total : <span class="text-success" id="total_transferencia" >( 0.00 )</span></label>
                                <div class="input-group">                          
                                    <button type="button" class="btn btn-block bg-gradient-info" id="btn_save_transferencia">Guardar</button>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="input-group">                            
                                    <input type="text" class="form-control"placeholder="Referencias" id="txt_referencia_transferencia" >
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="tab-pane fade" id="custom-content-depositos" role="tabpanel" aria-labelledby="custom-content-depositos-tab">

                        <div class="row mt-3 mb-3"> 

                            <div class="col-md-4">
                                <div class="form-group">                      
                                <label>CLIENTE.</label>                      
                                <select class="form-control select2" style="width: 100%;" id="id_select_cliente_deposito">
                                    @foreach($Clientes as $Cliente)
                                    <option value="{{$Cliente->id_clientes}}"> {{ strtoupper($Cliente->nombre) }} {{ strtoupper($Cliente->apellidos) }} </option>
                                    @endforeach
                                </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">                      
                                <label>CUENTAS BANCARIA.</label>                      
                                <select class="form-control select2" style="width: 100%;" id="id_select_cuenta_deposito">
                                    @foreach($Cuentas as $c)
                                    <option value="{{$c['id_cuenta']}}">{{$c['Descripcion']}} </option>
                                    @endforeach
                                </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>MONTO DEPT. </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"> C$. </span>
                                    </div>
                                    <input type="text" class="form-control" id="txt_deposito_monto" placeholder="0.00 " onkeypress='return isNumberKey(event)'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label> Total : <span class="text-success" id="total_depositos" >( 0.00 )</span></label>
                                <div class="input-group">                          
                                    <button type="button" class="btn btn-block bg-gradient-info" id="btn_save_deposito">Guardar</button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="dtAbono" data-target-input="nearest">
                                        <div class="input-group-append" data-target="#dtAbono" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <input type="text" class="form-control datetimepicker-input" data-target="#dtAbono" id="fecha_deposito" value="{{ date('d/m/y') }}"/>
                                    </div>
                                </div> 
                            </div>

                            <div class="col-md-8">
                                <div class="input-group">                          
                                    <input type="text" class="form-control" placeholder="Referencias" id="txt_referencia_deposito" >
                                </div>
                            </div>

                            

                        </div>
                    </div>
                    
                </div>

                <table id="tbl_deposiciones" class="table table-striped" style="width: 100%"></table>

            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                <button type="button" class="btn btn-primary">GUARDAR</button> -->
            </div>
        </div>
    </div>
</div>