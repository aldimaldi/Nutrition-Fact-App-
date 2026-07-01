<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodLog extends Model
{
    protected $fillable = [
        'user_id', 'photo_path', 'food_name', 'gemini_raw_response',
        'calories', 'protein_g', 'carbs_g', 'fat_g', 'fiber_g', 'sugar_g', 'sodium_mg',
        'confidence', 'xp_earned', 'hp_delta', 'status', 'eaten_at',
    ];

    protected $casts = [
        'eaten_at' => 'datetime',
        'confidence' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photoUrl(): string
    {
        return asset('storage/' . $this->photo_path);
    }
}
