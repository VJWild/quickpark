<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Permite guardar datos masivamente desde el controlador
    protected $guarded = [];

    // Un cliente puede tener muchos tickets (entradas al estacionamiento)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
