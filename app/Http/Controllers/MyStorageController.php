<?php

namespace App\Http\Controllers;

use App\Models\MyStorage;
use Illuminate\Http\Request;

class MyStorageController extends Controller
{
    public function fileShow(Request $request,string $id)
    {
        $request->validate([
            'download' => 'nullable|boolean'
        ]);

        $dokumen = MyStorage::find($id);
        if(!$dokumen){
            return response()->file(public_path('images/no-image.jpg'), [
                'Content-Type' => 'image/jpg',
                'Content-Disposition' => 'inline; filename="NoImage.jpg"',
            ]);
        }

        if(!file_exists(public_path($dokumen->url))){
            return response()->file(public_path('images/no-image.jpg'), [
                'Content-Type' => 'image/jpg',
                'Content-Disposition' => 'inline; filename="NoImage.jpg"',
            ]);
        }

        if($request->download){
            return response()->download(public_path($dokumen->url), $dokumen->name, [
                'Content-Type' => $dokumen->type,
                'Content-Disposition' => 'attachment; filename="' . $dokumen->name . '"',
            ]);
        }

        return response()->file(public_path($dokumen->url), [
            'Content-Type' => $dokumen->type,
            'Content-Disposition' => 'inline; filename="'.$dokumen->name.'"',
        ]);
    }
}
