<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventCategory;
use App\Models\Role;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use MyQRCode;
use WigaStorage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Load relation categories juga agar bisa tampil di tabel jika perlu
        $query = Event::with(['image', 'categories','certificates'])->withCount('participants');
        
        if($user->role_id != Role::SUPER_ADMIN)
        {
            $query->where('created_by', $user->id);
        }

        $result = WigaTable($query, $request, function($query, $search){
            $query->filterLike($search, ['title', 'description', 'location','link', 'quota', 'registration_fee'])->orWhereHas('categories', function($query) use ($search){
                $query->filterLike($search, ['name']);
            });
        }, [
            'id',
            null, // Untuk numbering/image
            'title',
            'description',
            'start_time',
            'participants_count',
            'end_time'
        ]);

        return response($result);
    }
    
    public function show(Request $request, string $id)
    {

        $data = Event::with(['image', 'categories'])->find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        return response([
            'status' => true,
            'data' => $data,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required',
            'category_ids' => 'required|array', // Validasi kategori
            'category_ids.*' => 'exists:categories,id',
            'description' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'nullable|date_format:Y-m-d\TH:i',
            'registration_end' => 'nullable|date_format:Y-m-d',
            'type' => 'required|in:online,offline',
            'location' => 'nullable|required_if:type,offline',
            'link' => 'nullable|url|required_if:type,online',
            'quota' => 'nullable|numeric',
            'registration_fee' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|required_if:registration_fee,>,0',
            'bank_account_name' => 'nullable|required_if:registration_fee,>,0',
            'bank_account_number' => 'nullable|required_if:registration_fee,>,0',
            'bank_name' => 'nullable|required_with:registration_fee',
            'bank_account_name' => 'nullable|required_with:registration_fee',
            'bank_account_number' => 'nullable|required_with:registration_fee',
        ], [
            'image.required' => 'Banner wajib diisi',
            'category_ids.required' => 'Pilih minimal satu kategori event',
        ]);

        if ($request->start_time < date('Y-m-d H:i')) {
            return response(['status' => false, 'message' => "Tanggal mulai tidak boleh sebelum sekarang"]);
        }
        
        if ($request->registration_end && $request->registration_end > $request->start_time) {
            return response(['status' => false, 'message' => "Batas registrasi tidak boleh melampaui tanggal mulai acara"]);
        }

        return DB::transaction(function () use ($request) {
            $image_id = WigaStorage::store('image', 'events')->id();

            $event = Event::create([
                'image_id' => $image_id,
                'title' => $request->title,
                'slug' => generateSlug(Event::class, 'title', $request->title),
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time ?: null,
                'registration_end' => $request->registration_end ?: null,
                'type' => $request->type,
                'location' => $request->location,
                'link' => $request->link,
                'quota' => $request->quota,
                'registration_fee' => $request->registration_fee ?: 0,
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'created_by' => auth()->user()->id
            ]);
            
            $event->categories()->sync($request->category_ids);

            return response([
                'status' => true,
                'message' => "Berhasil menambahkan event",
            ]);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $query = Event::query();
        if($user->role_id != Role::SUPER_ADMIN) {
            $query = $query->where('created_by', $user->id);
        }
        $data = $query->find($id);

        if (!$data) {
            return response(['status' => false, 'message' => "Data tidak ditemukan"]);
        }

        $request->validate([
            'image_deleted' => 'nullable|boolean',
            'image' => 'nullable|required_if:image_deleted,1|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'description' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'nullable|date_format:Y-m-d\TH:i',
            'registration_end' => 'nullable|date_format:Y-m-d',
            'type' => 'required|in:online,offline',
            'location' => 'nullable',
            'link' => 'nullable|url|required_if:type,online',
            'quota' => 'nullable|numeric',
            'registration_fee' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|required_with:registration_fee',
            'bank_account_name' => 'nullable|required_with:registration_fee',
            'bank_account_number' => 'nullable|required_with:registration_fee',
        ], [
            'image.required' => 'Banner wajib diisi',
            'category_ids.required' => 'Pilih minimal satu kategori event',
        ]);

        return DB::transaction(function () use ($request, $data) {
            WigaStorage::update('image', $data->image_id);

            $data->update([
                'title' => $request->title,
                'slug' => $data->title != $request->title ? generateSlug(Event::class, 'title', $request->title) : $data->slug,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time ?: null,
                'registration_end' => $request->registration_end ?: null,
                'type' => $request->type,
                'location' => $request->location,
                'link' => $request->link,
                'quota' => $request->quota,
                'registration_fee' => $request->registration_fee ?: 0,
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number
            ]);

            // Update Kategori di tabel pivot
            $data->categories()->sync($request->category_ids);

            return response([
                'status' => true,
                'message' => "Berhasil mengubah event",
            ]);
        });
    }


    public function destroy(string $id)
    {
        $data = Event::find($id);

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = Event::where('created_by', $user->id)->find($id);
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



    public function list()
    {
        $data = Event::latest()->get();

        $user = Auth::user();

        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = Event::where('created_by', $user->id)->latest()->get();
        }

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

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = Event::where('created_by', $user->id)->find($id);
        }

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

        $token = str_random(15);

        $qrCode = MyQRCode::save(route('event.attendance',$token), $data->slug);

        $link = EventAttendance::create([
            'event_id' => $data->id,
            'token' => $token,
            'qr_code_path' => $qrCode,
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

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = Event::where('created_by', $user->id)->find($id);
        }

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        if($data->participants()->count() == 0) {
            return response([
                'status' => false,
                'message' => "Tidak ada peserta yang terdaftar",
            ]);
        }

        if($data->participants()->has('attendance')->count() == 0) {
            return response([
                'status' => false,
                'message' => "Tidak ada peserta yang hadir",
            ]);
        }

        $participantSended = $data->participants()->has('attendance')->where('status_publish',false)->get()->map(function($item){
            return [
                'target' => $item->phone_number.'|'.$item->name.'|'.route('event.certificate',[trimBase64(base64_encode($item->attendance->id))])
            ];
        })->pluck('target')->toArray();

        if(empty($participantSended)){

            return response([
                'status' => false,
                'message' => "Tidak ada peserta yang belum terkirim sertifikat",
            ]);

        }

        FonnteService::send([

            'target' => implode(',',$participantSended),

            'message' => "Halo {name},

Terima kasih telah bergabung dalam acara *{$data->title}*.\n

Berikut adalah link unduh e-sertifikat sebagai bukti partisipasi Anda: 👉 _{var1}_

Catatan: Pastikan koneksi internet stabil saat mengunduh.

Sampai bertemu di event selanjutnya!

*WigaEvent*"

        ]);

        $data->update([
            'status_publish' => true,
            'end_time' => date('Y-m-d H:i:s')
        ]);
        $data->participants()->where('status_publish',false)->update([
            'status_publish' => true,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil mengirim sertifikat peserta",
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
_WigaEvent_";

        if($data->type == 'online') {
            $contentNotify = "Hai, {name}
            
Semoga dalam keadaan baik. Kami ingin mengingatkan Anda untuk bergabung di acara online kami.

*Acara*: {$data->title}
*Waktu*: {$data->date_format}, pukul {$data->time_format}

Anda dapat bergabung melalui tautan berikut:
_{$data->link}_

Pastikan koneksi internet Anda stabil. Sampai jumpa di dunia maya!

Terima kasih,
_WigaEvent_";
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