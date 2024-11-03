<?php

use App\Enums\Currency;
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
        Schema::create('weekly_commissions', function (Blueprint $table) {
            $table->id();  // Komisyon ID
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');  // Bayi ID
            $table->decimal('amount', 10, 2);  // Komisyon meblağı
            $table->enum('currency', Currency::values())->default(Currency::TL->value);
            $table->date('week_start');  // Həftə Başlanğıcı
            $table->date('week_end');  // Həftə Sonu
            $table->longText('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');  // Komisyonu əlavə edən istifadəçi
            $table->timestamps();  // Yaradılma və yenilənmə tarixi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_comissions');
    }
};
