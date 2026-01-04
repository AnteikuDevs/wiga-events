<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventParticipantController extends Controller
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

        $data = Participant::with(['event','attendance','proof_of_payment','certificateTemplate'])->where('event_id', $id);

        $result = WigaTable($data, $request, function($query,$search){

            $searchType = '';

            if($search == 'peserta' || $search == 'participant')
            {
                $searchType = Participant::TYPE_PARTICIPANT;
            }

            if($search == 'panitia' || $search == 'committee')
            {
                $searchType = Participant::TYPE_COMMITTEE;
            }
            
            $query->filterLike($search, ['name','agency','email','phone_number','certificate_as','reg_code'])->orWhere('type','like',"%$searchType%");
        }, [
            'id',
            'name',
            'agency',
            'phone_number',
            'reg_code',
            null,
            'certificate_as'
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
            'type' => 'required|in:participant,committee',
            'certificate_as' => 'required'
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
            'type' => $request->type,
            'reg_code' => $regCodeFormat,
            'certificate_as' => $request->certificate_as
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
            'type' => 'required|in:participant,committee',
            'certificate_as' => 'required'
        ],[
            'phone_number.starts_with' => 'Masukkan Nomor Telepon dengan benar sesuai petunjuk',
        ]);


        $participant->update([
            'name' => $request->name,
            'agency' => $request->agency,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'type' => $request->type,
            'certificate_as' => $request->certificate_as
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

    public function verify(Request $request, string $id,string $participant_id)
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

        $user = $data->participants()->find($participant_id);

        if (!$user) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        if($user->status == 1){
            return response([
                'status' => false,
                'message' => "Peserta sudah disetujui",
            ]);
        }
        
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

        $msg = $request->status == 'approve' ? "Peserta disetujui" : "Peserta ditolak";

        return response([
            'status'  => true,
            'message' => "$msg",
        ]);
    }

    public function changeModel(Request $request, string $id,string $participant_id)
    {

        $request->validate([
            'certificate_template' => 'required|exists:certificate_templates,id,event_id,'.$id,
            'certificate_as' => 'required'
        ]);

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

        $user = $data->participants()->find($participant_id);

        if (!$user) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $user->update([
            'certificate_template_id' => $request->certificate_template,
            'certificate_as' => $request->certificate_as
        ]);

        return response([
            'status'  => true,
            'message' => "Berhasil mengubah data",
        ]);
    }


}
