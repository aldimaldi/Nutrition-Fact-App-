<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFoodPhotoJob;
use App\Models\FoodLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodScanController extends Controller
{
    /**
     * POST /food-logs/scan
     * multipart/form-data: photo (file)
     *
     * Hanya menyimpan foto & membuat record berstatus "processing",
     * lalu melempar job ke queue. Response langsung balik tanpa menunggu Gemini.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:8192', // max 8MB
        ]);

        $user = Auth::user();
        $path = $request->file('photo')->store('food-photos', 'public');

        $log = FoodLog::create([
            'user_id' => $user->id,
            'photo_path' => $path,
            'status' => 'processing',
            'eaten_at' => now(),
        ]);

        ProcessFoodPhotoJob::dispatch($log);

        return response()->json([
            'message' => 'Foto sedang dianalisis...',
            'food_log' => $log,
        ], 202); // 202 Accepted: diterima, masih diproses
    }

    /**
     * GET /food-logs/{foodLog}
     * Dipanggil frontend untuk polling status (processing -> completed/failed)
     */
    public function show(FoodLog $foodLog)
    {
        abort_unless($foodLog->user_id === Auth::id(), 403);

        return response()->json([
            'food_log' => $foodLog,
            'character' => $foodLog->status === 'completed' ? Auth::user()->character : null,
        ]);
    }

    /**
     * GET /food-logs
     * Riwayat log makanan user, terbaru dulu
     */
    public function index(Request $request)
    {
        $logs = FoodLog::where('user_id', Auth::id())
            ->orderByDesc('eaten_at')
            ->paginate(20);

        return response()->json($logs);
    }
}
