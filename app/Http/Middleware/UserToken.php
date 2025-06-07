<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wigaevents_id = $request->cookie('wigaevents_id');

        if(empty($wigaevents_id))
        {
            return redirect()->route('login');
        }

        $user = User::findToken($wigaevents_id);
        if(empty($user))
        {
            return redirect()->route('login');
        }

        Auth::login($user);

        return $next($request);
    }
}
