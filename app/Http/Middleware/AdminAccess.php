<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect('/')
                ->with('error', 'Anda tidak memiliki akses sebagai admin.');
        }

        return $next($request);
    }
}
