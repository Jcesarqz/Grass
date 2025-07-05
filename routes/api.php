<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Models\Cliente;

// Rutas para productos
Route::get('/productos', [ProductoController::class, 'index']); // ← Importante
Route::post('/productos', [ProductoController::class, 'store']);

// Ruta para obtener clientes en XML o JSON
Route::get('/clientes', function () {
    if (request()->wantsXml() || request()->query('format') === 'xml') {
        $clientes = Cliente::all();
        $xml = new \SimpleXMLElement('<clientes/>');
        foreach ($clientes as $cliente) {
            $clienteXml = $xml->addChild('cliente');
            $clienteXml->addChild('id', $cliente->id);
            $clienteXml->addChild('nombre', $cliente->nombre);
            $clienteXml->addChild('dni', $cliente->dni);
            $clienteXml->addChild('puntos', $cliente->puntos);
            $clienteXml->addChild('total_compras', $cliente->total_compras);
        }
        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }

    return Cliente::all(); // JSON por defecto
});
