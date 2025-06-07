<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(string $slug) {

        $data = Event::where('slug', $slug)->first();

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

        $data = Event::where('slug', $slug)->where('start_time', '>=', date('Y-m-d H:i'))->first();

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $cekPendaftaran = $data->participants()->where('student_id', $request->student_id)->first();

        if ($cekPendaftaran) {
            return response([
                'status' => false,
                'message' => "Anda sudah terdaftar",
            ]);
        }

        $request->validate([
            'name' => 'required',
            'student_id' => 'required',
            'parallel_class' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
        ]);

        $data->participants()->create($request->all());

        return response([
            'status' => true,
            'message' => "Pendaftaran berhasil",
        ]);

    }

    public function attendance(Request $request) {
        
        $request->validate([
            'token' => 'required|exists:event_attendances,token',
            'student_id' => 'required|exists:participants,student_id'
        ]);

        $cekToken = EventAttendance::where('token', $request->token)->first();

        if (!$cekToken) {
            return response([
                'status' => false,
                'message' => "Token tidak ditemukan",
            ]);
        }

        $cekPendaftaran = $cekToken->event->participants()->where('student_id', $request->student_id)->first();

        if (!$cekPendaftaran) {
            return response([
                'status' => false,
                'message' => "Anda belum terdaftar",
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
}
