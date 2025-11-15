<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $wigaevents_id = $request->cookie('wigaevents_id');

        if(!empty($wigaevents_id))
        {
            $user = User::findToken($wigaevents_id);
            if(!empty($user))
            {
                Auth::login($user);
                return redirect()->route('redirect');
            }
        }


        return $next($request);
    }
}
