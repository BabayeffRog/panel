<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPayment extends Model
{
    protected $fillable = [
        'dealer_id',
        'amount_due',
        'currency',
        'payment_address',
        'payment_method',
        'is_sent',
        'note',
        'sent_at',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'work_links' => 'array',
        'payment_info' => 'array',
        'work_field' => 'array',
        'contract_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation to the dealer (bayi)
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    // Relation to the user who created the payment
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation to the user who approved the payment
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Mark the payment as sent and track the user who approved it
    public function markAsSent($userId)
    {
        $this->update([
            'is_sent' => true,
            'approved_at' => now(),
            'approved_by' => $userId, // Who approved the payment
        ]);
    }
}
