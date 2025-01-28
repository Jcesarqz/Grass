<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas y Reservas - {{ $fecha }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1, h2, p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
        }
        .container {
            margin: 0;
            padding: 10px;
        }
        .celda_centered {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Reporte de Ventas y Reservas - {{ $fecha }}</h1>

    <!-- Reporte de Ventas -->
    <h2>Ventas</h2>
    <table>
        <thead>
            <tr>
                <th class="celda_centered">Código</th>
                <th class="celda_centered">Fecha</th>
                <th class="celda_centered">Hora</th>
                <th class="celda_centered">Total</th>
                <th class="celda_centered">Productos Vendidos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->codigo }}</td>
                    <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                    <td>{{ $venta->fecha->format('H:i:s') }}</td>
                    <td>S/. {{ number_format($venta->total, 2) }}</td>
                    <td>
                        <ul>
                            @foreach($venta->productos as $producto)
                                <li>{{ $producto->nombre }} ({{ $producto->pivot->cantidad_vendida }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total de Ventas: S/. {{ number_format($totalVentas, 2) }}</p>

    <!-- Reporte de Reservas -->
    <h2>Reservas</h2>
    <table>
        <thead>
            <tr>
                <th class="celda_centered">Fecha</th>
                <th class="celda_centered">Hora Inicio</th>
                <th class="celda_centered">Hora Fin</th>
                <th class="celda_centered">Duración</th>
                <th class="celda_centered">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                    <td>{{ $reserva->hora_inicio->format('H:i:s') }}</td>
                    <td>{{ $reserva->hora_fin }}</td>
                    <td>{{ $reserva->duracion * 60 }} min</td>
                    <td>S/. {{ number_format($reserva->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total de Reservas: S/. {{ number_format($totalReservas, 2) }}</p>

    <!-- Total General -->
    <h2>Total General</h2>
    <p class="total">S/. {{ number_format($totalGeneral, 2) }}</p>
</body>
</html>