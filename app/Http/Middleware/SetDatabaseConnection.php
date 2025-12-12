<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DatabaseConnectionService;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * 
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set connection berdasarkan role user yang login
        DatabaseConnectionService::setConnectionByRole();
        
        return $next($request);
    }
}
