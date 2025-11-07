<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or 'role:admin,worker'
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Support a single combined param like 'admin|worker' or 'admin,worker'
        if (count($roles) === 1 && (str_contains($roles[0], '|') || str_contains($roles[0], ','))) {
            $roles = preg_split('/[|,]/', $roles[0]) ?: [];
        }

        // Normalize to Role enums
        $allowed = [];
        foreach ($roles as $r) {
            $r = trim(strtolower($r));
            if ($r === Role::Admin->value) {
                $allowed[] = Role::Admin;
            } elseif ($r === Role::Worker->value) {
                $allowed[] = Role::Worker;
            } elseif ($r === Role::User->value) {
                $allowed[] = Role::User;
            }
        }

        if (empty($allowed)) {
            // If no role specified, deny by default
            abort(403);
        }

        if (!$user->hasRole($allowed)) {
            abort(403);
        }

        return $next($request);
    }
}
