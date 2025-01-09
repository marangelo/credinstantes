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
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('img/logo.png') }}" alt="Credinstante">
        <div class="form-section">
            <p>ENTREGA: ____________________________</p>
            <p>DÍA DE VISITA: ____________________________</p>
            <p>MONTO: ____________________________</p>
            <p>VENCIMIENTO: ____________________________</p>
        </div>
    </div>

    <p>
        Yo <strong>________________________</strong>, mayor de edad, <strong>(________)</strong>, 
        <strong>(________)</strong>, identificado con <strong>________________________</strong> número 
        <strong>________________________</strong>, y de este domicilio, actuando en nombre y representación propia, 
        en adelante denominado <strong>EL DEUDOR</strong>, por este <strong>PAGARÉ A LA ORDEN</strong>, me obligo a pagar a 
        <strong>CREDINSTANTE</strong>, o a su orden en esta ciudad, en sus oficinas principales o en cualquier otra de 
        sus sucursales o lugar donde sea designado, por su cuenta y riesgo y por igual valor recibido a mi satisfacción, 
        la suma de <strong>________________________</strong> (__________), cantidad que pagaré en un plazo de 
        <strong>________</strong> meses en <strong>________</strong> cuotas.
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

    <div class="signature">
        <p>Firmo este pagaré a la orden en la ciudad de __________________________, a los ______ días del mes de 
        __________________________ del año ______.</p>
        <p>
            <strong>EL DEUDOR:</strong><br>
            __________________________<br>
            Cédula de identidad: __________________________<br>
        </p>
    </div>
</body>
</html>