<?php

// database/migrations/xxxx_xx_xx_create_reservas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->double('duracion'); // Duración en horas
            $table->decimal('precio', 8, 2);
            $table->decimal('total', 8, 2); // Total de la reserva (precio por hora * duración)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
