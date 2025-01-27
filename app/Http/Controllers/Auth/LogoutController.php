<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout(); // Cerrar sesión
        $request->session()->invalidate(); // Invalidar sesión
        $request->session()->regenerateToken(); // Regenerar token para evitar ataques CSRF

        return redirect('login'); // Redirigir a la página de inicio o login
    }
}
