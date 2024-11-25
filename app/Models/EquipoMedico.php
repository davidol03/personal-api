<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoMedico extends Model
{
    use HasFactory;

    protected $table = 'equipo_medico'; // Asegúrate de que esto coincida con tu nombre de tabla
    protected $primaryKey = 'id_equipomedico'; // Especifica el nombre de tu columna primaria

    protected $fillable = [
        'id_equipomedico', // id_control aquí
        'nombre_equipo',
        'descripcion',
        'fecha_uso',
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
