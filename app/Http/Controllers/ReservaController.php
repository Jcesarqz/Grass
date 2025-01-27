<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Mostrar todas las reservas
    public function index()
    {
        // Obtener todas las reservas
        $reservas = Reserva::all();

        // Calcular el total de ingresos de las reservas pagadas
        $totalIngresos = Reserva::where('estado', 'pagada')->sum('total');

        return view('reservas.index', compact('reservas', 'totalIngresos'));
    }

    // Mostrar formulario de creación de reserva
    public function create()
    {
        return view('reservas.create');
    }

    // Almacenar una nueva reserva
    public function store(Request $request)
    {
        // Validar y almacenar la nueva reserva
        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion' => 'required|numeric',
            'precio' => 'required|numeric',
        ]);

        // Calcular el total de la reserva
        $total = $request->duracion * $request->precio;

        // Crear la nueva reserva
        Reserva::create([
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'duracion' => $request->duracion,
            'precio' => $request->precio,
            'total' => $total,
            'estado' => 'pendiente', // Reserva está pendiente inicialmente
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva programada correctamente.');
    }

    // Mostrar formulario de edición de reserva
    public function edit($id)
    {
        // Buscar la reserva por su ID
        $reserva = Reserva::findOrFail($id);

        // Retornar la vista de edición con los datos de la reserva
        return view('reservas.edit', compact('reserva'));
    }

    // Actualizar una reserva existente
    public function update(Request $request, $id)
    {
        // Validar los campos que pueden cambiar
        $request->validate([
            'fecha' => 'nullable|date',
            'hora_inicio' => 'nullable|date_format:H:i',
            'duracion' => 'nullable|numeric',
            'precio' => 'nullable|numeric',
        ]);

        // Buscar la reserva por su ID
        $reserva = Reserva::findOrFail($id);

        // Verificar qué campos se han modificado y actualizar solo esos
        $data = [];

        if ($request->has('fecha')) {
            $data['fecha'] = $request->fecha;
        }
        if ($request->has('hora_inicio')) {
            $data['hora_inicio'] = $request->hora_inicio;
        }
        if ($request->has('duracion')) {
            $data['duracion'] = $request->duracion;
        }
        if ($request->has('precio')) {
            $data['precio'] = $request->precio;
        }

        // Si se ha modificado la duración o el precio, recalcular el total
        if (isset($data['duracion']) || isset($data['precio'])) {
            $data['total'] = ($data['duracion'] ?? $reserva->duracion) * ($data['precio'] ?? $reserva->precio);
        }

        // Actualizar la reserva con los campos modificados
        $reserva->update($data);

        // Redirigir con mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    // Eliminar una reserva
    public function destroy($id)
    {
        // Eliminar la reserva
        Reserva::find($id)->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada.');
    }

    // Marcar una reserva como pagada
    public function pagar($id)
    {
        // Buscar la reserva por su ID
        $reserva = Reserva::find($id);

        // Marcar la reserva como pagada
        $reserva->estado = 'pagada';
        $reserva->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva pagada.');
    }
}
