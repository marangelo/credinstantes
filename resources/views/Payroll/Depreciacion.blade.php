@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_edit_payrolls_depreciacion')
@endsection
@section('content')


<div class="content-wrapper">
    <link rel="stylesheet" href="{{ asset('css/payroll.css') }}?v={{ filemtime(public_path('css/payroll.css')) }}">

    <section class="content-header">
      <div class="container-fluid">
        <div class="pr-header">
          <img class="pr-header-icon" src="{{ asset('/img/nomina.png') }}" alt="" width="44" height="44">
          <div class="pr-header-text">
            <h1 class="pr-title">{{$Titulo}} · {{$Payrolls->payroll_name}}</h1>
            <p class="pr-subtitle">Período {{ \Date::parse($Payrolls->start_date)->format('d M Y') }} al {{ \Date::parse($Payrolls->end_date)->format('d M Y') }}</p>
          </div>
          <span class="badge pr-status {{$Payrolls->Status->status_color}}"><i class="{{$Payrolls->Status->status_icon}}" aria-hidden="true"></i> {{$Payrolls->Status->payroll_status_name}}</span>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="pr-toolbar">
              <div class="pr-search">
                <i class="fas fa-search" aria-hidden="true"></i>
                <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar colaborador" aria-label="Buscar colaborador" autocomplete="off">
              </div>
              <div class="pr-actions">
                <button type="button" class="btn btn-default" id="btn_export_payroll">
                  <i class="far fa-file-excel" aria-hidden="true"></i> <span>Exportar</span>
                </button>
                @if(Auth::User()->id_rol == 1  && $Payrolls->payroll_status_id < 3)
                  <button type="button" class="btn btn-success" id="btn_process_payroll">
                    <i class="far fa-credit-card" aria-hidden="true"></i> <span>Procesar</span>
                  </button>
                @elseif(Auth::User()->id_rol == 3 && $Payrolls->payroll_status_id == 1)
                  <button type="button" class="btn btn-success" id="btn_process_payroll">
                    <i class="fas fa-check" aria-hidden="true"></i> <span>Aprobar</span>
                  </button>
                @endif
              </div>
            </div>

            <div class="table-responsive pr-table-wrap">
              <table class="table pr-table" id="tbl_payroll_details">
                <thead>
                  <tr>
                  <th>Colaborador</th>
                  <th>Cédula</th>
                  <th>N.º INSS</th>
                  <th>Mes</th>
                  <th>Fecha</th>
                  <th>Concepto</th>
                  <th class="text-right pr-col-neto">Pago</th>
                  </tr>
                </thead>
                <tbody>
                  @php $ttNetoPagar = 0; @endphp

                  @foreach($Employes as $p)

                    @php $NetoPagar  = $p->neto_pagar @endphp

                  <tr>
                    <td class="pr-col-name">
                      @if($Payrolls->payroll_status_id == 1)
                        <a class="pr-name" href="#!" onclick="AddGastos({{$p}}); return false;" title="Editar pago">{{$p->employee_full_name}}</a>
                      @else
                        <span class="pr-name">{{$p->employee_full_name}}</span>
                      @endif
                    </td>
                    <td class="text-nowrap">{{$p->cedula}}</td>
                    <td class="text-nowrap">{{$p->inns}}</td>
                    <td>{{ ucfirst(mb_strtolower($p->mes)) }}</td>
                    <td class="text-nowrap" data-order="{{ $p->fecha }}">{{ \Date::parse($p->fecha)->format('d/m/Y')}}</td>
                    <td>{{$p->concepto}}</td>
                    <td class="pr-money pr-col-neto">C$ {{ number_format($NetoPagar,2) }}</td>
                  </tr>
                    @php $ttNetoPagar += $NetoPagar; @endphp
                  @endforeach
                </tbody>
                  <tfoot>
                    <tr class="pr-total">
                      <td>Subtotal <span class="pr-muted">({{ $Employes->count() }})</span></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="pr-money pr-col-neto">C$ <span id="neto_pagar_payroll">{{ number_format($ttNetoPagar,2) }}</span></td>
                    </tr>
                  </tfoot>
              </table>
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