<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Crédito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .section-title {
            background-color: #d9edf7;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .signature {
            margin-top: 10px;
        }
        .signature-table {
            width: 100%;
            margin-top: 10px;
            border: none; /* Sin bordes */
        }
        .signature-table td {
            border: none; /* Sin bordes en celdas */
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: blue;">CREDINSTANTE</h2>
        <h3>Solicitud de crédito</h3>
    </div>

    <p><strong>SUCURSAL:</strong> __________________________</p>
    <p><strong>Nombre del oficial de crédito:</strong> __________________________</p>

    <table>
        <tr>
            <td colspan="2" class="section-title">I. Datos Generales del Cliente</td>
            <td>No. De cliente</td>
            <td>Fecha</td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Ciclo del crédito solicitado:</strong> 
                <label><input type="checkbox"> Semanal</label> 
                <label><input type="checkbox"> Quincenal</label>
            </td>
        </tr>
        <tr>
            <td>Nombres</td>
            <td>Apellidos</td>
            <td>No cédula</td>
            <td>Municipio</td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Barrio y dirección:</strong> ____________________________________________
            </td>
        </tr>
        <tr>
            <td>Teléfono</td>
            <td>Celular</td>
            <td colspan="2">
                <strong>Sexo:</strong> 
                <label><input type="checkbox"> F</label> 
                <label><input type="checkbox"> M</label>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>Estado civil:</strong> 
                <label><input type="checkbox"> Soltero(a)</label> 
                <label><input type="checkbox"> Casado(a)</label> 
                <label><input type="checkbox"> Viudo(a)</label>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">II. Información del Negocio</td>
        </tr>
        <tr>
            <td>Dirección del negocio</td>
            <td>Teléfono</td>
        </tr>
        <tr>
            <td>
                <strong>Propiedad:</strong> 
                <label><input type="checkbox"> Propio</label> 
                <label><input type="checkbox"> Familiar</label>
            </td>
            <td>Antigüedad</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="section-title">III. Referencias Personales</td>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">IV. Datos del Cónyuge</td>
        </tr>
        <tr>
            <td>Nombres y Apellidos</td>
            <td>No de cédula</td>
        </tr>
        <tr>
            <td colspan="2">Nombre y dirección donde trabaja</td>
        </tr>
        <tr>
            <td colspan="2">Teléfono</td>
        </tr>
    </table>

    <p class="signature">
        Confieso que la información antes suministrada es verídica y autorizo a CREDINSTANTE o quien sea el acreedor del crédito para que informe o divulgue a TRANSUNION, S.A. cualquier información relevante para evaluar mi desempeño como deudor.
    </p>

    <div class="signature">
        <table class="signature-table">
            <tr>
                <td>
                    <p>________________________</p>
                    <p>FIRMA DEL CLIENTE / FIADOR</p>
                </td>
                <td>
                    <p>________________________</p>
                    <p>OFICIAL DE CRÉDITO</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
