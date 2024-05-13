@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_payrolls')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                
                <table id="tbl_payrolls" class="table">
                    <thead >
                        <tr>
                            <th>Nóminas</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Neto a Pagar</th>
                            <th>Estatus.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Payrolls as $p)
                        <tr>                                  
                            <td>
                                <div class="d-flex align-items-center position-relative">
                                  <div class="flex-1">
                                      <h6 class="mb-0 fw-semi-bold"><a class="stretched-link text-900" href="EditPayrolls/{{$p->id_payrolls}}">1Q-Jun-00</a></h6>
                                      <p class="text-500 fs--2 mb-0">  {{$p->Type->payroll_type_name}}</p>
                                  </div>
                                </div>
                            </td>
                            <td>{{ Date::parse($p->start_date)->format('D, M d, Y')  }} </td>
                            <td>{{ Date::parse($p->end_date)->format('D, M d, Y')}} </td>
                            <td><a class="text-primary fw-semi-bold" href="#!">C$ 500,000.00</a></td>
                            <td>
                              <span class="badge badge rounded-pill d-block p-2 {{$p->Status->status_color}}">{{$p->Status->payroll_status_name}}<span class="ms-1 {{$p->Status->status_icon}}" data-fa-transform="shrink-2"></span>
                            </span>
                            <td>
                                <div>
                                    <button class="btn p-0" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" onClick="edit_request({{$p}})"><span class="text-500 fas fa-lock-open"></span></button>
                                    <button class="btn p-0" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" onClick="edit_request({{$p}})"><span class="text-500 fas fa-edit"></span></button>
                                    <button class="btn p-0 ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover"  onClick="Remover({{$p->id_playrolls}},3)"><span class="text-500 fas fa-trash-alt"></span></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach    
                    
                    </tbody>
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

    <div class="modal fade" id="modal_new_request">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <div class="user-block">
              <img class="img-circle" src="{{asset('img/user.png')}}" alt="User Image">
              <span class="username"><a href="#"> Nueva Nómina </a></span>
              <span class="description"># Ingrese informacion de partura de Nueva Nómina</span>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">            
            <div class="row g-3">
                <div class="col-md-12 mb-12">
                    <div class="input-group date" id="dtInicio" data-target-input="nearest">
                        <div class="input-group-append" data-target="#dtInicio" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        <select class="custom-select" id="payroll_type"  name="PayrollType">
                          @foreach($PayRollType as $t)
                            <option value="{{$t->id_payroll_type}}"> {{$t->payroll_type_name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mb-3 mt-3">
                    <label>Inicio</label>
                    <div class="input-group date" id="dtInicio" data-target-input="nearest">
                        <div class="input-group-append" data-target="#dtInicio" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        <input type="text" class="form-control datetimepicker-input" data-target="#dtInicio" id="IdFechaInicio" value="{{ date('d/m/y') }}"/>
                    </div>
                </div> 

                <div class="col-md-6 mb-3">
                    <label>Final</label>
                    <div class="input-group date" id="dtFinal" data-target-input="nearest">
                        <div class="input-group-append" data-target="#dtFinal" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        <input type="text" class="form-control datetimepicker-input" data-target="#dtFinal" id="IdFechaFinal" value="{{ date('d/m/y') }}"/>
                    </div>
                </div> 

            

                <div class="col-md-6 mb-3">
                    <label class="fs-0 " for="eventValDay">INSS Patronal </label>                    
                    <div class="input-group"><span class="input-group-text "><span class="fas fa-donate"></span></span>
                        <input class="form-control" id="payroll_inss_patronal" type="text" name="cant_day" disabled="" placeHolder="0.00" value="{{$Inactec->inatec_value}}">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="fs-0 " for="eventValDay">INATEC</label>                    
                    <div class="input-group"><span class="input-group-text "><span class="fas fa-donate"></span></span>
                        <input class="form-control" id="payroll_inactec" type="text" name="cant_day" disabled="" placeHolder="0.00" value="{{$InssParonal->inss_patronal_value}}">
                    </div>
                </div>

            </div>

            <div class="form-group">
              <label>Observacion:</label>
              <div class="input-group date">
              <textarea class="form-control" rows="3" name="description" id="payroll_observation" ></textarea>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="btn_save_abono">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection