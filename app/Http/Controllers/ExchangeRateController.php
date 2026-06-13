<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $tasa = ExchangeRate::first();
        return view('admin.tasa.index', compact('tasa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0.01'
        ]);

        ExchangeRate::updateOrCreate(
            ['id' => 1],
            ['rate' => $request->rate]
        );

        return back()->with('success', 'Tasa de cambio actualizada correctamente. A partir de este momento las ventas se registrarán con este valor.');
    }
}
