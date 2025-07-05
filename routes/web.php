<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
Route::resource('clientes', ClienteController::class);
Route::post('/productos', [ProductoController::class, 'store']);
Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');  // Mostrar productos para añadir al carrito
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/ventas/{venta}/asignar-cliente', [VentaController::class, 'asignarCliente'])->name('ventas.asignarCliente');
Route::resource('reservas', ReservaController::class)->except(['destroy']);
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::post('/ventas/{venta}/asignar-cliente', [VentaController::class, 'asignarCliente'])->name('ventas.asignarCliente');
Route::resource('productos', ProductoController::class);


// Proteger rutas con middleware de autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas para productos
    
    // Rutas para ventas
    
    Route::post('ventas/{id}/addToCart', [VentaController::class, 'addToCart'])->name('ventas.addToCart');  // Añadir al carrito
    Route::get('/carrito', [VentaController::class, 'viewCart'])->name('carrito.view');  // Ver carrito de compras
    
    Route::post('ventas', [VentaController::class, 'store'])->name('ventas.store');  // Realizar la venta
    Route::post('ventas/removeFromCart/{id}', [VentaController::class, 'removeFromCart'])->name('ventas.removeFromCart'); // Eliminar del carrito
    Route::post('ventas/updateCart', [VentaController::class, 'updateCart'])->name('ventas.updateCart');


    // Rutas para reservas
    
    Route::post('reservas/pagar/{id}', [ReservaController::class, 'pagar'])->name('reservas.pagar');
    Route::delete('reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

    // Rutas para reportes
    
    Route::post('/reportes/generate', [ReporteController::class, 'generate'])->name('reportes.generate');

    // Ruta para cerrar sesión
    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
});



// Rutas de autenticación (automáticamente manejadas por Breeze)
require __DIR__.'/auth.php';
