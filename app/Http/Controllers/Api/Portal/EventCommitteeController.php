<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventCommitteeController extends Controller
{
    
    private function generateRegistrationCode(Event $event)
    {
        return strtoupper('REG-'.str_random(6));;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,string $id)
    {

        $data = Event::find($id);

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = Event::where('created_by', $user->id)->find($id);
        }

        if (!$data) {
            return response(WigaTableResponse([],0,0));
        }

        $data = Participant::with(['event','attendance'])->where('event_id', $id)->where('type', Participant::TYPE_COMMITTEE);

        $result = WigaTable($data, $request, function($query,$search){
            $query->filterLike($search, ['name','agency','email','phone_number']);
        }, [
            'id',
            'agency',
            'name',
            'email',
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
        
        $request->validate([
            'name' => 'required',
            'agency' => 'required',
            'email' => 'required|email|unique:participants,email,NULL,id,event_id,'.$id,
            'phone_number' => 'required|starts_with:08|unique:participants,phone_number,NULL,id,event_id,'.$id,
        ],[
            'phone_number.starts_with' => 'Masukkan Nomor Telepon dengan benar sesuai petunjuk',
        ]);

        // $cekPhoneNumberByEvent = $data->participants()->where('phone_number', $request->phone_number)->first();
        // $cekStudentIdByEvent = $data->participants()->where('student_id', $request->student_id)->first();

        // if ($cekPhoneNumberByEvent || $cekStudentIdByEvent) {
        //     return response([
        //         'errors' => [
        //             'phone_number' => [
        //                 'Nomor telepon sudah terdaftar.'
        //             ],
        //             ...($cekStudentIdByEvent ? ['student_id' => ['NIM sudah terdaftar']] : [])
        //         ],
        //         'message' => 'Nomor telepon sudah terdaftar. '.($cekStudentIdByEvent ? ' (and 1 more errors)' : '')
        //     ]);
        // }

        $regCodeFormat = $this->generateRegistrationCode($data);

        $data->participants()->create([
            'name' => $request->name,
            'agency' => $request->agency,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'type' => Participant::TYPE_COMMITTEE,
            'reg_code' => $regCodeFormat,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil menambahkan data",
        ]);

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id,string $participant_id)
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

        $participant = $data->participants()->find($participant_id);

        if (!$participant) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $request->validate([
            'name' => 'required',
            'agency' => 'required',
            'email' => 'required|email|unique:participants,email,'.$participant_id.',id,event_id,'.$id,
            'phone_number' => 'required|starts_with:08|unique:participants,phone_number,'.$participant_id.',id,event_id,'.$id,
        ],[
            'phone_number.starts_with' => 'Masukkan Nomor Telepon dengan benar sesuai petunjuk',
        ]);


        $participant->update([
            'name' => $request->name,
            'agency' => $request->agency,
            'email' => $request->email,
            'phone_number' => $request->phone_number
        ]);

        if($participant->reg_code == null){
            $regCodeFormat = $this->generateRegistrationCode($data);
            $participant->reg_code = $regCodeFormat;
            $participant->save();
        }

        return response([
            'status' => true,
            'message' => "Berhasil mengubah data",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,string $participant_id)
    {
        $event = Event::find($id);

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $data = Event::where('created_by', $user->id)->find($id);
        }

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
            'message' => "Berhasil menghapus data",
        ]);

    }
}
