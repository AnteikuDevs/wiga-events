<?php

namespace App\Http\Middleware;

use App\Models\ApiCredential;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY');
        if (empty($key)) {
            return response()->json([
                'status' => false,
                'message' => 'API key is missing.'
            ], 401);
        }

        $Credential = ApiCredential::findKey($key);

        if(!$Credential)
        {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Invalid API key.'
            ], 403);
        }
        
        return $next($request);
    }
}
