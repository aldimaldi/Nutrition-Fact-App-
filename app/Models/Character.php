<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    protected $fillable = [
        'user_id', 'name', 'avatar', 'level', 'xp', 'xp_to_next_level',
        'hp', 'hp_max', 'status', 'last_hp_regen_at',
    ];

    protected $casts = [
        'last_hp_regen_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
