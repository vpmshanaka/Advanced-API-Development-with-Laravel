<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class isUserCheckMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Gate::denies('isUser')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
