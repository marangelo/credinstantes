@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_historial_print')
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
              <li class="breadcrumb-item active">Historial</li>
              <li class="breadcrumb-item active">Imprimir</li>

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
            


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> Estado de Cuenta.
                    <small class="float-right">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row">                    
              <table class="table table-bordered">
                  
                  <tbody>
                    <tr>
                      <td> <strong><i class="fas fa-info mr-1"></i> No. CREDITO:</strong>  {{$Credito->id_creditos}}</td>
                      <td> <strong><i class="fas fa-file-invoice-dollar mr-1"></i> MONTO APROBADO:</strong> C$  {{ number_format($Credito->monto_credito,2) }}</td>
                    </tr>
                    <tr>
      <td ></td>
      <td><strong><i class="fas fa-file-invoice-dollar mr-1"></i> CUOTA:</strong> <strong>C$  {{ number_format($Credito->cuota,2) }}</strong></td>
    </tr>
                    <tr>
                      <td> <strong><i class="fas fa-user mr-1"></i> CLIENTE:</strong> {{$Credito->Clientes->nombre}} {{$Credito->Clientes->apellidos}} </td>
                      <td> <strong><i class="fas fa-book mr-1"></i> ZONA: </strong> {{$Credito->Clientes->getZona->nombre_zona}}</td>
                    </tr>
                    <tr>
                      <td> <strong><i class="fas fa-book mr-1"></i> PLAZO:</strong> {{$Credito->plazo}}</td>
                      <td> <strong><i class="fas fa-calendar mr-1"></i> FRECUENCIA DE PAGO:</strong> Semanal</td>
                    </tr>
                    <tr>
                      <td> <strong><i class="fas fa-file-invoice-dollar mr-1"></i> INTERES A PAGAR:</strong> C$ {{ number_format(( $Credito->numero_cuotas * $Credito->intereses_por_cuota ),2) }}</td>
                      <td> <strong><i class="fas fa-calendar mr-1"></i> FECHA DE VENCIMIENTO:</strong> {{ \Date::parse($Credito->fecha_ultimo_abono)->format('D, M d, Y')}}</td>
                    </tr>
                  </tbody>
                </table>
                </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>No. RECIBO</th>
                      <th>No. CUOTA</th>
                      <th>FECHA</th>
                      <th>CAPITAL</th>
                      <th>INTERES</th>
                      <th>SALDO ANTERIOR</th>
                      <th>SALDO</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($Pagos as $a)
                        <tr>
                            <td>{{$a['id_abonoscreditos']}}</td>
                            <td>{{$a['NumPago']}}</td>
                            <td>{{$a['fecha_cuota']}}</td>
                            <td><span class="badge rounded-pill badge-soft-info text-success">C$ {{ number_format($a['pago_capital'],2) }} </span></td>
                            <td><span class="badge rounded-pill badge-soft-info text-warning">C$ {{ number_format($a['pago_intereses'],2) }}</span></td>                            
                            <td><span class="badge rounded-pill badge-soft-info {{ $a['saldo_cuota'] > 0 ? 'text-danger' : '' }}">C$ {{ number_format($a['saldo_anterior'],2) }}</span></td>
                            <td>{{ number_format($a['saldo_actual'],2) }}</td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

            
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="../PrintViewPDF/{{$Credito->id_creditos}}" target="_blank" class="btn btn-danger float-right" style="margin-right: 5px;">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                  </a>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div>
       
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection