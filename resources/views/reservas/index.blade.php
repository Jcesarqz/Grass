@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <!-- Título con Raya Separadora -->
        <div class="text-center mb-4">
            <h1 class="text-black font-weight-bold" style="font-family: 'Poppins', sans-serif; font-size: 2.5rem; text-transform: capitalize; letter-spacing: 1px;">Listado de Reservas</h1>
            <hr class="my-4" style="border: 3px solid #007bff; width: 70%; margin: auto;"/>
        </div>

        <!-- Calendario con mes de Enero 2025 -->
        <div id="calendar" class="mb-5"></div>

        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet" />

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    timeZone: 'America/Lima',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                        prev: '◁',
                        next: '▷',
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                    initialDate: '2025-01-01',
                    events: {!! json_encode($reservas->map(function ($reserva) {
                        return [
                            'title' => ' - ' .  \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') . ' R',
                            'start' => $reserva->fecha . 'T' . $reserva->hora_inicio,
                            //$reserva->fecha . 'T' . \Carbon\Carbon::parse($reserva->hora_inicio)->addMinutes($reserva->duracion * 60)->format('H:i'),
                        ];
                    })) !!},
                    dateClick: function(info) {
                        window.location.href = '/reservas?fecha=' + info.dateStr;
                    }
                });
                calendar.render();
            });
        </script>

        <!-- Botón de Programar Nueva Reserva -->
        <div class="text-center mb-4">
            <button class="btn btn-primary rounded-pill shadow-sm px-4 py-2 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalCreateReserva">
                <span style="font-size: 1.25rem;">Programar Nueva Reserva</span>
            </button>
        </div>

        <hr class="my-5" style="border: 2px solid #007bff; width: 70%; margin: auto;"/>

        <!-- Mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success shadow-lg rounded-lg mb-4 p-3">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger shadow-lg rounded-lg mb-4 p-3">
                {{ session('error') }}
            </div>
        @endif

        <!-- Reservas Pendientes -->
        <div class="my-5">
            <h2 class="text-center text-muted mb-4" style="font-size: 2.25rem; font-weight: 600;">Reservas Pendientes</h2>
            <div class="table-responsive">
                <table class="table table-modern table-striped table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Fin</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            @if ($reserva->estado == 'pendiente')
                                <tr class="hover-shadow">
                                    <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d-m-Y') }}</td>
                                    <td>{{ $reserva->hora_inicio }}</td>
                                    <td>{{ $reserva->hora_fin }}</td>
                                    <td>
                                        @php
                                            $horas = floor($reserva->duracion); // Parte entera (horas)
                                            $minutos = ($reserva->duracion - $horas) * 60; // Parte decimal convertida a minutos
                                        @endphp

                                        @if ($horas > 0 && $minutos > 0)
                                            {{ $horas }} {{ $horas == 1 ? 'hora' : 'Horas' }} y {{ $minutos }} min
                                        @elseif ($horas > 0)
                                            {{ $horas }} {{ $horas == 1 ? 'hora' : 'Horas' }}
                                        @elseif ($minutos > 0)
                                            {{ $minutos }} min
                                        @else
                                            Sin duración
                                        @endif
                                    </td>
                                    <td>S/. {{ number_format($reserva->precio, 2) }}</td>
                                    <td>S/. {{ number_format($reserva->total, 2) }}</td>
                                    <td class="text-center text-white bg-warning font-weight-bold rounded">
                                        Pendiente
                                    </td>
                                    <td>
                                        <form action="{{ route('reservas.pagar', $reserva->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-2">Pagar</button>
                                        </form>
                                        <a href="#" class="btn btn-sm btn-warning rounded-pill px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalEditReserva{{ $reserva->id }}">Editar</a>
                                        <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-2">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal para editar reserva -->
                                <div class="modal fade" id="modalEditReserva{{ $reserva->id }}" tabindex="-1" aria-labelledby="modalEditReservaLabel{{ $reserva->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditReservaLabel{{ $reserva->id }}">Editar Reserva</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('reservas.update', $reserva->id) }}" method="POST" class="d-inline" id="formEditReserva{{ $reserva->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="fecha" class="form-label">Fecha</label>
                                                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $reserva->fecha }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="{{ $reserva->hora_inicio }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="duracion" class="form-label">Duración (Horas)</label>
                                                        <input type="number" class="form-control" id="duracion{{ $reserva->id }}" name="duracion" value="{{ $reserva->duracion }}" min="0.5" step="0.5" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="precio" class="form-label">Precio</label>
                                                        <input type="number" class="form-control" id="precio{{ $reserva->id }}" name="precio" value="60" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100">Actualizar Reserva</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reservas Pagadas -->
        <div class="my-5">
            <h2 class="text-center text-muted mb-4" style="font-size: 2.25rem; font-weight: 600;">Reservas Pagadas</h2>
            <div class="table-responsive">
                <table class="table table-modern table-striped table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora de Inicio</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            @if ($reserva->estado == 'pagada')
                                <tr class="hover-shadow">
                                    <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d-m-Y') }}</td>
                                    <td>{{ $reserva->hora_inicio }}</td>
                                    <td>{{ $reserva->duracion == 0.5 ? 'Media Hora' : ($reserva->duracion == 1 ? '1 Hora' : $reserva->duracion . ' Horas') }}</td>
                                    <td>S/. {{ number_format($reserva->precio, 2) }}</td>
                                    <td>S/. {{ number_format($reserva->total, 2) }}</td>
                                    <td class="text-center text-white bg-success font-weight-bold rounded">
                                        Pagada
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para Programar Nueva Reserva -->
        <div class="modal fade" id="modalCreateReserva" tabindex="-1" aria-labelledby="modalCreateReservaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateReservaLabel">Programar Nueva Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('reservas.store') }}" method="POST" id="createReservaForm">
                            @csrf
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                            </div>
                            <div class="mb-3">
                                <label for="duracion" class="form-label">Duración (Horas)</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" value="1" min="0.5" step="0.5" required>
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="precio" name="precio" value="60" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Programar Reserva</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Enviar el formulario de edición automáticamente al cambiar un campo
            document.querySelectorAll('form[id^="formEditReserva"]').forEach(form => {
                form.addEventListener('change', function () {
                    this.submit();
                });
            });
        });
    </script>
@endsection
