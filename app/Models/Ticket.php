<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relaciones: Un ticket pertenece a un Cliente, a un Puesto y a un Usuario(Cajero)
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function parkingSpace()
    {
        return $this->belongsTo(ParkingSpace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
