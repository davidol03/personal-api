<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vacaciones_medico', function (Blueprint $table) {
            $table->integer('id_vacacion')->primary();
            $table->integer('id_medico')->nullable(); // Puede ser nulo si no está asociado a un médico
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('motivo_vacacion', 255)->nullable(); // Motivo de la vacación, opcional
            $table->timestamps(); // created_at y updated_at
            $table->integer('id_estatus');
            $table->integer('id_estatus_usuario');

            // Claves foráneas
            $table->foreign('id_medico')->references('id_medico')->on('medico')->onDelete('set null');
            $table->foreign('id_estatus')->references('id_estatus')->on('estatus')->onDelete('set null');
            $table->foreign('id_estatus_usuario')->references('id_estatus_usuario')->on('estatus_usuario')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vacaciones_medico');
    }
};
