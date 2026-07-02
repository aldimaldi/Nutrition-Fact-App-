<?php

namespace Database\Seeders;

use App\Models\Award;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama jika ada agar tidak duplikat saat testing ulang
        Award::truncate();

        // Masukkan data lencana master ke database
        Award::create([
            'name' => 'First Bite',
            'description' => 'Berhasil melakukan scan makanan pertama kali.',
            'icon' => '🍎',
            'criteria_type' => 'scan',
            'criteria_value' => 1,
        ]);

        Award::create([
            'name' => 'Consistent Eater',
            'description' => 'Memindai lebih dari 5 porsi makanan.',
            'icon' => '🍽️',
            'criteria_type' => 'scan',
            'criteria_value' => 5,
        ]);

        Award::create([
            'name' => 'Nutrition Novice',
            'description' => 'Karaktermu berhasil mencapai Level 2.',
            'icon' => '⭐',
            'criteria_type' => 'level',
            'criteria_value' => 2,
        ]);

        Award::create([
            'name' => 'Macro Master',
            'description' => 'Karaktermu berevolusi mencapai Level 5.',
            'icon' => '👑',
            'criteria_type' => 'level',
            'criteria_value' => 5,
        ]);

        Award::create([
            'name' => 'Dietary Hero',
            'description' => 'Memindai 20 makanan. Pahlawan gizi sejati!',
            'icon' => '%F0%9F%A5%B8', // Emoji 🦸
            'criteria_type' => 'scan',
            'criteria_value' => 20,
        ]);
    }
}
