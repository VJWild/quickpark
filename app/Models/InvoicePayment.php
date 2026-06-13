<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    // Quitamos el candado para permitir guardar los pagos
    protected $guarded = [];

    // Relación: Un pago pertenece a una factura
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
