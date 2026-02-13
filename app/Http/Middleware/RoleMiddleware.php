<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Ambil role user dari database
        $userRole = Auth::user()->role;

        // Kalau role user ada di parameter middleware
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
