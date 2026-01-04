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

        if($data->status != 1)
        {
            return response()->json([
                'status' => false,
                'message' => 'Akun anda belum disetujui.'
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

    public function register(Request $request)
    {
        // 1. Validasi Input sesuai dengan form yang dibuat
        $validator = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|numeric',
            'agency_name'  => 'required|string|max:255',
            'username'     => 'required|string|unique:users,username|max:50',
            'password'     => 'required|min:6',
        ], [
            'email.unique'    => 'Alamat email sudah terdaftar.',
            'username.unique' => 'Username sudah digunakan.',
            'password.min'    => 'Password minimal 6 karakter.'
        ]);


        try {
            // 2. Simpan Data User
            $user = User::create([
                'role_id'      => 2, // Default: Pengguna/Mitra Instansi
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'agency_name'  => $request->agency_name,
                'username'     => $request->username,
                'password'     => Hash::make($request->password),
                'status'       => 0,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Pendaftaran berhasil. Silakan tunggu verifikasi admin sebelum dapat masuk.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function me()
    {

        return response()->json([
            'status' => true,
            'data' => Auth::user()
        ]);

    }
}
