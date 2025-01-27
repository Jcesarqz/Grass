<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Reserva;
use Carbon\Carbon;
use PDF;

class ReporteController extends Controller
{
    // Mostrar formulario para seleccionar el reporte
    public function index()
    {
        return view('reportes.index');
    }

    // Generar el reporte en PDF
    public function generate(Request $request)
    {
        // Validar que la fecha y el tipo de reporte sean correctos
        $request->validate([
            'fecha' => 'required', // Validar que la fecha sea válida
            'tipo_reporte' => 'required|string', // Tipo de reporte
        ]);

        // Convertir la fecha seleccionada a Carbon y formatearla a solo fecha (d/m/Y)
        $fecha = Carbon::parse($request->fecha)->format('d/m/Y');
        $tipoReporte = $request->tipo_reporte;

        // Filtrar las ventas y reservas según el tipo de reporte (día, mes, año)
        if ($tipoReporte == 'dia') {
            // Obtener ventas y reservas para un solo día
            $ventas = Venta::whereDate('fecha', $request->fecha)->get();
            $reservas = Reserva::whereDate('fecha', $request->fecha)->get();
        } elseif ($tipoReporte == 'mes') {
            // Obtener ventas y reservas para un mes específico
            $ventas = Venta::whereMonth('fecha', Carbon::parse($request->fecha)->month)->get();
            $reservas = Reserva::whereMonth('fecha', Carbon::parse($request->fecha)->month)->get();
        } elseif ($tipoReporte == 'año') {
            // Obtener ventas y reservas para un año específico
            $ventas = Venta::whereYear('fecha', $request->fecha)->get();
            $reservas = Reserva::whereYear('fecha', $request->fecha)->get();
        }

        // Asegurarse de que las fechas de las ventas y reservas sean instancias de Carbon
        foreach ($ventas as $venta) {
            $venta->fecha = Carbon::parse($venta->fecha);
        }

        foreach ($reservas as $reserva) {
            $reserva->fecha = Carbon::parse($reserva->fecha);
            $reserva->hora_inicio = Carbon::parse($reserva->hora_inicio);
        }

        // Calcular los totales
        $totalVentas = $ventas->sum('total');
        $totalReservas = $reservas->sum('total');
        $totalGeneral = $totalVentas + $totalReservas;

        // Sanitizar el nombre del archivo para evitar caracteres no permitidos
        $fileName = "reporte_" . str_replace('/', '-', $fecha) . ".pdf"; // Reemplazar / por -

        // Generar el PDF
        $pdf = PDF::loadView('reportes.pdf', compact('ventas', 'reservas', 'totalVentas', 'totalReservas', 'totalGeneral', 'fecha'));

        // Descargar el PDF con un nombre de archivo sin caracteres no permitidos
        return $pdf->download($fileName);
    }
}
