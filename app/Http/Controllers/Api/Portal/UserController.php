<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan dukungan WigaTable
     */
    public function index(Request $request)
    {
        // Query dasar dengan relasi role
        $query = User::with('role');

        $result = WigaTable($query, $request, function($query, $search) {
            $query->filterLike($search, ['name', 'username', 'email', 'phone_number', 'agency_name'])->orWhereHas('role', function($query) use ($search) {
                $query->filterLike($search, ['name']);
            });
        }, [
            'id',
            'name',
            'username',
            'agency_name',
            'phone_number',
            'status_acc',
            'role_id'
        ]);

        return response($result);
    }

    /**
     * Menyimpan pengguna baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id'      => 'required|in:1,2',
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|unique:users,username|max:50',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'nullable|numeric',
            'agency_name'  => 'nullable|string|max:255',
            'password'     => 'required|min:6',
        ]);

        User::create([
            'role_id'      => $request->role_id,
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'agency_name'  => $request->agency_name,
            'password'     => Hash::make($request->password),
            'status'       => 0, // Default saat daftar via admin/portal
        ]);

        return response([
            'status'  => true,
            'message' => "Berhasil menambahkan pengguna baru",
        ]);
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role_id'      => 'required|in:1,2',
            'name'         => 'required|string|max:255',
            'username'     => ['required', Rule::unique('users')->ignore($user->id)],
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|numeric',
            'agency_name'  => 'nullable|string|max:255',
            'password'     => 'nullable|min:6',
        ]);

        $data = [
            'role_id'      => $request->role_id,
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'agency_name'  => $request->agency_name,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response([
            'status'  => true,
            'message' => "Data pengguna berhasil diperbarui",
        ]);
    }

    /**
     * Logika Verifikasi Akun (Approve/Reject)
     */
    public function verify(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approve,reject'
        ]);

        $statusMapping = [
            'approve' => 1,
            'reject'  => 2
        ];

        $user->update([
            'status' => $statusMapping[$request->status]
        ]);

        $msg = $request->status == 'approve' ? "Akun disetujui" : "Akun ditolak";

        return response([
            'status'  => true,
            'message' => "$msg",
        ]);
    }

    /**
     * Menghapus pengguna
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi agar tidak menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return response([
                'status'  => false,
                'message' => "Anda tidak dapat menghapus akun sendiri",
            ], 403);
        }

        $user->delete();

        return response([
            'status'  => true,
            'message' => "Pengguna berhasil dihapus",
        ]);
    }
}