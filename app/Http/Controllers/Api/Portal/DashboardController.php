<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Institution;
use App\Models\Participant;
use App\Models\ParticipantAttendance;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function summary()
    {

        $totalParticipants = Participant::count();
        $totalAttendances = ParticipantAttendance::count();
        $total = Event::count();
        $totalInMonth = Event::whereMonth('created_at', date('m'))->count();
        $participantTotal = Participant::distinct('email')->count('email');

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $totalParticipants = Participant::whereRelation('event', 'created_by', $user->id)->count();
            $totalAttendances = ParticipantAttendance::whereRelation('event', 'created_by', $user->id)->count();

            $total = Event::where('created_by', $user->id)->count();
            $totalInMonth = Event::where('created_by', $user->id)->whereMonth('created_at', date('m'))->count();
            $participantTotal = Participant::whereRelation('event', 'created_by', $user->id)->distinct('email')->count('email');
        }

        $attendanceRate = $totalParticipants > 0 
        ? ($totalAttendances / $totalParticipants) * 100 
        : 0;

        return response([
            'status' => true,
            'data' => [
                'event' => [
                    'total' => $total,
                    'total_in_month' => $totalInMonth,
                ],
                'average_attendance' => round($attendanceRate, 2).'%',
                'participant_total' => $participantTotal
            ]
        ]);

    }
}
