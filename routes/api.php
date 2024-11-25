<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::resource('especialidades', App\Http\Controllers\EspecialidadesController::class);
    Route::resource('estatus', App\Http\Controllers\EstatusController::class);
    Route::resource('estatus_usuarios', App\Http\Controllers\EstatusUsuariosController::class);
    Route::resource('citas', App\Http\Controllers\CitasController::class);
    Route::resource('consultorios', App\Http\Controllers\ConsultoriosController::class);
    Route::resource('medicos', App\Http\Controllers\MedicosController::class);
    Route::resource('equipo_medico', App\Http\Controllers\EquipoMedicosController::class);
    Route::resource('vacaciones_medico', App\Http\Controllers\VacacionesMedicosController::class);
    Route::resource('historial_consultorios', App\Http\Controllers\HistorialConsultoriosController::class);
