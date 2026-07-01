<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('photo_path');
            $table->string('food_name')->nullable();          // hasil identifikasi Gemini
            $table->text('gemini_raw_response')->nullable();   // simpan JSON mentah untuk audit/debug

            $table->unsignedInteger('calories')->default(0);
            $table->unsignedInteger('protein_g')->default(0);
            $table->unsignedInteger('carbs_g')->default(0);
            $table->unsignedInteger('fat_g')->default(0);
            $table->unsignedInteger('fiber_g')->default(0);
            $table->unsignedInteger('sugar_g')->default(0);
            $table->unsignedInteger('sodium_mg')->default(0);

            $table->float('confidence')->nullable();  // 0-1, seberapa yakin Gemini dengan estimasinya
            $table->integer('xp_earned')->default(0);
            $table->integer('hp_delta')->default(0);   // dampak ke HP karakter (+/-)

            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->timestamp('eaten_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'eaten_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_logs');
    }
};
