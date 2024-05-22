@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_edit_payrolls_depreciacion')
@endsection
@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="lbl_titulo_reporte"> Nómina:  {{$Payrolls->payroll_name}} </h1>
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
        <div class="card card-secondary">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                    <div class="input-group date" id="dt-end" data-target-input="nearest">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar" aria-label="Buscar">
                    </div>
                </div>
              </div>
            </div>
            <div class="table-responsive scrollbar">
              <table class="table table-striped border-bottom" id="tbl_payroll_details">
                <thead class="light">
                  <tr class="bg-primary text-white dark__bg-1000">
                  <th class="border-0">Nombres Y Apellidos</th>
                  <th class="border-0 text-center">Cedula</th>
                  <th class="border-0 text-center">N INSS</th>
                  <th class="border-0 text-center">MES</th>
                  <th class="border-0 text-center">FECHA</th>
                  <th class="border-0 text-center">CONCEPTO</th>
                  <th class="border-0 text-center">PAGO</th>
                  </tr>
                </thead>
                <tbody>
                  @php $ttNetoPagar = 0; @endphp

                  @foreach($Employes as $p)

                    @php $NetoPagar  = $p->neto_pagar @endphp
                  
                  <tr>
                    <td class="align-middle">
                      @if($Payrolls->payroll_status_id == 1)
                        <a class="text-900" href="#!" onclick="AddGastos({{$p}})"><h6 class="mb-0 text-nowrap">{{$p->employee_full_name}}</h6></a>
                      @else
                      <a class="text-900" href="#!" ><h6 class="mb-0 text-nowrap">{{$p->employee_full_name}}</h6></a>
                      @endif
                    <td class="align-middle text-center"> {{$p->cedula}} </td>
                    <td class="align-middle text-center">{{$p->inns}}</td>
                    <td class="align-middle text-center"> {{ strtoupper($p->mes) }} </td>
                    <td class="align-middle text-center"> {{ \Date::parse($p->fecha)->format('d/m/Y')}} </td>
                    <td class="align-middle text-center"> {{$p->concepto}} </td>
                    <td class="align-middle text-right">C$. {{ number_format($NetoPagar,2) }} </td>
                    
                  </tr>
                    @php $ttNetoPagar += $NetoPagar; @endphp
                  @endforeach
                </tbody>
                  <tfoot>
                    
                    <tr>
                      <td class="border-0" colspan="6">SUB TOTAL.</td>
                      <td class="border-0 text-right">C$. <span id="neto_pagar_payroll">{{ number_format($ttNetoPagar,2) }}</span> </td>
                    </tr>
                  </tfoot>
              </table>
            </div>
          </div>
          <div class="card-footer">
            
              <div class="text-right">
                <a href="#" class="btn bg-warning" id="btn_export_payroll">
                  <i class="fas fa-file-excel"></i> Exportar
                </a>
                @if(Auth::User()->id_rol == 1  && $Payrolls->payroll_status_id < 3)
                <a href="#" class="btn btn-success" id="btn_process_payroll">
                  <i class="far fa-credit-card"></i> Procesar
                </a>
                @elseif(Auth::User()->id_rol == 3 && $Payrolls->payroll_status_id == 1)
                <a href="#" class="btn btn-primary" id="btn_process_payroll">
                  <i class="fas fa-check"></i> Aprobar
                </a>
              @endif
              </div>
            
          </div>
        </div>
      </div>
      <div class="modal fade" id="modal_add_gasto">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <div class="user-block">
                  <img class="img-circle" src="{{asset('img/user-01.png')}}" alt="User Image">
                  <span class="username"><a href="#" id="id_txt_nombre_empleado"> -  </a></span>
                  <span class="description"># Nomina: <span id="id_description">{{$Payrolls->id_payrolls}}</span>  |  {{$Payrolls->start_date}} al {{$Payrolls->end_date}}  </span>
                  <span id="id_num_row" style="display:none"></span>
                  <span id="PayRoll_type" style="display:none">{{$Payrolls->payroll_type_id}}</span>
                  <span id="PayRoll_status" style="display:none">{{$Payrolls->payroll_status_id}}</span>
                </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">            
                <div class="row g-3">

                    <div class="col-md-12 mb-3">
                        <label class="fs-0 " for="eventValDay">Pago C$.: </label>                    
                        <div class="input-group"><span class="input-group-text "><span class="fas fa-donate"></span></span>
                            <input class="form-control" id="pago_depreciacion" type="text" name="inss_patronal"  placeHolder="0.00" onkeypress='return isNumberKey(event)'>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label>Concepto:</label>
                            <div class="input-group date">
                                <textarea class="form-control" rows="3" name="description" id="depreciacion_concepto" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-success" id="btn_save">Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </section>

@endsection('content')