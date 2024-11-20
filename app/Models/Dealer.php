<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Dealer extends Model
{
    use LogsActivity;

    protected $table = 'dealers';

    // İzləniləcək sahələr
    protected static $logAttributes = ['*'];

    // Yalnız dəyişiklik olarsa log et
    //protected static $logOnlyDirty = true;

    // Logun adı
    protected static $logName = 'dealer_control';

    // getActivitylogOptions metodunu əlavə etmək



    protected $fillable = [
        'panel_name',
        'commission_account',
        'test_account',
        'referral_number',
        'fixed_contract_price',
        'affiliate_commission',
        'work_field',
        'status',
        'skype_live',
        'skype_group',
        'skype_name',
        'contract_date',
        'contract_details',
        'work_links',
        'payment_address',
        'payment_method',
        'last_weekly_commission',
        'last_checked_at',
    ];

    // JSON tipləri üçün laravel castlardan istifadə
    protected $casts = [
        'status' => 'array',
        'payment_info' => 'array',
        'contract_date' => 'date',
        'last_weekly_commission' => 'date',
    ];

    public function hasCommissionForCurrentWeek(): bool
    {
        $currentWeekStart = now()->startOfWeek()->toDateString();
        $currentWeekEnd = now()->endOfWeek()->toDateString();

        return $this->commissions()->where('week_start', $currentWeekStart)
            ->where('week_end', $currentWeekEnd)
            ->exists();
    }
    public function commissions()
    {
        return $this->hasMany(WeeklyCommission::class, 'dealer_id');
    }

    public function checks()
    {
        return $this->hasMany(DealerCheck::class);
    }
    public function lastCheckedBy()
    {
        return $this->belongsTo(User::class, 'last_checked_by');
    }

    public function lastChecked()
    {
        return $this->hasOne(DealerCheck::class)->latestOfMany('checked_at');
    }
    public function getWeekRangeText(): string
    {
        $commission = $this->commissions()
            ->where('week_start', now()->startOfWeek())
            ->where('week_end', now()->endOfWeek())
            ->first();

        if ($commission) {
            return $commission->week_start->format('d-m-Y') . ' - ' . $commission->week_end->format('d-m-Y') . ' Eklendi';
        }

        return '';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['panel_name', 'commission_account', 'test_account', 'referral_number']) // İzləniləcək sahələr
            ->useLogName('dealer_control') // Logun adı
            ->logOnlyDirty(); // Yalnız dəyişiklik olduqda logla
    }


    public function WorkLinks(): HasMany
    {
        return $this->hasMany(DealerLink::class);
    }

    public function workFields(): HasMany
    {
        return $this->hasMany(WorkField::class);
    }
}
