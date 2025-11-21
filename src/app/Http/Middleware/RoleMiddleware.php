<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        if (count($roles) === 1 && str_contains((string)$roles[0], ',')) {
            $roles = explode(',', (string)$roles[0]);
        }
        $roles = array_values(array_filter(array_map('trim', $roles)));

        if (empty($roles)) {
            return $next($request);
        }

        $userRole = (string)($user->role ?? '');
        if ($userRole === 'admin') {
            return $next($request);
        }

        if (in_array($userRole, $roles, true)) {
            return $next($request);
        }

        abort(403);
    }
}
