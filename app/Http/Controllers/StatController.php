<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatController extends Controller
{
    /**
     * Menampilkan halaman statistik pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        $character = $user->character;
        $target = $user->nutritionTarget;

        // Mengambil data makanan yang berhasil di-scan selama 7 hari terakhir
        $last7DaysLogs = $user->foodLogs()
            ->where('status', 'completed')
            ->where('eaten_at', '>=', Carbon::now()->subDays(7))
            ->get();

        // Kalkulasi Statistik Sederhana
        $totalScans = $last7DaysLogs->count();
        $totalXpEarned = $last7DaysLogs->sum('xp_earned');
        $totalCalories = $last7DaysLogs->sum('calories');
        
        // Rata-rata kalori per hari (dibagi 7 hari)
        $avgCalories = round($totalCalories / 7);

        return view('stats', compact(
            'character', 
            'target', 
            'totalScans', 
            'totalXpEarned', 
            'avgCalories'
        ));
    }
}