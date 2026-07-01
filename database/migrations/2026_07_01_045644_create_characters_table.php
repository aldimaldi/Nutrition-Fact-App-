<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Mochi');
            $table->string('avatar')->default('bunny');
            $table->unsignedInteger('level')->default(1);
            $table->unsignedInteger('xp')->default(0);
            $table->unsignedInteger('xp_to_next_level')->default(120);
            $table->unsignedInteger('hp')->default(100);
            $table->unsignedInteger('hp_max')->default(100);
            $table->enum('status', ['healthy', 'tired', 'blocked', 'sick'])->default('healthy');
            $table->date('last_hp_regen_at')->nullable();
            $table->timestamps();
        });

        // Target nutrisi harian per user (dihitung dari TDEE / preferensi)
        Schema::create('nutrition_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('calories')->default(2000);
            $table->unsignedInteger('protein_g')->default(60);
            $table->unsignedInteger('carbs_g')->default(250);
            $table->unsignedInteger('fat_g')->default(65);
            $table->unsignedInteger('fiber_g')->default(30);
            $table->unsignedInteger('sugar_g')->default(50);
            $table->unsignedInteger('sodium_mg')->default(2000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_targets');
        Schema::dropIfExists('characters');
    }
};
