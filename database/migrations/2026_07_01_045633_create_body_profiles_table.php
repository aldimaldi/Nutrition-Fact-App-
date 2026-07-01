<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->unsignedSmallInteger('height_cm');
            $table->float('weight_kg');
            $table->enum('activity_level', [
                'sedentary',    // jarang olahraga / kerja duduk
                'light',        // olahraga ringan 1-3x/minggu
                'moderate',     // olahraga sedang 3-5x/minggu
                'active',       // olahraga berat 6-7x/minggu
                'very_active',  // olahraga sangat berat / kerja fisik
            ])->default('sedentary');
            $table->enum('goal', ['lose_weight', 'maintain', 'gain_weight'])->default('maintain');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_profiles');
    }
};
