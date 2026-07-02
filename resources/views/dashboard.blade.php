@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
      <!-- BLOK PERHITUNGAN DINAMIS BLADE -->
      @php
          $completedLogs = $todayLogs->where('status', 'completed');
          $todayCalories = $completedLogs->sum('calories');
          $todayProtein = $completedLogs->sum('protein_g');
          $todayCarbs = $completedLogs->sum('carbs_g');
          $todayFat = $completedLogs->sum('fat_g');
          $todayFiber = $completedLogs->sum('fiber_g');
          $todaySugar = $completedLogs->sum('sugar_g');
          $todaySodium = $completedLogs->sum('sodium_mg');

          $targetCalories = $target->calories ?? 2000;
          $targetProtein = $target->protein_g ?? 60;
          $targetCarbs = $target->carbs_g ?? 250;
          $targetFat = $target->fat_g ?? 60;
          $targetFiber = $target->fiber_g ?? 30;
          $targetSugar = $target->sugar_g ?? 50;
          $targetSodium = $target->sodium_mg ?? 2000;
          
          $currentHp = $character->hp ?? 100;
          $currentXp = $character->xp ?? 0;
          $maxXp = 120; 
          $xpPercentage = min(100, ($currentXp / $maxXp) * 100);
      @endphp

      <div class="topbar">
        <div>
          <h1>Selamat siang, {{ Auth::user()->name }}</h1>
          <p>Karaktermu butuh makan siang yang seimbang</p>
        </div>
        <button class="scan-btn" onclick="document.getElementById('foodPhotoInput').click();">📷 Scan makanan</button>
      </div>

      <section class="stat-card">
        <div>
          <div class="level-badge">Lv. {{ $character->level ?? 1 }}</div>
          <div class="status-chip">Active</div>
        </div>

        <div>
          <div class="bar-row">
            <div class="bar-icon" style="background:#ffe4e6;">❤️</div>
            <div class="bar-track">
              <div class="bar-fill hp" style="width:{{ $currentHp }}%"></div>
              <span class="bar-text">HP {{ $currentHp }} / 100</span>
            </div>
          </div>
          <div class="bar-row">
            <div class="bar-icon" style="background:#fff6d9;">⚡</div>
            <div class="bar-track">
              <div class="bar-fill xp" style="width:{{ $xpPercentage }}%"></div>
              <span class="bar-text">XP {{ $currentXp }} / {{ $maxXp }}</span>
            </div>
          </div>
        </div>

        <div class="cal-ring-wrap">
          <div>
            <div class="cal-num">{{ $todayCalories }} <small style="font-family:'Nunito';font-size:12px;color:var(--ink-soft);">/ {{ $targetCalories }} kcal</small></div>
            <div class="cal-label">🔥 Kalori hari ini</div>
          </div>
        </div>
      </section>

      <section class="macro-grid">
        <div class="macro-card">
          <div class="macro-top"><div class="macro-icon" style="background:#e6f1fb;">🐟</div><div class="macro-name">Protein</div></div>
          <div class="macro-val">{{ $todayProtein }} <small>/{{ $targetProtein }}g</small></div>
          <div class="macro-track"><i style="width:{{ min(100, ($todayProtein / $targetProtein) * 100) }}%;background:var(--protein);"></i></div>
        </div>
        <div class="macro-card">
          <div class="macro-top"><div class="macro-icon" style="background:#eaf7ee;">🌿</div><div class="macro-name">Carbs</div></div>
          <div class="macro-val">{{ $todayCarbs }} <small>/{{ $targetCarbs }}g</small></div>
          <div class="macro-track"><i style="width:{{ min(100, ($todayCarbs / $targetCarbs) * 100) }}%;background:var(--carbs);"></i></div>
        </div>
        <div class="macro-card">
          <div class="macro-top"><div class="macro-icon" style="background:#fff3e0;">🥑</div><div class="macro-name">Fat</div></div>
          <div class="macro-val">{{ $todayFat }} <small>/{{ $targetFat }}g</small></div>
          <div class="macro-track"><i style="width:{{ min(100, ($todayFat / $targetFat) * 100) }}%;background:var(--fat);"></i></div>
        </div>
        <div class="macro-card">
          <div class="macro-top"><div class="macro-icon" style="background:#f4ecfb;">🫐</div><div class="macro-name">Fiber</div></div>
          <div class="macro-val">{{ $todayFiber }} <small>/{{ $targetFiber }}g</small></div>
          <div class="macro-track"><i style="width:{{ min(100, ($todayFiber / $targetFiber) * 100) }}%;background:var(--fiber);"></i></div>
        </div>
        <div class="macro-card">
          <div class="macro-top"><div class="macro-icon" style="background:#fbe9f1;">🍬</div><div class="macro-name">Sugar</div></div>
          <div class="macro-val {{ $todaySugar > $targetSugar ? 'over' : '' }}">{{ $todaySugar }} <small>/{{ $targetSugar }}g</small></div>
          <div class="macro-track"><i style="width:{{ min(100, ($todaySugar / $targetSugar) * 100) }}%;background:var(--sugar);"></i></div>
        </div>
        <div class="macro-card">
          <div class="macro-top"><div class="macro-icon" style="background:#fbe9e8;">🧂</div><div class="macro-name">Sodium</div></div>
          <div class="macro-val {{ $todaySodium > $targetSodium ? 'over' : '' }}">{{ $todaySodium }} <small>/{{ $targetSodium }}mg</small></div>
          <div class="macro-track"><i style="width:{{ min(100, ($todaySodium / $targetSodium) * 100) }}%;background:var(--sodium);"></i></div>
        </div>
      </section>

      <section class="content-grid">
        
        @php
            // Ambil data karakter, jika kosong gunakan default
            $avatarType = $character->avatar ?? 'bunny';
            $charName = $character->name ?? 'Mochi';

            // Tentukan emoji berdasarkan avatar
            $emoji = '🐰';
            if($avatarType === 'cat') $emoji = '🐱';
            elseif($avatarType === 'bear') $emoji = '🐻';
        @endphp

        <div class="character-panel">
          <div class="cloud c1"></div><div class="cloud c2"></div><div class="cloud c3"></div>
          
          <div class="char-name-tag">{{ $emoji }} {{ $charName }}</div>

          @if($avatarType === 'cat')
            <svg class="bunny" viewBox="0 0 210 230" xmlns="http://www.w3.org/2000/svg">
              <ellipse cx="105" cy="216" rx="70" ry="8" fill="rgba(0,0,0,.12)"/>
              <path d="M30 80 Q50 20 85 60 Z" fill="#ffbda3"/>
              <path d="M180 80 Q160 20 125 60 Z" fill="#ffbda3"/>
              <path d="M42 75 Q55 35 75 62 Z" fill="#ffe1d4"/>
              <path d="M168 75 Q155 35 135 62 Z" fill="#ffe1d4"/>
              <circle cx="105" cy="130" r="80" fill="#ffbda3"/>
              <path d="M35 130 a70 70 0 0 0 140 0 z" fill="#ffffff" opacity=".9"/>
              <circle cx="78" cy="118" r="9" fill="#523940"/>
              <circle cx="132" cy="118" r="9" fill="#523940"/>
              <ellipse cx="55" cy="135" rx="8" ry="5" fill="#f4a9be" opacity=".8"/>
              <ellipse cx="155" cy="135" rx="8" ry="5" fill="#f4a9be" opacity=".8"/>
              <ellipse cx="105" cy="135" rx="7" ry="5" fill="#f4a9be"/>
              <path d="M90 142 Q97 152 105 142 Q112 152 120 142" stroke="#523940" stroke-width="3" fill="none" stroke-linecap="round"/>
              <line x1="25" y1="120" x2="50" y2="125" stroke="#523940" stroke-width="2" stroke-linecap="round" opacity="0.5"/>
              <line x1="20" y1="135" x2="45" y2="135" stroke="#523940" stroke-width="2" stroke-linecap="round" opacity="0.5"/>
              <line x1="185" y1="120" x2="160" y2="125" stroke="#523940" stroke-width="2" stroke-linecap="round" opacity="0.5"/>
              <line x1="190" y1="135" x2="165" y2="135" stroke="#523940" stroke-width="2" stroke-linecap="round" opacity="0.5"/>
            </svg>

          @elseif($avatarType === 'bear')
            <svg class="bunny" viewBox="0 0 210 230" xmlns="http://www.w3.org/2000/svg">
              <ellipse cx="105" cy="216" rx="70" ry="8" fill="rgba(0,0,0,.12)"/>
              <circle cx="45" cy="70" r="28" fill="#d4a373"/>
              <circle cx="45" cy="70" r="16" fill="#faedcd"/>
              <circle cx="165" cy="70" r="28" fill="#d4a373"/>
              <circle cx="165" cy="70" r="16" fill="#faedcd"/>
              <circle cx="105" cy="130" r="80" fill="#d4a373"/>
              <circle cx="75" cy="110" r="8" fill="#4a3728"/>
              <circle cx="135" cy="110" r="8" fill="#4a3728"/>
              <ellipse cx="55" cy="125" rx="8" ry="5" fill="#f4a9be" opacity=".8"/>
              <ellipse cx="155" cy="125" rx="8" ry="5" fill="#f4a9be" opacity=".8"/>
              <ellipse cx="105" cy="145" rx="30" ry="24" fill="#faedcd"/>
              <ellipse cx="105" cy="136" rx="12" ry="7" fill="#4a3728"/>
              <path d="M105 143 L105 155" stroke="#4a3728" stroke-width="3" stroke-linecap="round"/>
              <path d="M92 153 Q105 165 118 153" stroke="#4a3728" stroke-width="3" fill="none" stroke-linecap="round"/>
            </svg>

          @else
            <svg class="bunny" viewBox="0 0 210 230" xmlns="http://www.w3.org/2000/svg">
              <ellipse cx="105" cy="216" rx="70" ry="8" fill="rgba(0,0,0,.12)"/>
              <path d="M62 30 Q50 -10 78 5 Q95 55 88 95 Z" fill="#f6c9d6"/>
              <path d="M148 30 Q160 -10 132 5 Q115 55 122 95 Z" fill="#f6c9d6"/>
              <path d="M70 34 Q62 4 80 12 Q92 50 87 82 Z" fill="#fbe1e9"/>
              <path d="M140 34 Q148 4 130 12 Q118 50 123 82 Z" fill="#fbe1e9"/>
              <circle cx="105" cy="130" r="80" fill="#f6c9d6"/>
              <path d="M35 130 a70 70 0 0 0 140 0 z" fill="#ffffff" opacity=".9"/>
              <circle cx="78" cy="118" r="9" fill="#e0355f"/>
              <circle cx="132" cy="118" r="9" fill="#e0355f"/>
              <ellipse cx="105" cy="140" rx="7" ry="5" fill="#c22a4f"/>
              <path d="M92 152 Q105 162 118 152" stroke="#c22a4f" stroke-width="3" fill="none" stroke-linecap="round"/>
              <ellipse cx="55" cy="140" rx="8" ry="5" fill="#f4a9be" opacity=".8"/>
              <ellipse cx="155" cy="140" rx="8" ry="5" fill="#f4a9be" opacity=".8"/>
              <circle cx="167" cy="172" r="10" fill="#fbe1e9"/>
            </svg>
          @endif
        </div>

        <div class="side-panel">
          <div class="panel-card">
            <div class="panel-title">Scan makanan baru</div>
            <div class="drop-zone">
              <div class="camera">📷</div>
              <h3>Seret foto makanan ke sini</h3>
              <p>atau ambil langsung dari kamera</p>
              <button type="button" id="uploadBtn">Pilih foto</button>
            </div>
          </div>

          <div class="panel-card">
            <div class="panel-title">Log makanan hari ini <a href="{{ route('food-logs.index') }}">Lihat semua</a></div>
            
            @forelse($foodLogsList as $log)
            <div class="log-item">
              <div class="log-thumb">
                @if($log->status === 'processing') 
                    ⏳ 
                @elseif($log->photo_path)
                    <img src="{{ asset('storage/' . $log->photo_path) }}" alt="Foto Makanan" loading="lazy">
                @else 
                    🍽️ 
                @endif
              </div>
              <div>
                <div class="log-name">{{ $log->food_name ?? 'Menganalisis foto...' }}</div>
                <div class="log-meta">
                    {{ \Carbon\Carbon::parse($log->eaten_at)->format('H:i') }} · 
                    {{ $log->status === 'processing' ? 'Menghitung nutrisi' : ($log->calories . ' kcal') }}
                </div>
              </div>
              <div class="log-xp" style="background: {{ $log->status === 'processing' ? '#f0ebf2' : '#fff1e6' }};">
                  +{{ $log->xp_earned ?? 0 }} XP
              </div>
            </div>
            @empty
            <div class="log-item" style="border:none; justify-content:center; padding-top:20px;">
              <p style="color:var(--ink-soft); font-size:13px; font-weight:700;">Belum ada makanan yang di-scan hari ini.</p>
            </div>
            @endforelse

            <div class="pagination-container">
                {{ $foodLogsList->links() }}
            </div>
          </div>
        </div>
      </section>

