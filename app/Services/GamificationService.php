<?php

namespace App\Services;

use App\Models\Character;
use App\Models\NutritionTarget;

/**
 * GamificationService
 *
 * Aturan inti:
 * - XP didapat dari MENCATAT makanan (konsistensi) + bonus jika makanan mendekati
 *   proporsi target harian secara seimbang.
 * - HP berkurang jika makanan membuat total harian MELEBIHI batas gula/sodium/kalori,
 *   HP naik sedikit jika makanan membantu memenuhi target serat/protein yang masih kurang.
 * - Status karakter (healthy/tired/blocked/sick) mengikuti HP saat ini.
 */
class GamificationService
{
    // XP dasar untuk setiap log makanan yang berhasil di-scan
    protected const BASE_XP = 8;
    protected const BONUS_XP_BALANCED = 10;

    protected const HP_PENALTY_OVER_LIMIT = 6;   // per jenis nutrisi yang melebihi target harian
    protected const HP_REWARD_GOOD_CHOICE = 3;

    public function evaluateFoodLog(
        Character $character,
        NutritionTarget $target,
        array $nutrition,
        array $todayTotalsBeforeThisLog
    ): array {
        $xp = self::BASE_XP;
        $hpDelta = 0;

        $todayAfter = [
            'calories'  => $todayTotalsBeforeThisLog['calories'] + $nutrition['calories'],
            'sugar_g'   => $todayTotalsBeforeThisLog['sugar_g'] + $nutrition['sugar_g'],
            'sodium_mg' => $todayTotalsBeforeThisLog['sodium_mg'] + $nutrition['sodium_mg'],
            'protein_g' => $todayTotalsBeforeThisLog['protein_g'] + $nutrition['protein_g'],
            'fiber_g'   => $todayTotalsBeforeThisLog['fiber_g'] + $nutrition['fiber_g'],
        ];

        // Penalti HP jika log ini yang menyebabkan total harian melewati batas
        if ($todayAfter['sugar_g'] > $target->sugar_g && $todayTotalsBeforeThisLog['sugar_g'] <= $target->sugar_g) {
            $hpDelta -= self::HP_PENALTY_OVER_LIMIT;
        }
        if ($todayAfter['sodium_mg'] > $target->sodium_mg && $todayTotalsBeforeThisLog['sodium_mg'] <= $target->sodium_mg) {
            $hpDelta -= self::HP_PENALTY_OVER_LIMIT;
        }
        if ($todayAfter['calories'] > $target->calories && $todayTotalsBeforeThisLog['calories'] <= $target->calories) {
            $hpDelta -= self::HP_PENALTY_OVER_LIMIT;
        }

        // Reward jika makanan ini membantu isi kekurangan protein/serat harian tanpa over-limit lain
        $helpsProtein = $todayAfter['protein_g'] <= $target->protein_g && $nutrition['protein_g'] > 0;
        $helpsFiber   = $todayAfter['fiber_g'] <= $target->fiber_g && $nutrition['fiber_g'] > 0;

        if ($helpsProtein && $helpsFiber && $hpDelta === 0) {
            $hpDelta += self::HP_REWARD_GOOD_CHOICE;
            $xp += self::BONUS_XP_BALANCED;
        }

        return ['xp' => $xp, 'hp_delta' => $hpDelta];
    }

    public function applyToCharacter(Character $character, int $xpGained, int $hpDelta): Character
    {
        $character->hp = max(0, min($character->hp_max, $character->hp + $hpDelta));
        $character->xp += $xpGained;

        while ($character->xp >= $character->xp_to_next_level) {
            $character->xp -= $character->xp_to_next_level;
            $character->level += 1;
            $character->xp_to_next_level = (int) round($character->xp_to_next_level * 1.25);
            $character->hp_max += 5; // naik level = HP max sedikit bertambah
        }

        $character->status = match (true) {
            $character->hp <= 0  => 'sick',
            $character->hp < 30  => 'blocked',
            $character->hp < 60  => 'tired',
            default               => 'healthy',
        };

        $character->save();

        return $character;
    }
}
