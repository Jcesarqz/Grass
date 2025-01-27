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
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->route('productos.index');
    }

    // Mostrar todos los productos con paginación y filtrado
    public function index(Request $request)
    {
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
