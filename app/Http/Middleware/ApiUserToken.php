<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $tokenBearer = $request->bearerToken();

        // Auth::logout();

        if(empty($tokenBearer))
        {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Invalid user token.'
            ], 403);
        }

        $user = User::findToken($tokenBearer);

        if(empty($user))
        {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Invalid user token.'
            ], 403);
        }
        
        Auth::login($user);

        return $next($request);

    }
}
