<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index()
    {
        // Traemos la información de la empresa (si no existe, manda null)
        $info = Information::first();
        return view('admin.information.index', compact('info'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'activity' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            // Puedes validar los demás campos si lo deseas
        ]);

        Information::updateOrCreate(
            ['id' => 1], // Siempre buscaremos/actualizaremos el registro 1
            $request->all()
        );

        return back()->with('success', 'Información de la empresa actualizada correctamente. Los próximos PDFs saldrán con estos datos.');
    }
}
