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
            padding: 3px;
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
            text-align: center;
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
            text-align: center;
        }
        .form-section p {
            margin: 5px 0;
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
        
        
    </div>
    <div class="header">
        <img src="{{ asset('img/Logo.png') }}" alt="Credinstante">
        <div class="form-section">
            <h2 style="color: blue;">CREDINSTANTE</h2>
            <h3>Solicitud de crédito</h3>
        </div>
    </div>



    <table>
        <tr>
            <td colspan="4" class="section-title">DATOS GENERALES DEL CLIENTE</td>
        </tr>
        <tr>
            <td colspan="2">
                Ciclo del crédito solicitado: <strong>{{$Credito->Clientes->getCreditos->count()}}</strong>
                
            </td>
            <td colspan="2" style="text-align: right;">
                Fecha: <strong>{{ date('d-m-Y', strtotime($Credito->fecha_apertura)) }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">Nombres: <strong>{{$Credito->Clientes->nombre}}</strong> </td> 
        </tr>
        <tr>
            <td colspan="4">Apellidos: <strong>{{$Credito->Clientes->apellidos}}</strong></td>
        </tr>
        <tr>
            <td colspan="4">No cédula: <strong>{{$Credito->Clientes->cedula}}</strong></td>
        </tr>
        <tr>
            <td colspan="4">Municipio: <strong>{{$Credito->Clientes->getMunicipio->nombre_municipio}}</strong> </td>
        </tr>
        <tr>
            <td colspan="4">
                Dirección: <strong>{{$Credito->Clientes->direccion_domicilio}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">Teléfono: <strong>{{$Credito->Clientes->telefono}}</strong></td>
        </tr>
        
        <tr>
            <td colspan="4">
                @php $estados_civiles = ['N/D', 'Soltero(a)', 'Casado(a)', 'Divorciado(a)', 'Viudo(a)', 'Unión libre']; @endphp
                @php $estado_civil = $estados_civiles[$Credito->Clientes->estado_civil] ?? 'N/D' ; @endphp
                Estado civil: <strong>{{$estado_civil}}</strong>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">INFORMACION DEL NEGOCIO</td>
        </tr>
        <tr>
            <td colspan="2">Nombre del negocio: <strong>{{ $Credito->Clientes->getNegocio->nombre_negocio ?? '' }}</strong></td>
        </tr>
        <tr>
            <td colspan="2">Antigüedad del negocio: <strong>{{ $Credito->Clientes->getNegocio->antiguedad ?? ''}}</strong></td>
        </tr>
        <tr>
            <td colspan="2">Dirección del negocio: <strong>{{ $Credito->Clientes->getNegocio->direccion ?? ''}}</strong></td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="section-title">Referencias Personales</td>
        </tr>
        <tr>
            <th style="text-align: center;">NOMBRE</th>
            <th style="text-align: center;">TELEFONO</th>
            <th style="text-align: center;">DIRECCION</th>
        </tr>
        @foreach ($Credito->Clientes->getReferencias as $key => $ref)
        <tr>
            <td style="text-align: center;">{{ $ref->nombre_ref }}</td>
            <td style="text-align: center;">{{ $ref->telefono_ref }}</td>
            <td style="text-align: center;">{{ $ref->direccion_ref }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">Datos del Cónyuge</td>
        </tr>
        <tr>
            <td colspan="2">Nombres: <strong>{{ $Credito->Clientes->getConyugue->nombres ?? ''}}</strong> </td>
            
        </tr>
        <tr>
            <td colspan="2">Apellidos: <strong>{{ $Credito->Clientes->getConyugue->apellidos ?? ''}}</strong></td>
            
        </tr>
        <tr><td colspan="2">Cédula: <strong>{{ $Credito->Clientes->getConyugue->no_cedula ?? ''}}</strong></td></tr>
        <tr>
            <td colspan="2">Teléfono: <strong>{{ $Credito->Clientes->getConyugue->telefono?? ''}}</strong></td>
        </tr>
        <tr>
            <td colspan="2">Lugar de trabajo: <strong>{{ $Credito->Clientes->getConyugue->direccion_trabajo ?? ''}}</strong></td>
        </tr>
    </table>


    <table>
        <tr>
            <td colspan="4" class="section-title">DETALLE DE GARANTIAS.</td>
        </tr>
        <tr>
            <td colspan="4">
            Las garantías mobiliarias para garantizar el pago del crédito en ausencia u omisión total o parcial del mismo por parte
            del deudor / fiador manifiesta el Sr (a) <u><strong>{{ strtoupper($Credito->Clientes->nombre ?? '') }} {{ strtoupper($Credito->Clientes->apellidos ?? '') }}</strong></u>.
            Que por su voluntad constituye a favor de CREDINSTANTE las garantías mobiliarias en su calidad de titular de los
            bienes que se encuentran en su posesión y se detallan a continuación.
            </td>
        </tr>
        <tr>
            <th style="text-align: center;">DETALLE DEL ARICULO</th>
            <th style="text-align: center;">MARCA</th>
            <th style="text-align: center;">COLOR</th>
            <th style="text-align: center;">VALOR</th>
        </tr>
        @foreach ($Credito->Clientes->getGarantias as $key => $garantia)
        <tr>
            <td style="text-align: center;">{{ $garantia->detalle_articulo }}</td>
            <td style="text-align: center;">{{ $garantia->marca }}</td>
            <td style="text-align: center;">{{ $garantia->color }}</td>
            <td style="text-align: center;">{{ $garantia->valor_recomendado }}</td>
        </tr>
        @endforeach
    </table>

    <p class="signature">
        Confieso que la información antes suministrada, es verídica y expresamente autorizo a <strong>CREDINSTANTE</strong> o quien sea el
        acreedor del crédito que solicito para que informen, reporten o divulguen a <strong>TRANSUNION, S.A</strong> o a cualquier otro buro toda
        la información relevante para conocer mi desempeño como deudor, o para valorar el riesgo futuro para la concesión de
        crédito.
    </p>



    <table>
        <tr>
            <td colspan="2" class="section-title">solucitud de crédito</td>
        </tr>
        <tr>
            <td colspan="2">Fecha: <strong>{{ date('d-m-Y', strtotime($Credito->fecha_apertura)) }}</strong></td>
        </tr>
        <tr>
            <td colspan="2">Monto: <strong>C$. {{number_format( ($Credito->getRefResquest->getRequest->monto ?? 0) ,0)}}</strong></td>
            
        </tr>
        <tr>
            <td colspan="2">Plazo: <strong>{{number_format(($Credito->getRefResquest->getRequest->plazo ?? 0),0)}}</strong></td>
        </tr>
        <tr>
            <td colspan="2"> Tasa de Interés: <strong>{{number_format(( $Credito->getRefResquest->getRequest->interes_porcent ?? 0),2)}}</strong></td>
        </tr>
        <tr>
            <td colspan="2">No de cuotas: <strong>{{number_format( ($Credito->getRefResquest->getRequest->num_cuotas ?? 0),0)}}</strong></td>
        </tr>
        <tr>
            <td colspan="2">Cuotas: <strong>C$. {{ number_format( ($Credito->getRefResquest->getRequest->cuota ?? 0),2)}}</strong></td>
        </tr>
        <tr>
            <td colspan="2" class="section-title">RESOLUCION DEL COMITÉ DE CREDITO</td>
        </tr>
        <tr>
            <td colspan="2">Monto: <strong>C$. {{ number_format($Credito->monto_credito,0) }}</strong></td>
            
        </tr>
        <tr>
            <td colspan="2">Plazo: <strong>{{ number_format($Credito->plazo,0) }}</strong></td>
        </tr>
        <tr>
            <td colspan="2">Tasa de Interés: <strong>{{ number_format($Credito->taza_interes,2) }}</strong></td>
        </tr>
        <tr>
            <td colspan="2">No de cuotas: <strong>{{ number_format($Credito->numero_cuotas,0) }}</strong></td> 
        </tr>
        <tr>
            <td colspan="2">Cuotas: <strong>C$. {{ number_format($Credito->cuota,2) }}</strong></td>
        </tr>
    </table>
    

    <div class="signature">
        <table class="signature-table">
            <tr>
                <td>
                    <p>________________________</p>
                    <p>OFICIAL DECREDITO</p>
                </td>
                <td>
                    <p>________________________</p>
                    <p>COORDINADOR DE SUCURSAL</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
