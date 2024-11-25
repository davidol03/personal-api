<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    protected $table = 'consultorio'; // Asegúrate de que esto coincida con tu nombre de tabla
    protected $primaryKey = 'id_consultorio'; // Especifica el nombre de tu columna primaria

    protected $fillable = [
        'id_consultorio', //  id_consultorio aquí
        'ubicacion_consultorio',
        'capacidad_consultorio',
        'id_estatus',
        'id_estatus_usuario',
        'fecha_modificacion',
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
