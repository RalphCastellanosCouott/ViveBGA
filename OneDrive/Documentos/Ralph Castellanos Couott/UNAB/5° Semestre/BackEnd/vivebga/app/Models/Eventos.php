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
        'fecha',
        'hora',
        'direccion',
        'precio',
        'imagen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
