<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['dni', 'nombre', 'puntos', 'total_compras', 'vip'];
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
    public function actualizarTotales()
    {
        $totalCompras = $this->ventas()->sum('total');
        $puntos = floor($totalCompras / 10);

        $this->update([
            'total_compras' => $totalCompras,
            'puntos' => $puntos,
        ]);
    }


}
