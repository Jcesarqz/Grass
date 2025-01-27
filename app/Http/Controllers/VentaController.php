<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VentaController extends Controller
{
    // Método para mostrar la lista de productos y ventas
    public function index(Request $request)
    {
        $terminoBusqueda = $request->input('search', '');

        // Buscar productos por nombre
        $productos = Producto::where('nombre', 'LIKE', "%{$terminoBusqueda}%")->paginate(10);

        // Buscar ventas relacionadas con los productos que coinciden
        $ventas = Venta::with('productos')
            ->whereHas('productos', function ($query) use ($terminoBusqueda) {
                $query->where('nombre', 'LIKE', "%{$terminoBusqueda}%");
            })
            ->paginate(10);

        return view('ventas.index', compact('productos', 'ventas', 'terminoBusqueda'));
    }

    // Método para añadir un producto al carrito
    public function addToCart($id)
    {
        $producto = Producto::findOrFail($id); // Obtén el producto o muestra un error 404
        $carrito = session()->get('cart', []); // Obtener el carrito actual

        if (isset($carrito[$id])) {
            // Si el producto ya está en el carrito, no hacer nada más
            return redirect()->route('ventas.index')->with('success', 'Producto ya está en el carrito');
        } else {
            // Si el producto no está en el carrito, añadirlo con cantidad 1
            $carrito[$id] = [
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'cantidad' => 1,
                'total' => $producto->precio,
            ];
        }

        session()->put('cart', $carrito); // Guardar el carrito en la sesión

        return redirect()->route('ventas.index')->with('success', 'Producto añadido al carrito');
    }

    // Método para actualizar el carrito
    public function updateCart(Request $request)
    {
        $carrito = session()->get('cart', []); // Obtener el carrito actual

        // Asegurarnos de que las cantidades se pasen correctamente
        if (!$request->has('cantidad') || !is_array($request->cantidad)) {
            return redirect()->route('ventas.index')->with('error', 'No se han recibido datos válidos para actualizar el carrito.');
        }

        // Iterar sobre las cantidades solicitadas y actualizar el carrito
        foreach ($request->cantidad as $id => $cantidadSolicitada) {
            $producto = Producto::find($id);

            // Validar que el producto existe
            if (!$producto) {
                return redirect()->route('ventas.index')->with('error', 'Producto no encontrado');
            }

            // Validar que la cantidad solicitada no sea mayor que la disponible
            $cantidadSolicitada = (int)$cantidadSolicitada;
            if ($cantidadSolicitada > $producto->cantidad) {
                return redirect()->route('ventas.index')->with('error', 'No hay suficiente stock disponible para el producto ' . $producto->nombre);
            }

            if (isset($carrito[$id])) {
                // Actualizar la cantidad y el total
                $carrito[$id]['cantidad'] = $cantidadSolicitada;
                $carrito[$id]['total'] = $carrito[$id]['precio'] * $cantidadSolicitada;
            }
        }

        session()->put('cart', $carrito); // Guardar el carrito actualizado en la sesión

        return redirect()->route('ventas.index')->with('success', 'Carrito actualizado');
    }

    // Método para eliminar un producto del carrito
    public function removeFromCart($id)
    {
        $carrito = session()->get('cart', []); // Obtener el carrito actual

        if (isset($carrito[$id])) {
            unset($carrito[$id]); // Eliminar el producto del carrito
        }

        session()->put('cart', $carrito); // Guardar los cambios en la sesión

        return redirect()->route('ventas.index')->with('success', 'Producto eliminado del carrito');
    }

    // Método para realizar la venta y almacenar los datos
    public function store(Request $request)
    {
        $carrito = session()->get('cart', []); // Obtener el carrito actual

        // Validar si el carrito está vacío
        if (empty($carrito)) {
            return redirect()->route('ventas.index')->with('error', 'El carrito está vacío, no se puede realizar la venta.');
        }

        // Calcular el total sumando los totales de cada producto en el carrito
        $total = array_sum(array_column($carrito, 'total'));

        // Crear la venta
        $venta = Venta::create([
            'codigo' => 'V' . str_pad(Venta::count() + 1, 4, '0', STR_PAD_LEFT),
            'fecha' => Carbon::now(),
            'total' => $total,
        ]);

        // Guardar los productos vendidos
        foreach ($carrito as $id => $producto) {
            $venta->productos()->attach($id, [
                'cantidad_vendida' => $producto['cantidad'],
                'total' => $producto['total'],
            ]);

            // Actualizar la cantidad del producto en el inventario
            $productoModel = Producto::find($id);
            $productoModel->cantidad -= $producto['cantidad']; // Restar la cantidad vendida
            $productoModel->save();
        }

        // Vaciar el carrito después de la compra
        session()->forget('cart');

        return redirect()->route('ventas.index')->with('success', 'Venta realizada con éxito');
    }
}
