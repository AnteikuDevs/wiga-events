<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('event.index',[
            'title' => "Event",
            'js' => componentJS('event/index'),
        ]);
    }
    
    public function show(string $slug)
    {

        $data = Event::where('slug', $slug)->whereHas('certificates', function($query){
            $query->where('is_default', 1);
        })->firstOrFail();

        return view('event.show',[
            'title' => $data->title,
            'js' => componentJS('event/show'),
        ]);

    }
    
    public function attendance(string $token)
    {

        $data = EventAttendance::where('token', $token)->firstOrFail();

        if($data->expired_at < now())
        {
            return view('event.finished');
        }

        // if($data->event->status_id == '0')
        // {
        //     return view('event.comingsoon');
        // }

        // if($data->event->status_id == '2')
        // {
        //     return view('event.finished');
        // }

        return view('event.attendance',[
            'title' => "Kehadiran Event - " . $data->event->title,
            'event' => $data->event,
            'js' => componentJS('event/attendance'),
        ]);

    }

    public function showQr(string $token)
    {

        $data = EventAttendance::where('token', $token)->firstOrFail();

        if($data->expired_at < now())
        {
            return view('event.finished');
        }

        if($data->event->status_id == '2')
        {
            return view('event.finished');
        }

        return view('event.qr',[
            'title' => "Kehadiran Event - " . $data->event->title,
            'event' => $data->event,
            'qr_path' => $data->qr_code_path,
        ]);

    }

    public function regCodeGenerate(string $reg_code)
    {
        

        $data = Event::whereHas('participants', function($query) use ($reg_code) {
            $query->where('reg_code', "REG-".$reg_code);
        })->firstOrFail();

        $participant = $data->participants()->where('reg_code', "REG-".$reg_code)->first();
        
        return view('event.registration-code',[
            'title' => "Kode Registrasi - " . $data->title,
            'event' => $data,
            'participant' => $participant,
            'js' => componentJS('event/registration-code'),
        ]);

    }
}
