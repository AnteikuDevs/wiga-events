<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(string $slug)
    {

        $data = Event::where('slug', $slug)->firstOrFail();

        return view('event.index',[
            'title' => $data->title,
            'js' => componentJS('event/index'),
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

        if($data->event->status_id == '2')
        {
            return view('event.finished');
        }

        return view('event.attendance',[
            'title' => "Kehadiran Event - " . $data->event->title,
            'event' => $data->event,
            'js' => componentJS('event/attendance'),
        ]);

    }
    
}
