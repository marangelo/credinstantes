<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">

<!------ Include the above in your HEAD tag ---------->


<div class="">
    <div class="">
        <div class="">
          
            <div class="">
                <div class="text-center">
                    <h3><img src="{{ asset('img/logo-gris.jpg') }}" alt="Logo" class="mt-3" style="opacity: .8" width="70" height="80"></h3>
                    <h3>CREDIN$TANTES</h3>
                    <h3>JINOTEPE - CARAZO</h3>
                    <h3>2533 - 2870</h3>
                    <h4>8784 - 1877</h4>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No RECIBO #{{ $Abono->id_abonoscreditos}}</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-9"><em>FECHA</em></h4></td>
                            <td class="col-md-1 text-right">{{ date('Y-m-d',strtotime($Abono->fecha_cuota))}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>HORA</em></h4></td>
                            <td class="col-md-1 text-right">{{ date('H:i:s')}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>NOMBRE</em></h4></td>
                            <td class="col-md-1 text-right">{{ $Abono->credito->Clientes->nombre}} {{ $Abono->credito->Clientes->apellidos}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>ESTADO</em></h4></td>
                            <td class="col-md-1 text-right">{{ $Abono->credito->estado->nombre_estado}} </td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>FECHA DE VENCIMIENTO</em></h4></td>
                            <td class="col-md-1 text-right">{{ date('Y-m-d',strtotime($Abono->credito->fecha_ultimo_abono))}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>CUOTA</em></h4></td>
                            <td class="col-md-1 text-right">C$ {{ number_format($Abono->cuota_credito,2)}}</td>
                        </tr>

                        <tr>
                            <td class="col-md-9"><em>ABONO PRESTAMO</em></h4></td>
                            <td class="col-md-1 text-right">C$ {{ number_format($Abono->cuota_cobrada,2)}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>SALDO ANTERIOR</em></h4></td>
                            <td class="col-md-1 text-right">C$ {{ number_format($Abono->saldo_anterior,2)}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>TOTAL PAGO</em></h4></td>
                            <td class="col-md-1 text-right">C$ {{ number_format($Abono->cuota_cobrada,2)}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>SALDO ACTUAL</em></h4></td>
                            <td class="col-md-1 text-right">C$ {{ number_format($Abono->saldo_actual,2)}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>USUARIO</em></h4></td>
                            <td class="col-md-1 text-right">{{Session::get('name_session')}}</td>
                        </tr>
                    </tbody>
                </table>
               </td>
            </div>
        </div>
    </div>