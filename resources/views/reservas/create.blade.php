@extends('layouts.app')

@section('content')
    <div class="container mt-4 d-flex justify-content-center">
        <div class="card p-4 shadow-lg border-0" style="max-width: 360px; background: linear-gradient(145deg, #ffffff, #e6e6e6); border-radius: 12px;">
            <h2 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; color: #34495e; font-weight: 600; letter-spacing: 0.5px;">
                Reservar Cancha
            </h2>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 0.75rem;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('reservas.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-12">
                    <label for="fecha" class="form-label text-secondary fw-semibold">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" required>
                </div>

                <div class="col-12">
                    <label for="hora_inicio" class="form-label text-secondary fw-semibold">Hora</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control form-control-sm" required>
                </div>

                <div class="col-6">
                    <label for="duracion" class="form-label text-secondary fw-semibold">Duración (h)</label>
                    <input type="number" step="0.1" name="duracion" id="duracion" class="form-control form-control-sm" required>
                </div>

                <div class="col-6">
                    <label for="precio" class="form-label text-secondary fw-semibold">Precio/hora</label>
                    <input type="number" name="precio" id="precio" class="form-control form-control-sm" value="60" required>
                </div>

                <div class="col-12">
                    <label for="total" class="form-label text-secondary fw-semibold">Total</label>
                    <input type="text" name="total" id="total" class="form-control form-control-sm bg-light" readonly>
                </div>

                <div class="col-12 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow">Reservar</button>
                    <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary btn-sm px-4 shadow">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        calculateTotal(document.getElementById('duracion').value);
    </script>
@endsection

@section('styles')
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #95a5a6;
            --light-bg: #ecf0f1;
            --card-bg: #f8f9fa;
            --font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            font-family: var(--font-family);
            color: #34495e;
        }

        .form-control-sm {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
        }

        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-outline-secondary {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .btn-outline-secondary:hover {
            background-color: var(--secondary-color);
            color: #fff;
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 1rem;
            }

            .btn {
                width: 100%;
            }

            .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection
