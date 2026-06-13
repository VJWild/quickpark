<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::all();
        $totalPrices = $prices->count();

        return view('admin.prices.index', compact('prices', 'totalPrices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mode' => 'required|numeric',
            'price_day' => 'required|numeric',
            'price_hour' => 'required|numeric',
        ]);

        // Guardamos la modalidad de cobro (1 = Mixto, 2 = Plana, 3 = Por Hora)
        \App\Models\Price::updateOrCreate(
            ['detail' => 'MODO'],
            ['amount' => $request->mode, 'quantity' => 1]
        );

        // Guardamos el precio del Día
        \App\Models\Price::updateOrCreate(
            ['detail' => 'DIAS'],
            ['amount' => $request->price_day, 'quantity' => 1]
        );

        // Guardamos el precio por Hora
        \App\Models\Price::updateOrCreate(
            ['detail' => 'HORAS'],
            ['amount' => $request->price_hour, 'quantity' => 1]
        );

        return back()->with('success', 'Las tarifas y la modalidad se han actualizado correctamente.');
    }

    public function update(Request $request, Price $price)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'detail' => 'required|string|in:HORAS,DIAS',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $price->update([
            'quantity' => $request->quantity,
            'detail' => $request->detail,
            'amount' => $request->amount
        ]);

        return redirect()->route('prices.index')->with('success', 'Precio actualizado correctamente.');
    }

    public function destroy(Price $price)
    {
        $price->delete();
        return redirect()->route('prices.index')->with('success', 'Tarifa eliminada del sistema.');
    }
}
