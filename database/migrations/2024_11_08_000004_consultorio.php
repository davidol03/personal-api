<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up()
    {
        Schema::create('consultorio', function (Blueprint $table) {
            $table->integer('id_consultorio')->primary(); // Cambia a UUID u otro tipo si es necesario
            $table->string('ubicacion_consultorio');
            $table->integer('capacidad_consultorio');
            $table->integer('id_estatus'); // Índice para mejorar el rendimiento
            $table->integer('id_estatus_usuario');
            $table->timestamps(); // Añade los campos created_at y updated_at
            // Llaves foráneas
            $table->foreign('id_estatus')->references('id_estatus')->on('estatus');
            $table->foreign('id_estatus_usuario')->references('id_estatus_usuario')->on('estatus_usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('consultorio');
    }
};
