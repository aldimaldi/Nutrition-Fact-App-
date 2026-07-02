<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFoodPhotoJob;
use App\Models\FoodLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodScanController extends Controller
{
    /**
     * GET /dashboard
     * Menampilkan halaman utama (dashboard) beserta log makanan.
     */
    public function dashboard() // <--- NAMA FUNGSI DIUBAH MENJADI dashboard()
    {
        $user = Auth::user();

        // 1. Data Koleksi Lengkap: Digunakan KHUSUS untuk kalkulasi gizi harian di Blade
        $todayAllLogs = $user->foodLogs()
            ->whereDate('eaten_at', Carbon::today())
            ->get();

        // 2. Data Terpisah untuk Tampilan: Digunakan KHUSUS untuk daftar riwayat (list)
        // Kita memotongnya menjadi 5 item per halaman dengan paginate(5)
        $foodLogsList = $user->foodLogs()
            ->whereDate('eaten_at', Carbon::today())
            ->latest('eaten_at')
            ->paginate(5);

        return view('dashboard', [
            'character'    => $user->character,
            'target'       => $user->nutritionTarget,
            'todayLogs'    => $todayAllLogs,   
            'foodLogsList' => $foodLogsList,   
        ]);
    }

    /**
     * POST /food-logs/scan
     * multipart/form-data: photo (file)
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
     * Riwayat log makanan user, terbaru dulu (Khusus respons JSON API)
     */
    public function index(Request $request)
    {
        // Ambil data log, urutkan dari yang terbaru, dan batasi 10 item per halaman
        $logs = FoodLog::where('user_id', Auth::id())
            ->orderByDesc('eaten_at')
            ->paginate(10);

        return view('foodLogs', compact('logs'));
    }
}