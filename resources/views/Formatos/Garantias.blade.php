<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato para Impresión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }
        .container {
            width: 100%;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }
        .header {
            background-color: #8faadc;
            text-align: center;
            padding: 10px;
            font-size: 18px;
            color: white;
            font-weight: bold;
        }
        .section {
            margin-top: 20px;
        }
        .section-title {
            background-color: #d4ebd4;
            padding: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 3px;
            text-align: left;
        }
        table th {
            background-color: #d4ebd4;
        }
        .line {
            display: inline-block;
            width: 90%;
            border-bottom: 1px solid black;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Detalle de Garantías -->
        <div class="header">DETALLE DE GARANTÍAS - CREDINSTANTE</div>
        <div class="section">
            <p>
                Las garantías mobiliarias para garantizar el pago del crédito en ausencia u omisión total o parcial del mismo por parte del deudor / fiador manifiesta el Sr (a)
                <span class="line">Lorem Ipsum, Lorem Ipsum</span>.
            </p>
            <p>
                Que por su voluntad constituye a favor de CREDINSTANTE las garantías mobiliarias en su calidad de titular de los bienes que se encuentran en su posesión y se detallan a continuación.
            </p>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Detalle del artículo</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Valor recomendado</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td><span class="line">Lorem Ipsum, Lorem</span></td>
                            <td><span class="line">Lorem Ipsum, Lorem</span></td>
                            <td><span class="line">Lorem Ipsum, Lorem</span></td>
                            <td><span class="line">Lorem Ipsum, Lorem</span></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Solicitud de Crédito -->
        <div class="section">
            <div class="header">SOLICITUD DE CRÉDITO</div>
            <table>
                <tr>
                    <td>FECHA:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                    <td>MONTO:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
                <tr>
                    <td>PLAZO:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                    <td>SEMANA:</td>
                    <td>
                        <span class="line">Lorem Ipsum, Lorem Ipsum</span>
                    </td>
                </tr>
                <tr>
                    <td>NO. CUOTAS:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                    <td>TASA DE INTERÉS:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
            </table>
        </div>

        <!-- Resolución del Comité de Crédito -->
        <div class="section">
            <div class="header">RESOLUCIÓN DEL COMITÉ DE CRÉDITO</div>
            <table>
                <tr>
                    <td>MONTO:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
                <tr>
                    <td>PLAZO:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
                <tr>
                    <td>TASA DE INTERÉS:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
                <tr>
                    <td>CUOTA RECOMENDADA:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
                <tr>
                    <td>OFICIAL DE CRÉDITO:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum </span></td>
                </tr>
                <tr>
                    <td>COORDINADOR DE SUCURSAL:</td>
                    <td><span class="line">Lorem Ipsum, Lorem Ipsum</span></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
