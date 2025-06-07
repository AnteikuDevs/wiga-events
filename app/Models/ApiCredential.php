<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    use HasFilter;
    
    protected $guarded = [];

    public static function findKey($key)
    {
        return self::where('access_key', $key)->first();
    }
}
