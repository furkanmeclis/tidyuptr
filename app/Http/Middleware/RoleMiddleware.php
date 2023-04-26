<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user()) {
            if (empty($request->user()->student_number)) {
                if ($request->user() && $request->user()->role == $role) {
                    return $next($request);
                }
            }
        }

        return redirect('/');
    }
}
