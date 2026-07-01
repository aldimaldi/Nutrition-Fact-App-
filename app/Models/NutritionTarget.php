<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionTarget extends Model
{
    protected $fillable = [
        'user_id', 'calories', 'protein_g', 'carbs_g',
        'fat_g', 'fiber_g', 'sugar_g', 'sodium_mg',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
