<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa (Mass Assignment).
     * Esto permite usar métodos como create() o updateOrCreate().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'detail',   // Ejemplo: 'MODO', 'DIAS', 'HORAS'
        'amount',   // El valor numérico de la tarifa (ej: 3.00, 0.50, 1)
        'quantity'  // Cantidad (por defecto 1, exigido por tu esquema)
    ];
}
