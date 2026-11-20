<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;
        if ($userRole === 'student') {
            $userRole = 'siswa';
        }

        $allowed = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $part) {
                $trimmed = trim($part);
                $allowed[] = ($trimmed === 'student') ? 'siswa' : $trimmed;
            }
        }

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
