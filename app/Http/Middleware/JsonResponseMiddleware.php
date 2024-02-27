<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;



class JsonResponseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //dd('da');
        $request->headers->set('Accept', 'Application/json');

        return $next($request);
    }
}
