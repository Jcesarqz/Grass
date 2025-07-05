<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
         if ($request->header('Accept') === 'application/xml' || $request->query('format') === 'xml') {
        $clientes = Cliente::count();
        $ventas = Venta::count();
        $reservas = Reserva::count();

        $xml = new \SimpleXMLElement('<dashboard/>');
        $xml->addChild('total_clientes', $clientes);
        $xml->addChild('total_ventas', $ventas);
        $xml->addChild('total_reservas', $reservas);

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
        }
        $ventasTotales = Venta::sum('total');

        $productosVendidos = DB::table('producto_venta')->sum('cantidad_vendida');

        $clientesRegistrados = Cliente::count();

        $reservasHoy = Reserva::whereDate('fecha', today())->count();
        $reservasPendientes = Reserva::whereDate('fecha', today())->where('estado', 'pendiente')->count();

       $rankingBruto = DB::table('producto_venta')
            ->select('producto_id', DB::raw('SUM(cantidad_vendida) as total_ventas'))
            ->groupBy('producto_id')
            ->orderByDesc('total_ventas')
            ->take(5)
            ->get();

        $ranking = $rankingBruto->map(function ($item) {
            $producto = Producto::find($item->producto_id);
            if ($producto) {
                $producto->total_ventas = $item->total_ventas;
                return $producto;
            }
            return null;
        })->filter(); // eliminar nulls

        $productosStockBajo = Producto::where('cantidad', '<=', 15)->get();

        return view('dashboard.index', compact(
            'ventasTotales', 'productosVendidos', 'clientesRegistrados',
            'reservasHoy', 'reservasPendientes', 'ranking', 'productosStockBajo'
        ));
    }
}
