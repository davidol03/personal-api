<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Cita extends Model
{
    use HasFactory;

    protected $table = 'cita'; 
    protected $primaryKey = 'id_cita';
    public $timestamps = true;

    protected $fillable = [
        'id_cita',
        'fecha_hora_cita',
        'id_medico',
        'id_consultorio',
        'id_estatus',
        'id_estatus_usuario',
        'id_paciente',
    ];
    
    // Relaciones
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }
    public function consultorio()
    {
        return $this->belongsTo(consultorio::class, 'id_consultorio');
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
