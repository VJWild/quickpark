<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Un turno pertenece a un cajero
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un turno tiene muchas facturas cobradas
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    protected $casts = [
        'system_details' => 'array',
        'declared_details' => 'array',
        'differences_details' => 'array',
    ];
}
