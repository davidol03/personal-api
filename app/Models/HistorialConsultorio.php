<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialConsultorio extends Model
{
    use HasFactory;

    protected $table = 'historial_consultorio'; // Nombre de la tabla
    protected $primaryKey = 'id_historial'; // Clave primaria

    protected $fillable = [
        'id_historial', // Clave primaria
        'id_consultorio', 
        'id_medico', 
        'fecha_uso', 
        'fecha_modificacion', 
        'id_estatus', 
        'id_estatus_usuario',
    ];

    public $timestamps = false; // Desactiva los timestamps automáticos

    // Relación con la tabla Consultorio
    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'id_consultorio');
    }

    // Relación con la tabla Medico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    // Relación con la tabla Estatus
    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'id_estatus');
    }

    // Relación con la tabla EstatusUsuario
    public function estatusUsuario()
    {
        return $this->belongsTo(EstatusUsuario::class, 'id_estatus_usuario');
    }
}
