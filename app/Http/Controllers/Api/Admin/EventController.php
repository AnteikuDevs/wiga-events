<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use WigaStorage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = WigaTable(Event::with(['image'])->withCount('participants'), $request, function($query,$search){
            $query->filterLike($search, ['title','description']);
        }, [
            'id',
            'title',
            'start_date',
            'end_date',
            'description'
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
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'nullable|date_format:Y-m-d\TH:i',
            'type' => 'required|in:online,offline',
            'location' => 'nullable',
            'link' => 'nullable|url',
        ],[
            'image.required' => 'Banner wajib diisi',
            'image.image' => 'Banner harus berupa gambar',
        ]);

        if($request->start_time < date('Y-m-d H:i')) {
            return response([
                'status' => false,
                'message' => "Tanggal mulai tidak boleh sebelum sekarang",
            ]);
        }
        
        if($request->end_time && $request->end_time < date('Y-m-d H:i')) {
            return response([
                'status' => false,
                'message' => "Tanggal selesai tidak boleh sebelum sekarang",
            ]);
        }

        $image_id = WigaStorage::store('image','events')->id();

        Event::create([
            'image_id' => $image_id,
            'title' => $request->title,
            'slug' => generateSlug(Event::class, 'title', $request->title),
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time? $request->end_time : null,
            'type' => $request->type,
            'location' => $request->location,
            'link' => $request->link
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

        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $request->validate([
            'image_deleted' => 'nullable|boolean',
            'image' => 'nullable|required_if:image_deleted,1|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            // 'until_finish' => 'required|in:0,1',
            'end_time' => 'nullable|date_format:Y-m-d\TH:i',
            'type' => 'required|in:online,offline',
            'location' => 'nullable',
            'link' => 'nullable|url',
        ],[
            'image.required_if' => 'Banner harus diisi',
            'image.image' => 'Banner harus berupa gambar',
        ]);

        WigaStorage::update('image',$data->image_id);

        $data->update([
            'title' => $request->title,
            'slug' => $data->title != $request->title ? generateSlug(Event::class, 'title', $request->title) : $data->slug,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time? $request->end_time : null,
            'type' => $request->type,
            'location' => $request->location,
            'link' => $request->link
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
        $data = Event::find($id);

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

    public function list()
    {

        $data = Event::latest()->get();

        if(empty($data)){
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        return response([
            'status' => true,
            'data' => $data
        ]);

    }

    public function generateAttendance(string $id)
    {
        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $cekLinkAttendance = EventAttendance::where('event_id', $data->id)->where('expired_at','>=',date('Y-m-d H:i:s'))->first();
        if($cekLinkAttendance){
            return response([
                'status' => true,
                'data' => $cekLinkAttendance
            ]);
        }

        $link = EventAttendance::create([
            'event_id' => $data->id,
            'token' => str_random(15),
            'expired_at' => date('Y-m-d H:i:s', strtotime('1 hour')),
        ]);

        return response([
            'status' => true,
            'data' => $link
        ]);
    }

    public function publish(string $id)
    {
        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $participantSended = $data->participants()->where('status_publish',false)->has('attendance')->get()->map(function($item){
            return [
                'target' => $item->phone_number.'|'.$item->name.'|'.route('event.certificate',[trimBase64(base64_encode($item->attendance->id))])
            ];
        })->pluck('target')->toArray();

        // return response(implode(',',$participantSended));

        FonnteService::send([
            'target' => implode(',',$participantSended),
            'message' => "Hai, {name}
Terima kasih telah berpartisipasi dan menyelesaikan seluruh rangkaian acara {$data->title}.
Sebagai bentuk apresiasi atas keikutsertaan Anda, bersama ini kami sampaikan e-sertifikat Anda dengan detail sebagai berikut:\n\n
Nama Acara: {$data->title}
Nomor Sertifikat: {name}
Sertifikat dapat diunduh melalui tautan berikut:
Link Sertifikat: {var1}
Semoga ilmu yang didapat bermanfaat. Sampai jumpa di acara kami selanjutnya!
Hormat kami,
Panitia Acara"
        ]);
        
        $data->update([
            'status_publish' => true,
            'end_time' => date('Y-m-d H:i:s')
        ]);

        $data->participants()->where('status_publish',false)->update([
            'status_publish' => true,
        ]);

    }

    public function sendNotification(string $id)
    {
        
        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        if($data->participants()->count() == 0) {
            return response([
                'status' => false,
                'message' => "Gagal mengirim notifikasi, acara ini belum memiliki peserta",
            ]);
        }

        $participantSended = $data->participants()->where('status_publish',false)->get()->map(function($item){
            return [
                'target' => $item->phone_number.'|'.$item->name
            ];
        })->pluck('target')->toArray();

        // return response(implode(',',$participantSended));

        $contentNotify = "Hai, {name}

Jangan lewatkan acara spesial kami!

*Acara*: {$data->title}
*Waktu*: {$data->date_format}, pukul {$data->time_format}
*Lokasi*: {$data->location}

Pastikan Anda datang tepat waktu. Kami tunggu kehadiran Anda!

Terima kasih,
_Panitia Acara_";

        if($data->type == 'online') {
            $contentNotify = "Hai, {name}
            
Semoga dalam keadaan baik. Kami ingin mengingatkan Anda untuk bergabung di acara online kami.

*Acara*: {$data->title}
*Waktu*: {$data->date_format}, pukul {$data->time_format}

Anda dapat bergabung melalui tautan berikut:
_{$data->link}_

Pastikan koneksi internet Anda stabil. Sampai jumpa di dunia maya!

Terima kasih,
_Panitia Acara_";
        }

        FonnteService::send([
            'target' => implode(',',$participantSended),
            'message' => $contentNotify
        ]);

        // return response($response);

        return response([
            'status' => true,
            'message' => "Berhasil mengirim notifikasi",
        ]);

    }
}
