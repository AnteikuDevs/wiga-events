<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class ComponentJS extends Model
{

    use HasFilter;
    
    protected $table = 'component_js';

    protected $guarded = [];
}
