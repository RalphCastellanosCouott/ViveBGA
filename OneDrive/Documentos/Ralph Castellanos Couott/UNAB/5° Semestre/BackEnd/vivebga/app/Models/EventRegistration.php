<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    // 🔹 Campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id',
        'evento_id',
        'cantidad',
        'precio_pagado',
        'calificacion',
        'resena',
    ];

    // 🔹 Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔹 Relación con el evento
    public function evento()
    {
        return $this->belongsTo(Eventos::class, 'evento_id');
    }
}
