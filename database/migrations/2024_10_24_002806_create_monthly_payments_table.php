<?php

use App\Enums\Currency;
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
        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_due', 10, 2);
            $table->enum('currency', Currency::values())->default(Currency::USD->value);
            $table->string('payment_address')->nullable();
            $table->enum('payment_method', Payment::values())->default(Payment::TRC20->value);
            $table->boolean('is_sent')->default(false);
            $table->longText('note')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_payments');
    }
};
