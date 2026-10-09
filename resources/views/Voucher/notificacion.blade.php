<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">

<!------ Include the above in your HEAD tag ---------->
<style>
    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
    padding: 0 8px; /* Add 3px padding to the right and left */
    line-height: 1.428571429;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
    .ntf-titulo { font-size: 22px; font-weight: bold; margin: 10px 0 2px; }
    .ntf-numero { font-size: 16px; margin-bottom: 8px; }
    .ntf-comentario { font-size: 20px; line-height: 1.4; padding: 6px 8px !important; }
</style>

@php
    // Formato amigable: Sábado, 10 Oct. 2026
    $FechaAmigable = function ($Fecha) {
        if (is_null($Fecha)) return '-';
        $Dias  = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $Meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $t = strtotime($Fecha);
        return $Dias[date('w', $t)] . ', ' . date('j', $t) . ' ' . $Meses[date('n', $t) - 1] . '. ' . date('Y', $t);
    };
@endphp


<div class="">
    <div class="">
        <div class="">

            <div class="">
                <div class="text-center">
                    <h3><img src="{{ asset('img/logo-gris.jpg') }}" alt="Logo" class="mt-3" style="opacity: .8" width="70" height="80"></h3>
                    <h3>CREDIN$TANTE</h3>
                    <h3>JINOTEPE - CARAZO</h3>
                    <h4><strong>DE PALI 2C. SUR 20 VRS OESTE.</strong></h4>
                    <h4>TELEF: 2533 - 6439</h4>
                    <h4>CEL: 7553 - 2435 </h4>
                    <div class="ntf-titulo">NOTIFICACIÓN DE COBRO</div>
                    <div class="ntf-numero">No {{ sprintf('%04d', $Notificacion->id_notificacion) }}</div>
                </div>
                <table class="table table-hover">

                    <tbody>
                        <tr>
                            <td class="col-md-9"><em>FECHA VISITA:</em></td>
                            <td class="col-md-1 text-right">{{ $FechaAmigable($Notificacion->fecha_visita) }}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>HORA VISITA:</em></td>
                            <td class="col-md-1 text-right">{{ date('h:i A', strtotime($Notificacion->hora_visita)) }}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>NOMBRE:</em></td>
                            <td class="col-md-1 text-right">{{ $Notificacion->Cliente->nombre }} {{ $Notificacion->Cliente->apellidos }}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>COD:</em></td>
                            <td class="col-md-1 text-right">{{ sprintf('%04d', $Notificacion->id_clientes) }}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>MONTO ATRASADO:</em></td>
                            <td class="col-md-1 text-right">C$ {{ number_format($Notificacion->monto_atrasado, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>PROXIMA VISITA:</em></td>
                            <td class="col-md-1 text-right">{{ $FechaAmigable($Notificacion->fecha_proxima) }}</td>
                        </tr>
                        @if(!empty($Notificacion->comentario))
                        <tr>
                            <td colspan="2"><em>COMENTARIO:</em><div class="ntf-comentario">{{ $Notificacion->comentario }}</div></td>
                        </tr>
                        @endif
                        <tr>
                            <td></td>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em>USUARIO:</em></td>
                            <td class="col-md-1 text-right">{{ $Notificacion->Usuario->nombre }}</td>
                        </tr>
                        <tr>
                            <td class="col-md-9"><em></em></td>
                            <td class="col-md-1 text-right">{{ $Notificacion->Usuario->Phone }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
