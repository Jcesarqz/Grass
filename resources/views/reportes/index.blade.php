@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-5">Generar Reporte de Ventas y Reservas</h1>

        <!-- Contenedor para los botones -->
        <div class="d-flex justify-content-center mb-4 flex-wrap">
            <!-- Botón para seleccionar por Día -->
            <div class="card mx-3 my-2" style="width: 18rem; cursor: pointer; transition: transform 0.3s ease;" id="btnDia" onclick="cargarFormulario('dia')">
                <div class="card-body text-center">
                    <img src="https://img.icons8.com/ios/50/000000/calendar.png" alt="Día" class="mb-3" />
                    <h5 class="card-title">Por Día</h5>
                    <p class="card-text">Genera el reporte de ventas y reservas por un día específico.</p>
                </div>
            </div>

            <!-- Botón para seleccionar por Mes -->
            <div class="card mx-3 my-2" style="width: 18rem; cursor: pointer; transition: transform 0.3s ease;" id="btnMes" onclick="cargarFormulario('mes')">
                <div class="card-body text-center">
                    <img src="https://img.icons8.com/ios/50/000000/month-view.png" alt="Mes" class="mb-3" />
                    <h5 class="card-title">Por Mes</h5>
                    <p class="card-text">Genera el reporte de ventas y reservas por mes.</p>
                </div>
            </div>

            <!-- Botón para seleccionar por Año -->
            <div class="card mx-3 my-2" style="width: 18rem; cursor: pointer; transition: transform 0.3s ease;" id="btnAño" onclick="cargarFormulario('año')">
                <div class="card-body text-center">
                    <img src="https://img.icons8.com/ios/50/000000/clock.png" alt="Año" class="mb-3" />
                    <h5 class="card-title">Por Año</h5>
                    <p class="card-text">Genera el reporte de ventas y reservas por un año específico.</p>
                </div>
            </div>
        </div>

        <!-- Formulario de selección de fechas -->
        <form action="{{ route('reportes.generate') }}" method="POST" id="formReporte">
            @csrf
            <div class="form-group text-center" id="fechaDiv" class="d-flex justify-content-center">
                <!-- Aquí se cargarán los campos dependiendo de la selección -->
            </div>

            <!-- Botón Generar Reporte centrado -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg mt-4" id="btnGenerar">Generar Reporte</button>
            </div>
        </form>

        <!-- Mensaje de Error -->
        <div id="mensajeError" class="alert alert-danger d-none text-center mt-4" role="alert"></div>
    </div>

    <script>
        // Función para actualizar el formulario según el tipo de reporte seleccionado
        function cargarFormulario(tipo) {
            let fechaDiv = document.getElementById('fechaDiv');
            let mensajeError = document.getElementById('mensajeError');
            mensajeError.classList.add('d-none'); // Ocultar el mensaje de error

            if (tipo === 'dia') {
                fechaDiv.innerHTML = `
                    <label for="fecha" class="d-block mb-2">Seleccionar Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm w-25 mx-auto" required>
                    <input type="hidden" name="tipo_reporte" value="dia">
                `;
            } else if (tipo === 'mes') {
                fechaDiv.innerHTML = `
                    <label for="fecha" class="d-block mb-2">Seleccionar Mes</label>
                    <input type="month" name="fecha" id="fecha" class="form-control form-control-sm w-25 mx-auto" required>
                    <input type="hidden" name="tipo_reporte" value="mes">
                `;
            } else if (tipo === 'año') {
                let currentYear = new Date().getFullYear();
                let options = '';
                for (let year = currentYear; year >= 2000; year--) {
                    options += `<option value="${year}">${year}</option>`;
                }

                fechaDiv.innerHTML = `
                    <label for="fecha" class="d-block mb-2">Seleccionar Año</label>
                    <select name="fecha" id="fecha" class="form-control form-control-sm w-25 mx-auto" required>
                        ${options}
                    </select>
                    <input type="hidden" name="tipo_reporte" value="año">
                `;
            }
        }

        // Validación del formulario antes de enviarlo
        document.getElementById('formReporte').onsubmit = function(event) {
            let fecha = document.getElementById('fecha') ? document.getElementById('fecha').value : '';
            let mensajeError = document.getElementById('mensajeError');
            
            // Comprobar si se ha seleccionado una fecha
            if (!fecha) {
                event.preventDefault(); // Evitar el envío del formulario
                mensajeError.textContent = 'Por favor, seleccione una opción (día, mes o año) antes de generar el reporte.';
                mensajeError.classList.remove('d-none'); // Mostrar el mensaje de error
                setTimeout(() => { mensajeError.classList.add('d-none'); }, 5000); // Ocultar después de 5 segundos
                return false;
            }
        }
    </script>

    <style>
        /* Estilos del Mensaje de Error */
        .alert {
            position: relative;
            z-index: 1050;
        }

        /* Estilo para los campos de selección */
        select.form-control, input[type="month"], input[type="date"] {
            border-radius: 5px;
            font-size: 1rem;
            padding: 10px;
            transition: all 0.3s ease;
        }

        select.form-control:hover, input[type="month"]:hover, input[type="date"]:hover {
            border-color: #007bff;
        }

        select.form-control:focus, input[type="month"]:focus, input[type="date"]:focus {
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
    </style>

@endsection
