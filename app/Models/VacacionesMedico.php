<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacacionesMedico extends Model
{
    use HasFactory;


    protected $table = 'vacaciones_medico'; // Nombre de la tabla
    protected $primaryKey = 'id_vacacion'; // Clave primaria


    // Especificar los campos que son asignables
    protected $fillable = [
        'id_vacacion',
        'id_medico',
        'fecha_inicio',
        'fecha_fin',
        'motivo_vacacion',
        'fecha_modificacion',
        'id_estatus',
        'id_estatus_usuario',
    ];  
    
    public $timestamps = false;// Desactiva la gestión de timestamps
    // Definir las relaciones
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'id_estatus');
    }

    public function estatusUsuario()
    {
        return $this->belongsTo(EstatusUsuario::class, 'id_estatus_usuario');
    }
}
