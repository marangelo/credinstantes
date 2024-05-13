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
            <!-- /.card -->
            <div class="card">
              <div class="card-header" style="display:none" >
                
               
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control select2" style="width: 100%;" id="id_select_zona" name="IdZona">
                      @for($mes = 1; $mes <= 12; $mes++)
                        <option value="{{$mes}}">{{Date::parse(date('F', mktime(0, 0, 0, $mes, 1)))->format('F')}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">                            
                            <select class="form-control select2" id="id_select_zona" name="IdZona">
                              @for($year = 2024; $year >= 2022; $year--)
                                <option value="{{$year}}">{{$year}}</option>
                              @endfor
                            </select>
                            <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-filter"></i></div>
                            </div>
                            <div class="card-tools">
                               
                                <button type="button" class="btn btn-warning" id="btn-add-nomina">
                                    <i class="fas fa-plus-circle "></i>
                                </button>
                                <button type="button" class="btn btn-success" id="btn-add-employee">
                                    <i class="fas fa-user-tie "></i>
                                </button>
                            </div>
                          
                        </div>
                    </div>
                  </div>
                </div>
                <form method="POST" action="{{ (isset($Employee)) ? route('UpdateEmployee') : route('SaveEmployee') }}" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex"></div>

                                    @csrf
                                    

                                    <div class="row gx-2">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="event-name">NUMERO DE CLIENTES</label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                <input class="form-control" type="text"  />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="event-name">SALDOS DE CARTERA</label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                <input class="form-control" type="text"  />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-">
                                            <label class="form-label" for="event-name">EFECTIVO DISPONIBLE  DE CAP</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-envelope"></span></span>
                                                <input class="form-control" id="txt_email" type="text"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">DESEMBOLSOS  DEL MES</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text" />
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">CLIENTES NUEVOS</label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                <input class="form-control" id="event-name" type="text" />
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">RENOVACIONES: </label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-dollar-sign"></span></span>
                                                <input class="form-control" id="event-name" type="text" />
                                            </div>
                                        </div>


                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">MORA ATRASADA</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                            <input class="form-control" id="event-name" type="text" />
                                            </div>
                                        </div>


                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">MORA VENCIDA</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text"  />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">RECUPERACION</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text"  />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">INGRESOS FINANCIEROS</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text"/>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">GASTOS OPERATIVOS</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text"  />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">UTIL DE ACCIONISTAS REINVERTIDAS</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text" />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">REINVERSION DE INTERESES / P CAPITAL</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text" />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="event-name">UTIL. RETENIDAS PARA PROVICION</label>
                                            <div class="input-group">
                                                <span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text"  />
                                            </div>
                                        </div>
                                       
                                        
                                 
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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