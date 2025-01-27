<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Carga de estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body {
            background-image: url('/images/login.webp');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.25);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px;
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco con algo de transparencia */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-form h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
            color: #0d6efd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-form .form-label {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-form .form-control {
            border-radius: 8px;
            padding: 15px;
        }

        .logo {
            font-size: 30px;
            color: #0d6efd;
            margin-left: 10px;
            vertical-align: middle;
        }

        .icon {
            font-size: 30px;
            color: #0d6efd;
            margin-left: 10px;
            vertical-align: middle;
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                justify-content: center;
            }

            .login-form {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>
                <span class="material-icons icon">sports_soccer</span>
                Iniciar Sesión
                <span class="material-icons icon">sports_soccer</span>
            </h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            </form>
        </div>
    </div>

    <!-- Scripts necesarios para Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
