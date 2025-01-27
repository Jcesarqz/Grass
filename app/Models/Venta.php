<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Venta extends Model
{
    use HasFactory;

    // Columnas que se pueden asignar masivamente
    protected $fillable = ['codigo', 'fecha', 'total'];

    // Convertir automáticamente la columna 'fecha' a un objeto Carbon
    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Relación muchos a muchos con el modelo Producto.
     * Incluye los campos adicionales en la tabla pivote: cantidad_vendida y total.
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
                    ->withPivot('cantidad_vendida', 'total')
                    ->withTimestamps();
    }

    /**
     * Accesor para obtener el formato de fecha amigable.
     * @return string
     */
    public function getFormattedFechaAttribute()
    {
        return $this->fecha ? $this->fecha->format('d/m/Y H:i:s') : null;
    }
}
