<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $table = 'passwords';
    protected $fillable = ['category' ,'login','password'];
}
