<?php

namespace App\Jobs;

use App\Models\FoodLog;
use App\Services\GamificationService;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessFoodPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Kalau Gemini timeout/limit, coba lagi maksimal 3x dengan jeda
    public int $tries = 3;
    public array $backoff = [10, 30, 60];

    public function __construct(protected FoodLog $foodLog) {}

    public function handle(GeminiService $gemini, GamificationService $gamification): void
    {
        $log = $this->foodLog;
        $user = $log->user;

        try {
            $absolutePath = storage_path('app/public/' . $log->photo_path);
            $nutrition = $gemini->analyzeFoodPhoto($absolutePath);

            $character = $user->character ?? \App\Models\Character::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'level' => 1,
                    'hp' => 100,
                    'hp_max' => 100,
                    'xp' => 0,
                    'xp_to_next_level' => 100,
                    'status' => 'healthy'
                ]
            );

            // PENYEMBUH BUG: Jika target nutrisi belum ada, buatkan target default 2000 Kalori
            $target = $user->nutritionTarget ?? \App\Models\NutritionTarget::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'calories' => 2000,
                    'protein_g' => 60,
                    'carbs_g' => 250,
                    'fat_g' => 60,
                    'fiber_g' => 30,
                    'sugar_g' => 50,
                    'sodium_mg' => 2000
                ]
            );

            $todayTotals = FoodLog::query()
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('eaten_at', $log->eaten_at->toDateString())
                ->where('id', '!=', $log->id)
                ->selectRaw('
                    COALESCE(SUM(calories),0) as calories,
                    COALESCE(SUM(sugar_g),0) as sugar_g,
                    COALESCE(SUM(sodium_mg),0) as sodium_mg,
                    COALESCE(SUM(protein_g),0) as protein_g,
                    COALESCE(SUM(fiber_g),0) as fiber_g
                ')
                ->first()
                ->toArray();

            $result = $gamification->evaluateFoodLog($character, $target, $nutrition, $todayTotals);

            $log->update([
                'food_name' => $nutrition['food_name'],
                'calories' => $nutrition['calories'],
                'protein_g' => $nutrition['protein_g'],
                'carbs_g' => $nutrition['carbs_g'],
                'fat_g' => $nutrition['fat_g'],
                'fiber_g' => $nutrition['fiber_g'],
                'sugar_g' => $nutrition['sugar_g'],
                'sodium_mg' => $nutrition['sodium_mg'],
                'confidence' => $nutrition['confidence'],
                'gemini_raw_response' => $nutrition['raw'],
                'xp_earned' => $result['xp'],
                'hp_delta' => $result['hp_delta'],
                'status' => 'completed',
            ]);

            $gamification->applyToCharacter($character, $result['xp'], $result['hp_delta']);
        } catch (Throwable $e) {
            // ==========================================
            // --- MULAI MODE PEMBEDAHAN TERMINAL ---
            // ==========================================
            echo "\n❌ [SYSTEM HALTED] ERROR TERDETEKSI!\n";
            echo "Pesan   : " . $e->getMessage() . "\n";
            echo "File    : " . $e->getFile() . "\n";
            echo "Baris   : " . $e->getLine() . "\n";
            echo "==========================================\n\n";
            // ==========================================

            report($e);

            // Kalau ini percobaan terakhir, tandai gagal supaya frontend berhenti polling
            if ($this->attempts() >= $this->tries) {
                $log->update(['status' => 'failed']);
            }

            throw $e; // biar Laravel retry sesuai $tries/$backoff
        }
    }

    public function failed(Throwable $e): void
    {
        $this->foodLog->update(['status' => 'failed']);
    }
}
