<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusUsuario extends Model
{
    use HasFactory;

    protected $table = 'estatus_usuario'; // Nombre de la tabla
    protected $primaryKey = 'id_estatus_usuario'; // Llave primaria

    protected $fillable = [
        'id_estatus_usuario',
        'nombre_usuario',
    ];

    public $timestamps = false; // Desactiva la gestión automática de created_at y updated_at

}
