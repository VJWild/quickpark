<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Búsqueda AJAX para la Dispensadora
     */
    public function search($plate)
    {
        $client = Client::where('plate', $plate)->first();
        return response()->json($client);
    }

    /**
     * DIRECTORIO DE CLIENTES (Vista principal)
     */
    public function index()
    {
        // Traemos todos los clientes ordenados por los más recientes
        $clients = Client::orderBy('created_at', 'desc')->get();
        $totalClients = $clients->count();

        return view('admin.clients.index', compact('clients', 'totalClients'));
    }

    /**
     * ACTUALIZAR CLIENTE
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string|max:255',
            'plate' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update([
            'name' => $request->name,
            'document' => $request->document,
            'plate' => strtoupper($request->plate), // Guardamos la placa siempre en mayúsculas
            'phone' => $request->phone,
        ]);

        return redirect()->route('clients.index')->with('success', 'Datos del cliente actualizados correctamente.');
    }
}
