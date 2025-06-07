<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    use HasFilter;

    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
