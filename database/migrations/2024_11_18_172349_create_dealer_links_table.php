<?php

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
        Schema::create('dealer_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');
            $table->string('field_url')->nullable(); // Link üçün sütun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_links');
    }
};
