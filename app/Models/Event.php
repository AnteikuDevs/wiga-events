<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFilter;

    protected $guarded = [];

    protected $appends = [
        'start_time_format',
        'end_time_format',
        'status_id',
        'status',
        'date_format',
        'time_format',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function getStartTimeFormatAttribute()
    {
        return formatDateIndo($this->start_time);
    }
    
    public function getDateFormatAttribute()
    {
        return date('Y-m-d', strtotime($this->start_time)) == ($this->end_time? date('Y-m-d', strtotime($this->end_time)) : '')? formatDateIndo($this->start_time,false) : formatDateIndo($this->start_time) . ' - ' . formatDateIndo($this->end_time);
    }

    public function getTimeFormatAttribute()
    {
        return date('H:i', strtotime($this->start_time)) . ' s.d ' . ($this->end_time? date('H:i', strtotime($this->end_time)) : 'Selesai');
    }

    public function getEndTimeFormatAttribute()
    {
        return formatDateIndo($this->end_time);
    }

    public function getStatusIdAttribute()
    {
        if ($this->start_time->isPast() && (is_null($this->end_time) || $this->end_time->isFuture())) {
            return 1; // Berlangsung
        }
        if ($this->end_time?->isPast() || (is_null($this->end_time) && $this->start_time->isPast())) {
            return 2; // Selesai
        }
        return 0; // Belum Mulai
    }

    public function getStatusAttribute()
    {
        switch ($this->status_id) { // Memanggil accessor getStatusIdAttribute()
            case 1:
                return 'Berlangsung';
            case 2:
                return 'Selesai';
            default:
                return 'Belum Di Mulai';
        }
    }

    

    public function image()
    {
        return $this->belongsTo(MyStorage::class, 'image_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function certificates()
    {
        return $this->hasMany(CertificateTemplate::class);
    }
}
