<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        // Administrators have unrestricted access
        if ($user->hasRole('Administrator')) {
            return $next($request);
        }

        $currentRoute = $request->route()?->getName();

        if (! $currentRoute) {
            return $next($request);
        }

        // Check if any of the user's permissions (via their roles) match the current route
        $permissions = $user->getAllPermissions()->pluck('name');

        foreach ($permissions as $permission) {
            if (fnmatch($permission, $currentRoute)) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak.');
    }
}
