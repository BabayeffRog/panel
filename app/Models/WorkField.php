<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkField extends Model
{

    protected $fillable = ['dealer_id', 'field'];


    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
}
