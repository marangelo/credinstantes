@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_payrolls_comiciones')
@endsection
@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">            
            <div class="user-block">
              <img class="img-circle img-bordered-sm" src="{{ asset('/img/nomina.png') }}" alt="user image">
              <span class="username">
              {{$Titulo}} - {{$Payrolls->payroll_name}} 
              </span>
              <span class="description">Periodo {{$Payrolls->start_date}}  al {{$Payrolls->end_date}} </span>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"><span class="badge {{$Payrolls->Status->status_color}}"> <i class="{{$Payrolls->Status->status_icon}}"></i>  {{$Payrolls->Status->payroll_status_name}}</span></li>
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
                  <th class="border-0">NOMBRES Y APELLIDOS</th>
                  <th class="border-0 text-center">CEDULA</th>
                  <th class="border-0 text-center">N INSS</th>
                  <th class="border-0 text-center">MES</th>
                  <th class="border-0 text-center">FECHA</th>
                  <th class="border-0 text-center">COMISIONES</th>
                  <th class="border-0 text-center">NETO A PAGAR</th>
                  <th class="border-0 text-center">FIRMA</th>
                  <th class="border-0 text-center">VACACIONES</th>
                  <th class="border-0 text-center">PROV AGUINALDO</th>
                  <th class="border-0 text-center">PROV. INDEMNIZACION</th>
                  </tr>
                </thead>
                <tbody>
                  @php $ttVACACIONES = 0; @endphp
                  @php $ttAGUINALDO = 0; @endphp
                  @php $ttINDENMNIZACION = 0; @endphp
                  @php $ttNETO = 0; @endphp

                  @foreach($Employes as $p)

                    @php $NetoPagar  = $p->neto_pagar @endphp

                    @php $Vacaciones = $p->vacaciones @endphp
                    @php $Aguinaldo  = $p->aguinaldo @endphp
                    @php $Indenizacion = $p->indenmnizacion @endphp
                  
                  <tr>
                    <td class="align-middle">
                      @if($Payrolls->payroll_status_id == 1 || Auth::User()->id_rol == 1)
                        <a class="text-900" href="#!" onclick="AddGastos({{$p}})"><h6 class="mb-0 text-nowrap">{{$p->employee_full_name}}</h6></a>
                      @else
                      <a class="text-900" href="#!" ><h6 class="mb-0 text-nowrap">{{$p->employee_full_name}}</h6></a>
                      @endif
                    </td>
                    <td class="align-middle text-center"> {{$p->cedula}} </td>
                    <td class="align-middle text-end">{{$p->inns}}</td>
                    <td class="align-middle text-center"> {{ strtoupper($p->mes) }} </td>
                    <td class="align-middle text-center"> {{ \Date::parse($p->fecha)->format('d/m/Y')}} </td>
                    <td class="align-middle text-centrightrightrighter">C$. {{ number_format($p->comision,2) }} </td>
                    <td class="align-middle text-right">C$. {{ number_format($NetoPagar,2) }} </td>
                    <td class="align-middle text-center"> - </td>
                    <td class="align-middle text-right">C$. {{ number_format($Vacaciones,2) }} </td>
                    <td class="align-middle text-right">C$. {{ number_format($Aguinaldo,2) }} </td>
                    <td class="align-middle text-right">C$. {{ number_format($Indenizacion,2) }}</td>
                    
                  </tr>
                    @php $ttVACACIONES += $Vacaciones; @endphp
                    @php $ttAGUINALDO += $Aguinaldo; @endphp
                    @php $ttINDENMNIZACION += $Indenizacion; @endphp
                    @php $ttNETO += $NetoPagar; @endphp
                  @endforeach
                </tbody>
                  <tfoot>
                    
                    <tr>
                      <td class="border-0" colspan="6">SUB TOTAL.</td>
                      <td class="border-0 text-right">C$. <span id="neto_pagar_payroll">{{ number_format($ttNETO,2) }}</span></td>
                      <td class="border-0 text-center"> - </td>
                      <td class="border-0 text-right">C$. {{ number_format($ttVACACIONES,2) }}</td>
                      <td class="border-0 text-right">C$. {{ number_format($ttAGUINALDO,2) }}</td>
                      <td class="border-0 text-right">C$. {{ number_format($ttINDENMNIZACION,2) }}</td>
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
                  <span class="description"># Nomina: <span id="id_description"> {{$Payrolls->id_payrolls}} </span>  |  {{$Payrolls->start_date}} al {{$Payrolls->end_date}}  </span>
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
                        <label class="fs-0 " for="eventValDay">Comisión  C$.: </label>                    
                        <div class="input-group"><span class="input-group-text "><span class="fas fa-donate"></span></span>
                            <input class="form-control" id="txt_comisiones" type="text" name="inss_patronal"  placeHolder="0.00" onkeypress='return isNumberKey(event)'>
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