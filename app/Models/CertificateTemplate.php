<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFilter;

    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function image()
    {
        return $this->belongsTo(MyStorage::class, 'image_id');
    }
}
