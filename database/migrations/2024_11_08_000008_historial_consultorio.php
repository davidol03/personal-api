<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historial_consultorio', function (Blueprint $table) {
            $table->integer('id_historial')->primary(); // Sin auto-incremento
            $table->string('id_consultorio'); // Foreign key
            $table->string('id_medico'); // Foreign key
            $table->dateTime('fecha_uso');
            $table->timestamps(); // created_at y updated_at
            $table->integer('id_estatus');
            $table->integer('id_estatus_usuario');

            // Claves foráneas
            $table->foreign('id_consultorio')->references('id_consultorio')->on('consultorio');
            $table->foreign('id_medico')->references('id_medico')->on('medico');
            $table->foreign('id_estatus')->references('id_estatus')->on('estatus');
            $table->foreign('id_estatus_usuario')->references('id_estatus_usuario')->on('estatus_usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_consultorio'); // Nombre debe coincidir exactamente
    }
};
