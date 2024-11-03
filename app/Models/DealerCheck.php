<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerCheck extends Model
{
    protected $fillable = ['dealer_id', 'checked_by', 'checked_at', 'note'];

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
