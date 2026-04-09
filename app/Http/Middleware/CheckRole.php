<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Si l'utilisateur n'est pas connecté → page de login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // On vérifie si le rôle de l'utilisateur est dans la liste autorisée
        // Exemple : CheckRole::handle($request, $next, 'admin', 'bibliothecaire')
        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Accès refusé. Vous n\'avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}
