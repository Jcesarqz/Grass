<?php

namespace App\Http\Controllers;
use App\Models\Cliente;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    

    public function index(Request $request)
{
    $clientes = Cliente::latest()->get();

    // Datos para el resumen
    $totalClientes = $clientes->count();
    $clientesVip = $clientes->where('vip', true)->count();
    $puntosTotales = $clientes->sum('puntos');
    $comprasTotales = $clientes->sum('total_compras');

    // ✅ Si la solicitud pide XML
    if ($request->header('Accept') === 'application/xml' || $request->query('format') === 'xml') {

        $xml = new \SimpleXMLElement('<clientes/>');

        foreach ($clientes as $cliente) {
            $clienteXml = $xml->addChild('cliente');
            $clienteXml->addChild('id', $cliente->id);
            $clienteXml->addChild('dni', $cliente->dni);
            $clienteXml->addChild('nombre', $cliente->nombre);
            $clienteXml->addChild('puntos', $cliente->puntos);
            $clienteXml->addChild('total_compras', $cliente->total_compras);
            $clienteXml->addChild('vip', $cliente->vip ? 'true' : 'false');
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }

    // ✅ Vista HTML por defecto
    return view('clientes.index', compact(
        'clientes', 'totalClientes', 'clientesVip', 'puntosTotales', 'comprasTotales'
    ));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'dni' => 'required|digits:8|unique:clientes,dni',
        'nombre' => 'required|string|max:255',
        'puntos' => 'required|integer|min:0',
        'total_compras' => 'required|numeric|min:0',
    ]);

    // Crear el cliente
    $cliente = Cliente::create([
        'dni' => $request->dni,
        'nombre' => $request->nombre,
        'puntos' => $request->puntos,
        'total_compras' => $request->total_compras,
        'vip' => $request->has('vip'),
    ]);

    // Si se solicita XML
    if ($request->header('Accept') === 'application/xml' || $request->query('format') === 'xml') {
        $xml = new \SimpleXMLElement('<cliente/>');
        $xml->addChild('id', $cliente->id);
        $xml->addChild('dni', $cliente->dni);
        $xml->addChild('nombre', $cliente->nombre);
        $xml->addChild('puntos', $cliente->puntos);
        $xml->addChild('total_compras', $cliente->total_compras);
        $xml->addChild('vip', $cliente->vip ? 'true' : 'false');

        return response($xml->asXML(), 201)->header('Content-Type', 'application/xml');
    }

    // Por defecto: redirigir con mensaje
    return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:clientes,dni,' . $cliente->id,
            'nombre' => 'required|string|max:255',
            'puntos' => 'required|integer|min:0',
            'total_compras' => 'required|numeric|min:0',
        ]);

        $cliente->update([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'puntos' => $request->puntos,
            'total_compras' => $request->total_compras,
            'vip' => $request->has('vip'),
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

}
