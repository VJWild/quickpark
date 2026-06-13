<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpace extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Un puesto de estacionamiento puede tener muchos tickets a lo largo del tiempo
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
