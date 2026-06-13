<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role; // Importante: Importamos el modelo Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $totalUsers = $users->count();
        $admins = $users->whereIn('role', ['Administrador', 'Root'])->count();
        $cashiers = $users->where('role', 'Cajero')->count();

        // Buscamos todos los roles disponibles en la base de datos
        $availableRoles = Role::all();

        // Le pasamos los roles a la vista
        return view('admin.users.index', compact('users', 'totalUsers', 'admins', 'cashiers', 'availableRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            // Validamos que el rol enviado exista en la columna 'name' de la tabla 'roles'
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'email.unique' => 'Este correo ya está en uso por otro usuario.',
            'role.exists' => 'El rol seleccionado no es válido.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            // Validamos que el rol enviado exista en la tabla 'roles'
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'email.unique' => 'Este correo ya pertenece a otro usuario.',
            'role.exists' => 'El rol seleccionado no es válido.'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Información actualizada correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado del sistema.');
    }
}
