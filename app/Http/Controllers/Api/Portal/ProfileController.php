<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update data profil user (kecuali username)
     */
    public function update(Request $request)
    {
        $user = $request->user(); // Mendapatkan user yang sedang login

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'required|string|max:15',
            'agency_name'       => 'required|string|max:255',
        ]);

        // Update data (username sengaja tidak dimasukkan agar tidak berubah)
        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'agency_name'       => $request->agency_name,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user
        ], 200);
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required', // Opsional: meminta password lama demi keamanan
            'password'         => 'required|string|min:8|confirmed', // Harus ada input password_confirmation
        ]);

        $user = User::find(Auth::user()->id);

        // Validasi apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Password lama tidak sesuai'
            ], 422);
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil diubah'
        ], 200);
    }
}