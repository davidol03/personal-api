<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medico'; // Nombre de la tabla
    protected $primaryKey = 'id_medico'; // Columna primaria

    protected $fillable = [
        'id_medico', // Incluyendo el campo id_medico aquí
        'nombre_medico',
        'apellido_medico',
        'telefono',
        'email_medico',
        'id_especialidad',
        'id_consultorio',
        'fecha_modificacion',
        'id_estatus',
        'id_estatus_usuario',
        'id_paciente',//agregamos la fecha de mod
    ];
    public $timestamps = false; // Desactiva la gestión de timestamps

    // Relación con Especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad');
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'id_consultorio');
    }
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