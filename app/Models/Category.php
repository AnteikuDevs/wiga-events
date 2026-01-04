<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'created_at_format',
        'updated_at_format',
    ];

    protected function getUpdatedAtFormatAttribute()
    {
        return formatDateIndo($this->updated_at);
    }

    protected function getCreatedAtFormatAttribute()
    {
        return formatDateIndo($this->created_at);
    }

    public function events()
    {
        return $this->hasMany(EventCategory::class);
    }
}
