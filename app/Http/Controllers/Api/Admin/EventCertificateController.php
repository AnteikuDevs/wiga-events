<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use WigaStorage;

class EventCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = WigaTable(CertificateTemplate::with(['event','image']), $request, function($query,$search){
            $query->filterLike($search, ['certificate_number'])->orWhereHas('event',function($query) use ($search){
                $query->filterLike($search, ['title']);
            });
        }, [
            'id',
            null,
            'event_id',
            'participant_type',
            'certificate_number',
        ]);

        return response($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'event' => 'required|exists:events,id',
            'type' => 'required|in:participant,committee',
            'certificate_number' => 'required|unique:certificate_templates,certificate_number',
        ]);

        $cekCertByType = CertificateTemplate::where('event_id', $request->event)->where('participant_type', $request->type)->first();

        if ($cekCertByType) {
            return response([
                'status' => false,
                'message' => "Template sertifikat sudah ada",
            ]);
        }

        $image_id = WigaStorage::store('image','events')->id();

        CertificateTemplate::create([
            'image_id' => $image_id,
            'event_id' => $request->event,
            'participant_type' => $request->type,
            'certificate_number' => $request->certificate_number,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil menambahkan event",
        ]);

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $data = CertificateTemplate::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $request->validate([
            'image_deleted' => 'nullable|boolean',
            'image' => 'nullable|required_if:image_deleted,1|image|mimes:jpeg,png,jpg,gif',
            'event' => 'required|exists:events,id',
            'type' => 'required|in:participant,committee',
            'certificate_number' => 'required',
        ],[
            'image.required_if' => 'Banner harus diisi',
            'image.image' => 'Banner harus berupa gambar',
        ]);

        WigaStorage::update('image',$data->image_id);

        $data->update([
            'event_id' => $request->event,
            'participant_type' => $request->type,
            'certificate_number' => $request->certificate_number,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil mengubah event",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = CertificateTemplate::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $data->delete();

        WigaStorage::delete($data->image_id,'image');

        return response([
            'status' => true,
            'message' => "Berhasil menghapus event",
        ]);

    }
}
