<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class EventParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,string $id)
    {

        $data = Event::find($id);

        if (!$data) {
            return response(WigaTableResponse([],0,0));
        }

        $data = Participant::with(['event','attendance'])->where('event_id', $id)->where('type',Participant::TYPE_PARTICIPANT);

        $result = WigaTable($data, $request, function($query,$search){
            $query->filterLike($search, ['name','student_id','parallel_class','email','phone_number']);
        }, [
            'id',
            'student_id',
            'name',
            'parallel_class',
            // 'email',
            'phone_number'
        ]);

        return response($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,string $id)
    {

        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }
        
        $request->validate([
            'name' => 'required',
            'student_id' => 'required',
            'parallel_class' => 'required',
            // 'email' => 'required|email',
            'phone_number' => 'required|starts_with:08',
        ],[
            'phone_number.starts_with' => 'Masukkan Nomor Telepon dengan benar sesuai petunjuk',
        ]);

        $cekPhoneNumberByEvent = $data->participants()->where('phone_number', $request->phone_number)->first();
        $cekStudentIdByEvent = $data->participants()->where('student_id', $request->student_id)->first();

        if ($cekPhoneNumberByEvent || $cekStudentIdByEvent) {
            return response([
                'errors' => [
                    'phone_number' => [
                        'Nomor telepon sudah terdaftar.'
                    ],
                    ...($cekStudentIdByEvent ? ['student_id' => ['NIM sudah terdaftar']] : [])
                ],
                'message' => 'Nomor telepon sudah terdaftar. '.($cekStudentIdByEvent ? ' (and 1 more errors)' : '')
            ]);
        }

        $data->participants()->create([
            'name' => $request->name,
            'student_id' => $request->student_id,
            'parallel_class' => $request->parallel_class,
            // 'email' => $request->email,
            'phone_number' => $request->phone_number,
            'type' => Participant::TYPE_PARTICIPANT,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil menambahkan panitia",
        ]);

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id,string $participant_id)
    {

        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $participant = $data->participants()->find($participant_id);

        if (!$participant) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $request->validate([
            'name' => 'required',
            'student_id' => 'required',
            'parallel_class' => 'required',
            // 'email' => 'required|email',
            'phone_number' => 'required|starts_with:08',
        ],[
            'phone_number.starts_with' => 'Masukkan Nomor Telepon dengan benar sesuai petunjuk',
        ]);


        $participant->update([
            'name' => $request->name,
            'student_id' => $request->student_id,
            'parallel_class' => $request->parallel_class,
            // 'email' => $request->email,
            'phone_number' => $request->phone_number
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil mengubah panitia",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,string $participant_id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $data = $event->participants()->find($participant_id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $data->delete();

        return response([
            'status' => true,
            'message' => "Berhasil menghapus panitia",
        ]);

    }
}
