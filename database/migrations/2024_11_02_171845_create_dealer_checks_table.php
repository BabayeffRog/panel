<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealer_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade'); // Dealer ID
            $table->foreignId('checked_by')->constrained('users')->onDelete('cascade'); // Kontrol edən user ID
            $table->timestamp('checked_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kontrol vaxtı
            $table->longText('note')->nullable(); // Əlavə qeyd
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_checks');
    }
};
