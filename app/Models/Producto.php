<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'cantidad'];

    // Relación con Ventas
    public function ventas()
    {
        return $this->belongsToMany(Venta::class)->withPivot('cantidad_vendida', 'total');
    }
}
