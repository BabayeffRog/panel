<?php

use App\Enums\WorkField;
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
        Schema::create('work_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');
            $table->string('field');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_fields');
    }
};
