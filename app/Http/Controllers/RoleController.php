<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $totalRoles = $roles->count();

        return view('admin.roles.index', compact('roles', 'totalRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name']
        ], [
            'name.unique' => 'Este rol ya existe en el sistema.',
            'name.required' => 'El nombre del rol es obligatorio.'
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol registrado exitosamente.');
    }

    public function destroy(Role $role)
    {
        // Validamos para no borrar el rol principal de Root
        if (in_array($role->name, ['Root', 'Administrador'])) {
            return back()->withErrors(['error' => 'No puedes eliminar un rol central del sistema.']);
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
