<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerLink extends Model
{
    protected $fillable = ['dealer_id', 'field_url'];

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }
}
