<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyCommission extends Model
{

    protected $fillable = [
        'dealer_id',
        'amount',
        'currency',
        'week_start',
        'week_end',
        'note',
        'created_by',
        'last_weekly_commission',
    ];

    // Dealer ilə əlaqə (komisyonu alan bayi)
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    // Komisyonu əlavə edən istifadəçi ilə əlaqə
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
