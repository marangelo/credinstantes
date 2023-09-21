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
                    <h3>CREDIN$TANTES</h3>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No RECIBO #{{ $Abono->id_abonoscreditos}}</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-9"><em>FECHA</em></h4></td>
                            <td class="col-md-1 text-center">{{ $Abono->fecha_cuota}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>HORA</em></h4></td>
                            <td class="col-md-1 text-center">{{ $Abono->fecha_cuota}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>NOMBRE</em></h4></td>
                            <td class="col-md-1 text-center">{{ $Abono->credito->Clientes->nombre}} {{ $Abono->credito->Clientes->apellidos}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>CUOTA</em></h4></td>
                            <td class="col-md-1 text-center">C$ {{ $Abono->cuota_credito}}</td>
                        </tr>

                        <tr>
                            <td class="col-md-9"><em>ABONO PRESTAMO</em></h4></td>
                            <td class="col-md-1 text-center">C$ {{ $Abono->cuota_cobrada}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>SALDO ANTERIOR</em></h4></td>
                            <td class="col-md-1 text-center">C$ {{ $Abono->saldo_anterior}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>TOTAL PAGO</em></h4></td>
                            <td class="col-md-1 text-center">C$ {{ $Abono->cuota_cobrada}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>USUARIO</em></h4></td>
                            <td class="col-md-1 text-center">{{Session::get('name_session')}}</td>
                        </tr>
                    </tbody>
                </table>
               </td>
            </div>
        </div>
    </div>