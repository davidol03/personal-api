<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up()
    {
        Schema::create('especialidad', function (Blueprint $table) {
            $table->integer('id_especialidad')->primary(); // Clave primaria sin auto-incremento
            $table->string('nombre_especialidad', 100);
            $table->string('descripcion', 255)->nullable(); // Aumentar longitud si es necesario
            $table->timestamps(); // Añade los campos created_at y updated_at
            $table->integer('id_estatus'); // Añadir índice
            $table->integer('id_estatus_usuario');

            // Llaves foráneas
            $table->foreign('id_estatus')->references('id_estatus')->on('estatus');
            $table->foreign('id_estatus_usuario')->references('id_estatus_usuario')->on('estatus_usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('especialidad');
    }
};
