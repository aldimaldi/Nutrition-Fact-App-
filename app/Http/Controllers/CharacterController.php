<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    /**
     * Menampilkan halaman pengaturan karakter.
     */
    public function index()
    {
        $character = Auth::user()->character;
        return view('character', compact('character'));
    }

    /**
     * Memproses form pembaruan karakter.
     */
    public function update(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'name' => 'required|string|max:20',
            'avatar' => 'required|in:bunny,cat,bear', // Daftar avatar yang tersedia
        ]);

        $user = Auth::user();
        $character = $user->character;

        // Memperbarui data jika karakter sudah ada
        if ($character) {
            $character->update([
                'name' => $request->name,
                'avatar' => $request->avatar,
            ]);
        } else {
            // Jaring pengaman: Jika entah bagaimana karakter belum ada, buat baru
            $user->character()->create([
                'name' => $request->name,
                'avatar' => $request->avatar,
                'level' => 1,
                'hp' => 100,
                'hp_max' => 100,
                'xp' => 0,
                'xp_to_next_level' => 100,
                'status' => 'healthy',
            ]);
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Karakter berhasil diperbarui!');
    }
}