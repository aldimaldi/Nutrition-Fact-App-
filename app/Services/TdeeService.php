<?php

namespace App\Services;

use App\Models\BodyProfile;

/**
 * TdeeService
 *
 * Menghitung Total Daily Energy Expenditure memakai formula Mifflin-St Jeor
 * (paling akurat & umum dipakai dibanding Harris-Benedict), lalu memecahnya
 * jadi target makro harian.
 */
class TdeeService
{
    protected const ACTIVITY_FACTORS = [
        'sedentary'   => 1.2,
        'light'       => 1.375,
        'moderate'    => 1.55,
        'active'      => 1.725,
        'very_active' => 1.9,
    ];

    // Penyesuaian kalori berdasarkan goal (defisit/surplus aman)
    protected const GOAL_ADJUSTMENT = [
        'lose_weight' => -500,
        'maintain'    => 0,
        'gain_weight' => 300,
    ];

    public function calculateBmr(BodyProfile $profile): float
    {
        $age = $profile->birth_date->age;

        // Mifflin-St Jeor
        $base = (10 * $profile->weight_kg) + (6.25 * $profile->height_cm) - (5 * $age);

        return $profile->gender === 'male' ? $base + 5 : $base - 161;
    }

    public function calculateTdee(BodyProfile $profile): float
    {
        $bmr = $this->calculateBmr($profile);
        $factor = self::ACTIVITY_FACTORS[$profile->activity_level];

        return $bmr * $factor;
    }

    /**
     * Kembalikan target harian siap disimpan ke tabel nutrition_targets.
     */
    public function calculateDailyTargets(BodyProfile $profile): array
    {
        $tdee = $this->calculateTdee($profile);
        $calories = max(1200, $tdee + self::GOAL_ADJUSTMENT[$profile->goal]); // jangan pernah di bawah 1200 kkal

        // Protein: 1.6 g/kg (cukup untuk mempertahankan/membangun massa otot)
        $proteinG = round($profile->weight_kg * 1.6);

        // Lemak: 25% dari total kalori
        $fatG = round(($calories * 0.25) / 9);

        // Sisa kalori untuk karbohidrat
        $remainingCalories = $calories - ($proteinG * 4) - ($fatG * 9);
        $carbsG = round(max(0, $remainingCalories) / 4);

        // Serat: 14g per 1000 kkal (rekomendasi umum)
        $fiberG = round(($calories / 1000) * 14);

        // Gula: maksimal 10% dari kalori (rekomendasi WHO)
        $sugarG = round(($calories * 0.10) / 4);

        // Sodium: rekomendasi umum WHO, tidak tergantung TDEE
        $sodiumMg = 2000;

        return [
            'calories'  => (int) round($calories),
            'protein_g' => (int) $proteinG,
            'carbs_g'   => (int) $carbsG,
            'fat_g'     => (int) $fatG,
            'fiber_g'   => (int) $fiberG,
            'sugar_g'   => (int) $sugarG,
            'sodium_mg' => (int) $sodiumMg,
        ];
    }
}
