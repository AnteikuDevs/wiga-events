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

    // public function certificate()
    // {
    //     return $this->hasOne(ParticipantCertificate::class);
    // }

    public function certificateTemplate()
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    public function proof_of_payment()
    {
        return $this->belongsTo(MyStorage::class, 'proof_of_payment_id');
    }
}
