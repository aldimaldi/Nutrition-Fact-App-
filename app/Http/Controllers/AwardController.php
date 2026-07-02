<?php

namespace App\Http\Controllers;

use App\Models\Award; // <--- Pastikan model di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AwardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $character = $user->character;
        
        // 1. Ambil data kondisi user dari DB
        $totalScans = $user->foodLogs()->where('status', 'completed')->count();
        $level = $character->level ?? 1;

        // 2. Tarik semua data lencana master dari tabel 'awards'
        $dbAwards = Award::all();

        // 3. Lakukan mapping kecocokan syarat secara dinamis (Sangat Elegan!)
        $awards = $dbAwards->map(function ($award) use ($totalScans, $level) {
            $unlocked = false;

            if ($award->criteria_type === 'scan') {
                $unlocked = $totalScans >= $award->criteria_value;
            } elseif ($award->criteria_type === 'level') {
                $unlocked = $level >= $award->criteria_value;
            }

            return [
                'name' => $award->name,
                'desc' => $award->description,
                'icon' => $award->icon,
                'unlocked' => $unlocked,
            ];
    });

        return view('awards', compact('awards', 'totalScans', 'level'));
    }
}