<!-- Input tersembunyi untuk fitur upload -->
<input type="file" id="foodPhotoInput" accept="image/*" capture="environment" hidden>

<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="spinner"></div>
    <p>AI Menganalisis Nutrisi...</p>
</div>
@endsection

@push('scripts')
<script>
const uploadBtn = document.getElementById('uploadBtn');
const fileInput = document.getElementById('foodPhotoInput');
const loadingOverlay = document.getElementById('loadingOverlay');

// Memicu klik pada input file ketika tombol ditekan
uploadBtn.addEventListener('click', () => {
  fileInput.click();
});

// Mengeksekusi API ketika file telah dipilih
fileInput.addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;

  // 1. TAMPILKAN LOADING SCREEN!
  loadingOverlay.style.display = 'flex';

  const formData = new FormData();
  formData.append('photo', file);

  try {
      const res = await fetch('/food-logs/scan', {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData,
      });

      const data = await res.json();
      
      // Jika berhasil di-queue, mulai polling untuk melihat statusnya
      if(data.food_log && data.food_log.id) {
          console.log('Gambar terkirim, memulai proses pemindaian...');
          pollFoodLog(data.food_log.id);
      } else {
          // Sembunyikan loading jika API tidak mengembalikan ID
          loadingOverlay.style.display = 'none';
      }
  } catch (error) {
      console.error('Terjadi kesalahan saat mengunggah:', error);
      // Sembunyikan loading jika gagal koneksi
      loadingOverlay.style.display = 'none';
      alert('Gagal mengunggah foto. Periksa koneksi internetmu.');
  }
});

// Fungsi untuk mengecek status pemindaian AI setiap 2 detik
function pollFoodLog(id) {
  const interval = setInterval(async () => {
    try {
        const res = await fetch(`/food-logs/${id}`);
        const data = await res.json();

        if (data.food_log.status === 'completed') {
          clearInterval(interval);
          // Halaman dimuat ulang otomatis, ini akan mereset tampilan dan loading akan hilang
          window.location.reload();
        } else if (data.food_log.status === 'failed') {
          clearInterval(interval);
          // 2. SEMBUNYIKAN LOADING SCREEN JIKA AI GAGAL
          loadingOverlay.style.display = 'none';
          alert('Gagal menganalisis foto, coba foto yang lebih jelas.');
        }
    } catch (error) {
        console.error('Gagal mengecek status:', error);
    }
  }, 2000);
}
</script>
@endpush