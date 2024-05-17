@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_edit_payrolls')
@endsection
@section('content')


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
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title"> Planilla:  {{$Payrolls->payroll_name}}</h3>
            <span id="Id_PayRoll" style="display:none"></span>
          </div>
          <!-- /.card-header -->
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
              <table class="table table-striped border-bottom">
                <thead class="light">
                  <tr class="bg-primary text-white dark__bg-1000">
                  <th class="border-0">Nombres Y Apellidos</th>
                  <th class="border-0 text-center">Cedula</th>
                  <th class="border-0 text-center">N INSS</th>
                  <th class="border-0 text-center">MES</th>
                  <th class="border-0 text-center">FECHA</th>
                  <th class="border-0 text-center">SALARIO BASICO MENSUAL</th>
                  <th class="border-0 text-center">DIAS TRABADOS</th>
                  <th class="border-0 text-center">SALARIO QUINCENAL</th>
                  <th class="border-0 text-center">COMICIONES</th>
                  <th class="border-0 text-center">NETO A PAGAR</th>
                  <th class="border-0 text-center">FIRMA</th>
                  <th class="border-0 text-center">VACACIONES</th>
                  <th class="border-0 text-center">PROV AGUINALDO</th>
                  <th class="border-0 text-center">PROV. INDEMNIZACION</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($Employes as $p)

                  @php $Comisiones = $p->comision @endphp
                  @php $NetoPagar  = $Comisiones + $p->salario_mensual @endphp
                  @php $Vacaciones = ( $NetoPagar / 30 ) * 2.5 @endphp
                  @php $Aguinaldo  = ( $NetoPagar / 12 ) @endphp
                  @php $Indenizacion = ( $NetoPagar / 12 ) @endphp
                  
                  <tr>
                    <td class="align-middle">
                      <a class=" text-900" href="#!" onclick="AddGastos({{$p}})" ><h6 class="mb-0 text-nowrap">{{$p->employee_full_name}}  </h6></a>
                      <p class="mb-0"> LA POSICION SUPUESTAMENTE</p>
                      
                    </td>
                    <td class="align-middle text-center"> {{$p->cedula}} </td>
                    <td class="align-middle text-end">{{$p->inns}}</td>
                    <td class="align-middle text-end"> {{$p->mes}} </td>
                    <td class="align-middle text-center"> {{$p->fecha}} </td>
                    <td class="align-middle text-center">C$. {{ number_format($p->salario_mensual,2) }} </td>
                    <td class="align-middle text-center"> {{ number_format($p->dias_trabajados,2) }} </td>
                    <td class="align-middle text-center">C$. {{ number_format( $p->salario_quincenal,2)}} </td>
                    <td class="align-middle text-center">C$. {{ number_format($Comisiones,2) }} </td>
                    <td class="align-middle text-center">C$. {{ number_format($NetoPagar,2) }} </td>
                    <td class="align-middle text-center"> - </td>
                    <td class="align-middle text-center">C$. {{ number_format($Vacaciones,2) }} </td>
                    <td class="align-middle text-center">C$. {{ number_format($Aguinaldo,2) }} </td>
                    <td class="align-middle text-center">C$. {{ number_format($Indenizacion,2) }}</td>
                    
                  </tr>
                  @endforeach
                </tbody>
                  <tfoot>
                    
                    <tr>
                      <td class="border-0" colspan="12"></td>
                      <td class="border-0 text-center">Total:</td>
                      <td class="border-0 text-center">C$. 00.00</td>
                    </tr>
                  </tfoot>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="text-right">
              <a href="#" class="btn bg-warning">
                <i class="fas fa-file-excel"></i> Exportar
              </a>
              <a href="#" class="btn btn-success">
                <i class="fas fa-dollar-sign"></i> Procesar
              </a>
            </div>
          </div>
        </div>
      </div>

           
    
      <!-- /.container-fluid -->
      <div class="modal fade" id="modal_add_gasto">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <div class="user-block">
                  <img class="img-circle" src="{{asset('img/user-01.png')}}" alt="User Image">
                  <span class="username"><a href="#" id="id_txt_nombre_empleado"> -  </a></span>
                  <span class="description"># Nomina: <span id="id_description"> {{$Payrolls->id_payrolls}} </span>  |  {{$Payrolls->start_date}} al {{$Payrolls->end_date}}  </span>
                  <span id="id_num_row" style="display:none"></span>
                </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">            
                <div class="row g-3">

                    <div class="col-md-6 mb-3">
                        <label class="fs-0 " for="eventValDay">Comision C$.: </label>                    
                        <div class="input-group"><span class="input-group-text "><span class="fas fa-donate"></span></span>
                            <input class="form-control" id="payroll_comision" type="text" name="inss_patronal"  placeHolder="0.00" onkeypress='return isNumberKey(event)'>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fs-0 " for="eventValDay">Dias Trabajados: </label>                    
                        <div class="input-group"><span class="input-group-text "><span class="fas fa-calendar"></span></span>
                            <input class="form-control" id="payroll_dias_trabajados" type="text" name="inatec" placeHolder="0.00" onkeypress='return isNumberKey(event)'>
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