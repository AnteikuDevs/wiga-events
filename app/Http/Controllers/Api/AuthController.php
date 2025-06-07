<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $data = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if(empty($data) || !Hash::check($request->password, $data->password))
        {
            return response()->json([
                'status' => false,
                'message' => 'Username atau Password yang anda masukan salah.'
            ]);
        }

        if(!$data->remember_token)
        {
            $data->remember_token = str_random(50);
            $data->save();
        }


        return response()->json([
            'status' => true,
            'data' => [
                'token' => $data->remember_token
            ]
        ]);

    }

    public function me()
    {

        return response()->json([
            'status' => true,
            'data' => Auth::user()
        ]);

    }
}
