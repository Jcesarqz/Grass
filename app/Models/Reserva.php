<?php

// Modelo Reserva
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    // Agregar todos los campos necesarios
    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'duracion',
        'precio',
        'total',
        'estado', // Agrega este campo
    ];
}

