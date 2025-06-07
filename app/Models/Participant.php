<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFilter;

    protected $guarded = [];

    public const TYPE_PARTICIPANT = 'participant';
    public const TYPE_COMMITTEE = 'committee';

    public function attendance()
    {
        return $this->hasOne(ParticipantAttendance::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function certificate()
    {
        return $this->hasOne(ParticipantCertificate::class);
    }
}
