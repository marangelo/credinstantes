<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAGARÉ A LA ORDEN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 20px;
            font-size: 14px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .form-section {
            flex-grow: 1;
            margin-left: 20px;
        }
        .form-section p {
            margin: 5px 0;
        }
        .signature {
            margin-top: 30px;
        }
        .section { margin-top: 20px; }
        .signature-line { border-bottom: 1px solid black; width: 100px; display: inline-block; }
        .info { margin-bottom: 10px; }
        .flex { display: flex; justify-content: space-between; }
        .columns { display: flex; justify-content: space-between; }
        .column { width: 48%; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('img/Logo.png') }}" alt="Credinstante">
        <div class="form-section">
            <p><strong>ENTREGA:</strong> {{ Date::parse($Credito->fecha_apertura)->format('l, j F  Y') }}</p>
            <p><strong>MONTO: C$.</strong> {{ number_format($Credito->monto_credito,2) }} </p>
            <p><strong>DÍA DE VISITA:</strong> {{$Credito->getDiasSemana->dia_semana}}</p>
            <p><strong>DIRECCION:</strong> {{$Credito->Clientes->direccion_domicilio}}</p>
            <p><strong>TELEFONO:</strong> {{$Credito->Clientes->telefono}}</p>
        </div>
    </div>

    <p>
        @php $estados_civiles = ['N/D', 'Soltero(a)', 'Casado(1)', 'Divorciado(a)', 'Viudo(a)', 'Unión libre']; @endphp
        @php $estado_civil = $estados_civiles[$Credito->Clientes->estado_civil]; @endphp
        
        Yo <u><strong>{{$Credito->Clientes->nombre}} {{$Credito->Clientes->apellidos}}</strong></u>, mayor de edad, <strong>( {{$estado_civil}} )</strong>, 
        <strong>(________)</strong>, identificado con <strong>________________________</strong> número 
        <strong><u>{{$Credito->Clientes->cedula}}</u></strong>, y de este domicilio, actuando en nombre y representación propia, 
        en adelante denominado <strong>EL DEUDOR</strong>, por este <strong>PAGARÉ A LA ORDEN</strong>, me obligo a pagar a 
        <strong>CREDINSTANTE</strong>, o a su orden en esta ciudad, en sus oficinas principales o en cualquier otra de 
        sus sucursales o lugar donde sea designado, por su cuenta y riesgo y por igual valor recibido a mi satisfacción, 
        la suma de <strong><u>{{$Credito->numberToWords($Credito->monto_credito)}}</u></strong> ( <u><strong> C$ {{ number_format($Credito->monto_credito,2) }}</strong></u> ), cantidad que pagaré en un plazo de 
        <strong>____<u>{{number_format($Credito->plazo,0)}}____</u></strong> meses en <strong>____<u>{{number_format($Credito->numero_cuotas,0)}}____</u></strong> cuotas.
    </p>

    <p>
        Las fechas de pago de cada cuota y el monto de las mismas son establecidas en el calendario de pago (plan) de pagos 
        que se emite de forma simultánea a este pagaré, y que es firmado y rubricado por mí en calidad de DEUDOR.
    </p>

    <p>
        Sobre la suma recibida en este acto, en nombre propio reconozco desde la fecha hasta su efectivo pago, 
        una tasa de interés fija del <strong>________%</strong> mensual sobre saldo. En caso de mora, 
        <strong>EL DEUDOR</strong> se obliga a pagar a <strong>CREDINSTANTE</strong> un interés moratorio adicional del 
        <strong>________%</strong> de la tasa de interés corriente pactada, sobre el saldo deudor hasta el total y efectivo 
        pago de todo lo adeudado.
    </p>

    <p>
        El presente pagaré avala como causal, se considera parte integrante e indivisible de la solicitud de crédito antes 
        relacionado y, por tanto, como una sola unidad jurídica, y tiene como fin la comprobación del otorgamiento efectivo 
        del préstamo o desembolso de fondos del solicitante a cuenta del crédito antes referido.
    </p>

    <div class="container">
        <p>
            Firmo este pagaré a la orden en la ciudad de <strong><u>____{{ $Credito->Clientes->getZona->nombre_zona }}____</u></strong>, a los <strong><u>____{{date('d', strtotime($Credito->fecha_apertura))}}____</u></strong> 
            días del mes de <strong><u>____{{ Date::parse($Credito->fecha_apertura)->format('F')}}____</u></strong> del año <strong><u>____{{date('Y', strtotime($Credito->fecha_apertura))}}____</u></strong>
        </p>
        
        <div class="columns">
            <div class="column">
                <div class="info"><strong>EL DEUDOR:</strong> _______________________________________</span></div>
                <div class="info"><strong>Cédula de identidad:</strong> __________________________________</span></div>
            </div>
            <div class="column">
                <div class="info"><strong>Fiador:</strong> _______________________________________</span></div>
                <div class="info"><strong>Cédula:</strong> _______________________________________</span></div>
            </div>
        </div>
    </div>

</body>
</html>