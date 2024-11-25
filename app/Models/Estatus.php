<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;

    protected $table = 'estatus'; // Nombre de la tabla
    protected $primaryKey = 'id_estatus'; // Llave primaria

    protected $fillable = [
        'id_estatus',
        'nombre_estatus',
    ];

    public $timestamps = false; // Desactiva la gestión automática de created_at y updated_at

}
