<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToReservasTable extends Migration
{
    public function up()
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Agregar la columna estado
            $table->string('estado')->default('pendiente'); // O 'enum' si prefieres usar estados específicos
        });
    }

    public function down()
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Eliminar la columna estado si es necesario
            $table->dropColumn('estado');
        });
    }
}
