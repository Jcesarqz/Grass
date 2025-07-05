<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Mostrar formulario para crear un nuevo producto
    public function create()
    {
        return view('productos.create');
    }

    // Guardar un nuevo producto
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'cantidad' => 'required|integer|min:0',
    ]);

    $producto = Producto::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'precio' => $request->precio,
        'cantidad' => $request->cantidad,
    ]);

    // Retornar XML si el request lo solicita
    if ($request->header('Accept') === 'application/xml' || $request->query('format') === 'xml') {
        $xml = new \SimpleXMLElement('<producto/>');
        $xml->addChild('id', $producto->id);
        $xml->addChild('nombre', $producto->nombre);
        $xml->addChild('descripcion', $producto->descripcion);
        $xml->addChild('precio', $producto->precio);
        $xml->addChild('cantidad', $producto->cantidad);

        return response($xml->asXML(), 201)->header('Content-Type', 'application/xml');
    }
    
    return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
}


    // Mostrar todos los productos con paginación y filtrado
    public function index(Request $request)
{
    $productos = Producto::latest()->get();

    // Permitir acceso sin login si se solicita XML
    if ($request->header('Accept') === 'application/xml' || $request->query('format') === 'xml') {
        $xml = new \SimpleXMLElement('<productos/>');

        foreach ($productos as $producto) {
            $productoXml = $xml->addChild('producto');
            $productoXml->addChild('id', $producto->id);
            $productoXml->addChild('nombre', $producto->nombre);
            $productoXml->addChild('descripcion', $producto->descripcion);
            $productoXml->addChild('precio', $producto->precio);
            $productoXml->addChild('cantidad', $producto->cantidad);
        }

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }

    // Requiere autenticación para vista HTML
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $searchTerm = $request->input('search', ''); // Recibe el término de búsqueda

    // Filtra los productos basados en el término de búsqueda
    $productos = Producto::where('nombre', 'LIKE', "%{$searchTerm}%")
                        ->paginate(10); // Puedes ajustar la paginación

    // Obtener ventas recientes
    $ventas = Venta::with('productos')->latest()->take(5)->get();

    return view('productos.index', compact('productos', 'ventas'));
}


    // Mostrar el formulario de edición
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    // Método para actualizar un producto
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
        ]);

        $producto = Producto::findOrFail($id);

        // Actualizar los campos del producto
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->route('productos.index');
    }

    
   
}
