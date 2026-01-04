<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // Pastikan Model Category sudah dibuat
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori (DataTable support)
     */
    public function index(Request $request)
    {
        // Gunakan WigaTable untuk mendukung fitur search & pagination di frontend
        $result = WigaTable(Category::query(), $request, function($query, $search){
            $query->where('name', 'like', "%$search%");
        }, [
            'id',
            'name',
            'created_at'
        ]);

        return response($result);
    }

    /**
     * Menyimpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:100',
        ], [
            'name.required' => 'name kategori wajib diisi',
            'name.unique' => 'Kategori ini sudah ada',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil menambahkan kategori",
        ]);
    }

    /**
     * Memperbarui kategori
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response(['status' => false, 'message' => "Kategori tidak ditemukan"]);
        }

        $request->validate([
            'name' => 'required|max:100|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil mengubah kategori",
        ]);
    }

    /**
     * Menghapus kategori
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response(['status' => false, 'message' => "Kategori tidak ditemukan"]);
        }

        // Opsional: Cek apakah kategori sedang digunakan oleh event lain sebelum dihapus
        // if ($category->events()->count() > 0) {
        //     return response(['status' => false, 'message' => "Kategori tidak bisa dihapus karena digunakan oleh event"]);
        // }

        $category->delete();

        return response([
            'status' => true,
            'message' => "Berhasil menghapus kategori",
        ]);
    }

    /**
     * List kategori sederhana untuk dropdown (tanpa WigaTable)
     */
    public function list()
    {
        $data = Category::orderBy('name', 'asc')->get();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}