<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRASS SINTETICO SICA</title>
    <!-- Enlace a Bootstrap 5.3 para un diseño moderno y responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a FontAwesome para íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* Reset de estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        /* Barra lateral */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #2c3e50; /* Color más elegante y formal */
            color: white;
            padding-top: 100px; /* Aumentado para una mayor separación del título */
            box-shadow: 4px 0px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        #sidebar .title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
            margin-bottom: 40px;
            padding-top: 50px;
        }

        /* Enlaces barra lateral */
        #sidebar .nav-link {
            color: #bdc3c7;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 16px 25px;
            border-radius: 50px;
            margin: 8px 0;
            transition: all 0.3s ease;
        }

        #sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.5rem;
        }

        #sidebar .nav-link:hover {
            background-color: #2980b9;
            color: white;
            transform: translateX(10px);
        }

        #sidebar .nav-item.active .nav-link {
            background-color: #1abc9c;
            color: white;
        }

        /* Cerrar sesión */
        .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .logout button {
            background-color: #34495e;
            color: white;
            padding: 14px 25px;
            width: 100%;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .logout button:hover {
            background-color: #2c3e50;
            cursor: pointer;
            transform: scale(1.05);
        }

        /* Contenido principal */
        .main-content {
            margin-left: 250px;
            padding: 50px 35px;
            background-color: #f8f9fa;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Título principal */
        .content-title-container {
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .content-title-container h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .content-title-container i {
            font-size: 3rem;
            color: #3498db;
        }

        /* Responsividad */
        @media (max-width: 992px) {
            #sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
            }
        }

        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                position: fixed;
                top: 0;
                left: -100%;
                height: 100%;
                padding-top: 20px;
                transition: left 0.3s ease;
            }

            #sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            #sidebar .nav-link {
                text-align: center;
                padding: 14px;
            }

            .content-title-container h1 {
                font-size: 2.5rem;
            }

            .content-title-container i {
                font-size: 2.5rem;
            }

            .toggle-sidebar {
                display: block;
                font-size: 2rem;
                color: #2980b9;
                cursor: pointer;
                position: absolute;
                top: 20px;
                left: 20px;
            }
        }

    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Barra lateral -->
            <div id="sidebar" class="col-md-3 col-lg-2 p-0">
                <!-- Título de la barra lateral -->
                <div class="title">
                    OPCIONES
                </div>

                <!-- Opciones de la barra lateral -->
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('productos.index') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                            <i class="fas fa-cogs"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservas.index') ? 'active' : '' }}" href="{{ route('reservas.index') }}">
                            <i class="fas fa-calendar-check"></i> Reservar Partido
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ventas.index') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                            <i class="fas fa-chart-line"></i> Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reportes.index') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                            <i class="fas fa-file-alt"></i> Reportes
                        </a>
                    </li>
                </ul>

                <!-- Cerrar sesión -->
                <div class="logout">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Contenedor con título -->
                <div class="content-title-container">
                    <i class="fas fa-futbol"></i>
                    <h1>GRASS SINTETICO SAN SEBASTIAN</h1>
                    <i class="fas fa-futbol"></i>
                </div>

                <!-- Aquí se inyectará el contenido de cada vista -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Botón para abrir barra lateral en móvil -->
    <div class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Scripts de Bootstrap y JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para abrir el modal -->
    <script>
        document.getElementById('openModalButton').addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('nuevaVentaModal'));
            modal.show();
        });

        // Toggle para abrir/ocultar la barra lateral en móvil
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

</body>
</html>
