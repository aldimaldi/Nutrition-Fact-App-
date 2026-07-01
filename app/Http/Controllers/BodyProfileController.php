<?php

namespace App\Http\Controllers;

use App\Models\BodyProfile;
use App\Models\Character;
use App\Models\NutritionTarget;
use App\Services\TdeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BodyProfileController extends Controller
{
    public function __construct(protected TdeeService $tdee) {}

    /**
     * POST /body-profile
     * Dipanggil sekali saat onboarding (setelah register), atau saat user
     * update berat badan / tingkat aktivitas dari halaman Profile.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before:today',
            'height_cm' => 'required|numeric|min:100|max:250',
            'weight_kg' => 'required|numeric|min:30|max:300',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            'goal' => 'required|in:lose_weight,maintain,gain_weight',
        ]);

        $user = Auth::user();

        $profile = BodyProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        // Hitung ulang target nutrisi otomatis dari TDEE setiap kali profil berubah
        $targets = $this->tdee->calculateDailyTargets($profile);

        NutritionTarget::updateOrCreate(
            ['user_id' => $user->id],
            $targets
        );

        // Buat karakter default jika user baru pertama kali isi profil
        Character::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Mochi',
                'avatar' => 'bunny',
                'level' => 1,
                'xp' => 0,
                'xp_to_next_level' => 120,
                'hp' => 100,
                'hp_max' => 100,
                'status' => 'healthy',
            ]
        );

        return response()->json([
            'profile' => $profile,
            'nutrition_target' => $targets,
        ]);
    }
}
