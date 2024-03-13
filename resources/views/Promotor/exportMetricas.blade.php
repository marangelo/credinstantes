
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Estilos para la primera fila como texto en negrita */
        tr:first-child {
            font-weight: bold;
        }
        
        /* Estilos para una celda específica */
        td:nth-child(2) {
            font-style: italic;
        }

        /* Estilos para una columna completa */
        td:nth-child(3) {
            font-size: 16px;
        }
    </style>
</head>
<body>
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Origen</th>
        </tr>
    </thead>
    <tbody>
    @foreach($Clientes['LISTA_CLIENTES'] as $c)
        <tr>
            <td>{{ $c['id_clientes'] }}</td>
            <td>{{ $c['Nombre'] }}</td>
            <td>{{ $c['Fecha'] }}</td>
            <td>{{ $c['Monto'] }}</td>
            <td>{{ $c['Origen'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

