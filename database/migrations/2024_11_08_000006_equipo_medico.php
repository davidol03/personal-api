<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_medico', function (Blueprint $table) {
            $table->integer('id_equipomedico')->primary(); // Clave primaria sin auto-incremento
            $table->string('nombre_equipo', 100);
            $table->string('descripcion', 255); // Aumentar longitud si es necesario
            $table->date('fecha_uso');
            $table->timestamps(); // Añade created_at y updated_at
            $table->integer('id_estatus'); // Añadir índice
            $table->integer('id_estatus_usuario');

            // Claves foráneas con eliminación `set null`
            $table->foreign('id_estatus')->references('id_estatus')->on('estatus')->onDelete('set null');
            $table->foreign('id_estatus_usuario')->references('id_estatus_usuario')->on('estatus_usuario')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipo_medico');
    }
};
