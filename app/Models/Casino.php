<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Casino extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'logo',
    ];
}
