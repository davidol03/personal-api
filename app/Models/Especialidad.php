<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidad'; // Nombre de la tabla
    protected $primaryKey = 'id_especialidad'; // Columna primaria

    protected $fillable = [
        'id_especialidad', // Incluyendo el campo id_especialidad aquí
        'nombre_especialidad',
        'descripcion',
        'fecha_modificacion',
        'id_estatus',
        'id_estatus_usuario',
    ];

    public $timestamps = false; // Desactiva la gestión de timestamps
    // Relación con Estatus
    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'id_estatus');
    }

    // Relación con EstatusUsuario
    public function estatusUsuario()
    {
        return $this->belongsTo(EstatusUsuario::class, 'id_estatus_usuario');
    }
}