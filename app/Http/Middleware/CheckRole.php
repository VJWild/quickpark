<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Intercepta la petición y verifica los roles.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1. Extraemos el rol de forma segura (Soporta si en BD es texto o si es una relación)
        $userRole = is_object($user->role) ? $user->role->name : ($user->role ?? '');

        // 2. El Administrador tiene "Pase VIP" universal
        if ($userRole === 'Administrador') {
            return $next($request);
        }

        // 3. Verificamos si el rol del usuario está en la lista de permitidos
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. EL PARCHE DE BREEZE: Si un "Operador" inicia sesión, Laravel lo manda al dashboard.
        // En lugar de tirarle un error 403, lo redirigimos amablemente a su panel de usuarios.
        if ($userRole === 'Operador' && $request->routeIs('dashboard')) {
            return redirect()->route('users.index');
        }

        // 5. Si no tiene permiso y no es un redireccionamiento automático, lo bloqueamos
        abort(403, 'Acceso Denegado. Tu rol (' . $userRole . ') no tiene permisos para este módulo.');
    }
}
