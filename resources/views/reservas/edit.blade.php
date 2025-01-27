@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Reserva</h1>

        <!-- Mostrar mensaje de error en caso de conflicto de reserva -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Mostrar mensaje de éxito -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('reservas.update', $reserva->id) }}" method="POST" class="mx-auto" style="max-width: 600px;">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" value="{{ $reserva->fecha }}">
            </div>

            <div class="form-group">
                <label for="hora_inicio">Hora de inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control form-control-sm" value="{{ $reserva->hora_inicio }}">
            </div>

            <div class="form-group">
                <label for="duracion">Duración (horas)</label>
                <input type="number" step="0.1" name="duracion" id="duracion" class="form-control form-control-sm" value="{{ $reserva->duracion }}">
            </div>

            <div class="form-group">
                <label for="precio">Precio por hora</label>
                <input type="number" name="precio" id="precio" class="form-control form-control-sm" value="{{ $reserva->precio }}">
            </div>

            <div class="form-group">
                <label for="total">Total</label>
                <input type="text" name="total" id="total" class="form-control form-control-sm" readonly value="{{ $reserva->total }}">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary w-48">Actualizar Reserva</button>
                <a href="{{ route('reservas.index') }}" class="btn btn-secondary w-48" style="background-color: #6c757d; border-color: #6c757d;">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Actualización del total cuando cambia la duración o el precio
        document.getElementById('duracion').addEventListener('input', function () {
            calculateTotal(this.value);
        });

        document.getElementById('precio').addEventListener('input', function () {
            const duracion = document.getElementById('duracion').value;
            calculateTotal(duracion);
        });

        function calculateTotal(duracion) {
            const precio = document.getElementById('precio').value;
            const total = precio * duracion;
            document.getElementById('total').value = total ? total.toFixed(2) : '';
        }

        // Inicializar cálculo total
        calculateTotal(document.getElementById('duracion').value);
    </script>
@endsection
