<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medico', function (Blueprint $table) {
            $table->integer('id_medico')->primary(); // Clave primaria sin auto-incremento
            $table->string('nombre_medico', 100);
            $table->string('apellido_medico', 100);
            $table->string('telefono', 15)->nullable();
            $table->string('email_medico', 100)->nullable();
            $table->integer('id_especialidad')->index(); // Agregar índice
            $table->integer('id_consultorio')->index();
            $table->timestamps(); // created_at y updated_at automáticas
            $table->integer('id_estatus');
            $table->integer('id_estatus_usuario');
            $table->integer('id_paciente');

            // Claves foráneas
            $table->foreign('id_especialidad')->references('id_especialidad')->on('especialidad');
            $table->foreign('id_consultorio')->references('id_consultorio')->on('consultorio');
            $table->foreign('id_estatus')->references('id_estatus')->on('estatus');
            $table->foreign('id_estatus_usuario')->references('id_estatus_usuario')->on('estatus_usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medico');
    }
};
