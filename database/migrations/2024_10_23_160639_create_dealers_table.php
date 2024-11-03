<?php

use App\Enums\Currency;
use App\Enums\DealStatus;
use App\Enums\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('panel_name')->index(); // Frequently used field, indexed
            $table->string('commission_account')->nullable();
            $table->string('test_account')->nullable();
            $table->string('referral_number')->unique()->index();
            $table->decimal('fixed_contract_price', 10, 2)->default(0);
            $table->enum('currency', Currency::values())->default(Currency::USD->value);
            $table->enum('status', DealStatus::values())->default(DealStatus::Beklemede->value);
            $table->decimal('affiliate_commission', 5, 2)->default(1);
            $table->json('work_field')->nullable();
            $table->string('skype_live')->nullable();
            $table->string('skype_group')->nullable();
            $table->string('skype_name')->nullable();
            $table->date('contract_date')->nullable();
            $table->longText('contract_details')->nullable();
            $table->json('work_links')->nullable();
            $table->string('payment_address')->nullable();
            $table->enum('payment_method', Payment::values())->default(Payment::TRC20->value);
            $table->dateTime('last_weekly_commission')->nullable();
            $table->dateTime('last_checked_at')->nullable();
            $table->timestamps();
        });

        // İndekslər
        Schema::table('dealers', function (Blueprint $table) {
            $table->index(['panel_name', 'referral_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
