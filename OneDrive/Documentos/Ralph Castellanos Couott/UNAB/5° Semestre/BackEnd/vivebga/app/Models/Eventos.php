<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'categoria',
        'fecha',
        'hora',
        'cupos',
        'cupos_disponibles',
        'direccion',
        'precio',
        'imagen',
    ];

    // Relación con el organizador
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con los registros de usuarios
    public function registros()
    {
        return $this->hasMany(EventRegistration::class, 'evento_id');
    }
}
