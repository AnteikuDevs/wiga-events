<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class ParticipantCertificate extends Model
{
    use HasFilter;

    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function certificateTemplate()
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function certificateFile()
    {
        return $this->belongsTo(MyStorage::class, 'certificate_file_id');
    }


}
