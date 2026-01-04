<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Participant;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use WigaStorage;

class EventController extends Controller
{
    private function generateRegistrationCode(Event $event)
    {
        return strtoupper('REG-'.str_random(6));
    }

    public function show(string $slug) {

        $data = Event::where('slug', $slug)->withCount('participants')->whereHas('certificates', function($query){
            $query->where('is_default', 1);
        })->first();

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

    public function store(Request $request,string $slug)
    {

        $data = Event::where('slug', $slug)->first();

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $cekPendaftaran = $data->participants()->where('name', $request->name)->where('agency', $request->agency)->where(function($query) use ($request){
            $query->where('email', $request->email)->orWhere('phone_number', $request->phone_number);
        })->first();

        if ($cekPendaftaran) {
            return response([
                'status' => false,
                'message' => "Anda sudah terdaftar",
            ]);
        }

        $request->validate([
            'name' => 'required',
            'agency' => 'required',
            'email' => 'required|email|unique:participants,email,NULL,id,event_id,'.$data->id,
            // 'parallel_class' => 'required',
            // 'email' => 'required|email',
            'phone_number' => 'required|starts_with:08|unique:participants,phone_number,NULL,id,event_id,'.$data->id,
        ],[
            'phone_number.starts_with' => 'Masukkan Nomor Telepon dengan benar sesuai petunjuk',
        ]);

        $kodeRegistrasi = $this->generateRegistrationCode($data);

        $certDefault = $data->certificates()->where('is_default', 1)->first();

        $request->merge([
            'type' => Participant::TYPE_PARTICIPANT,
            'reg_code' => $kodeRegistrasi,
            'certificate_template_id' => $certDefault->id,
            'certificate_as' => $certDefault->certificate_as,
            'status' => $data->registration_fee > 0 ? 0 : 1
        ]);
        
        $data->participants()->create($request->all());

        FonnteService::send([
    'target' => implode(',', [$request->phone_number . '|' . $request->name . '|' . $request->reg_code]),
    'message' => "Halo *{name}*, 

*Pendaftaran Berhasil!* ✅

Terima kasih telah mendaftar di acara: 
📌 *{$data->title}*

Berikut adalah data registrasi Anda:
🎫 Kode Registrasi : *{var1}*
🏢 Instansi : {$request->agency}

Silakan simpan atau unduh bukti pendaftaran Anda melalui link berikut:
👉 " . route('event.reg-code.generate', $request->reg_code) . "

*Informasi Penting:*
- Simpan kode registrasi ini untuk proses *check-in* di lokasi acara.
- Pastikan hadir tepat waktu sesuai jadwal yang tertera.

Sampai jumpa di lokasi! 👋
_WigaEvent_"
]);

        return response([
            'status' => true,
            'message' => "Pendaftaran berhasil",
            'data' => [
                'reg_code' => $kodeRegistrasi,
            ]
        ]);

    }

    public function attendance(Request $request) {
        
        $request->validate([
            'token' => 'required|exists:event_attendances,token',
            'reg_code' => 'required',
        ],[
            'reg_code.required' => 'Nomor Registrasi harus diisi',
            // 'reg_code.exists' => 'Nomor Registrasi tidak ditemukan',
        ]);

        $cekToken = EventAttendance::where('token', $request->token)->first();

        if (!$cekToken) {
            return response([
                'status' => false,
                'message' => "Token tidak ditemukan",
            ]);
        }

        $cekPendaftaran = $cekToken->event->participants()->where('reg_code', "REG-".$request->reg_code)->first();
        if (!$cekPendaftaran) {
            return response([
                'status' => false,
                'message' => "Nomor Registrasi tidak ditemukan",
            ]);
        }

        if($cekPendaftaran->status != '1')
        {
            return response([
                'status' => false,
                'message' => "Pastikan anda telah menyelesaikan pendaftaran",
            ]);
        }

        $cekKehadiran = $cekPendaftaran->attendance;

        if ($cekKehadiran) {
            return response([
                'status' => false,
                'message' => "Anda telah melakukan kehadiran",
            ]);
        }

        $attendanceData = $cekPendaftaran->attendance()->create(['event_id' => $cekToken->event_id]);

        return response([
            'status' => true,
            'message' => "Kehadiran berhasil",
            'data' => $attendanceData
        ]);

    }

    public function index(Request $request)
    {
        $query = Event::with(['image', 'categories'])->withCount('participants');

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $data = $query->latest()->paginate(9);
        
        return response([
            'status' => true,
            'data' => $data
        ]);
    }

    public function categoryEvent(Request $request)
    {
        // Mengambil kategori yang setidaknya memiliki satu event
        // has('events') memastikan hanya kategori yang terhubung ke tabel event yang diambil
        $data = Category::has('events')
            ->select('id', 'name', 'slug')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Belum ada kategori yang digunakan pada event apapun.'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function regShow(Request $request, string $id) {

        $participant = Participant::where('reg_code', "REG-".$id)->with(['event'])->first();

        if (!$participant) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        return response([
            'status' => true,
            'data' => $participant
        ]);

    }

    public function payment(Request $request, $code)
    {

        $participant = Participant::where('reg_code', "REG-".$code)->with(['event'])->first();

        if (!$participant) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        if($participant->event->registration_fee < 1) {
            return response([
                'status' => false,
                'message' => "Anda tidak perlu melakukan pembayaran",
            ]);
        }
        
        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $paymentProof = $participant->proof_of_payment_id;

        if($paymentProof) {
            WigaStorage::update('image', $paymentProof);
        }else{
            $paymentProof = WigaStorage::store('proof_of_payment', 'events/payment')->id();
        }

        $participant->update([
            'proof_of_payment_id' => $paymentProof
        ]);

        return response([
            'status' => true,
            'data' => $participant
        ]);

    }

}
