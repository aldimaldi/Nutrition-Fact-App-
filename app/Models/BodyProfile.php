<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyProfile extends Model
{
    protected $fillable = [
        'user_id', 'gender', 'birth_date', 'height_cm',
        'weight_kg', 'activity_level', 'goal',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
