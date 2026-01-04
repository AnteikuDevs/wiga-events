<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use App\Models\Event;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use WigaStorage;

class EventCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $request->validate([
            'event' => 'required|exists:events,id',
        ]);

        $data = CertificateTemplate::with(['event','image'])->where('event_id', $request->event);
        
        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = CertificateTemplate::whereRelation('event', 'created_by', $user->id)->where('event_id', $request->event);
        }

        $result = WigaTable($data, $request, function($query,$search){
            $query->filterLike($search, ['certificate_number','model','name'])->orWhereHas('event',function($query) use ($search){
                $query->filterLike($search, ['title']);
            });
        }, [
            'id',
            'name',
            null,
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
            'event' => 'required|exists:events,id',
            'name'  => 'required',
            'model' => 'required|in:1,2',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'certificate_number' => 'nullable',
            'certificate_as' => 'required',
        ],[
            'image.required' => 'Background Sertifikat harus diisi',
            'image.image' => 'Background Sertifikat harus berupa gambar',
        ]);

        $dataEvent = Event::find($request->event);
        
        if(!$dataEvent)
        {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }
        // $cekCertByType = CertificateTemplate::where('event_id', $request->event)->where('participant_type', $request->type)->first();

        // if ($cekCertByType) {
        //     return response([
        //         'status' => false,
        //         'message' => "Template sertifikat sudah ada",
        //     ]);
        // }

        $image_id = WigaStorage::store('image','events')->id();

        $countTemplate = CertificateTemplate::where('event_id', $request->event)->count();

        CertificateTemplate::create([
            'name' => $request->name,
            'model_id' => $request->model,
            'image_id' => $image_id,
            'event_id' => $request->event,
            'certificate_number' => $request->certificate_number,
            'certificate_as' => $request->certificate_as,
            'is_default' => $countTemplate == 0 ? 1 : 0
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

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = CertificateTemplate::whereRelation('event', 'created_by', $user->id);
        }

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $request->validate([
            'event' => 'required|exists:events,id',
            'name'  => 'required',
            'model' => 'required|in:1,2',
            'image_deleted' => 'nullable|boolean',
            'image' => 'nullable|required_if:image_deleted,1|image|mimes:jpeg,png,jpg',
            'certificate_number' => 'nullable',
            'certificate_as' => 'required',
        ],[
            'image.required' => 'Background Sertifikat harus diisi',
            'image.image' => 'Background Sertifikat harus berupa gambar',
        ]);

        WigaStorage::update('image',$data->image_id);

        $data->update([
            'name' => $request->name,
            'model_id' => $request->model,
            'certificate_number' => $request->certificate_number,
            'certificate_as' => $request->certificate_as
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

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = CertificateTemplate::whereRelation('event', 'created_by', $user->id);
        }

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

    public function setDefault(string $id)
    {
        $data = CertificateTemplate::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $data->event->certificates()->update([
            'is_default' => false
        ]);

        $data->update([
            'is_default' => true
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil mengubah default",
        ]);
    }

    public function list(Request $request)
    {

        $request->validate([
            'event' => 'required|exists:events,id',
        ]);

        $data = CertificateTemplate::where('event_id', $request->event);

        return response([
            'status' => true,
            'data' => $data->get(),
        ]);

    }
}
