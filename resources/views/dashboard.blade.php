<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>NutriQuest — Dashboard</title>
<!-- Token keamanan wajib untuk upload foto di Laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --sky-top:#cdb8dc;
    --sky-bottom:#c98fa8;
    --ground:#b97e9a;
    --card:#ffffff;
    --ink:#2b1f33;
    --ink-soft:#6b5c74;
    --orange:#ff8a3d;
    --orange-deep:#e8712a;
    --blue:#3f6fe0;
    --blue-deep:#2f57bd;
    --hp:#ff5c66;
    --xp:#ffc93c;
    --protein:#3fa7e6;
    --carbs:#4fbf7f;
    --fat:#f2b84b;
    --fiber:#b06fe0;
    --sugar:#ec6fa6;
    --sodium:#e0554f;
    --track:#ece7ee;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  body{
    font-family:'Nunito',sans-serif;
    color:var(--ink);
    background:linear-gradient(180deg,#efe9f4 0%, #efe9f4 40%, var(--ground) 100%);
    min-height:100vh;
  }
  h1,h2,h3,.display{font-family:'Baloo 2',cursive;}

  .shell{display:grid;grid-template-columns:224px 1fr;min-height:100vh;}

  /* sidebar */
  .sidebar{
    background:var(--card);
    border-right:1px solid #ece5ef;
    padding:28px 18px;
    display:flex;flex-direction:column;gap:6px;
  }
  .brand{
    display:flex;align-items:center;gap:10px;
    margin-bottom:28px;padding:0 6px;
  }
  .brand-mark{
    width:38px;height:38px;border-radius:12px;
    background:linear-gradient(160deg,var(--orange),var(--orange-deep));
    display:flex;align-items:center;justify-content:center;
    font-family:'Baloo 2';color:#fff;font-weight:700;font-size:18px;
  }
  .brand-name{font-family:'Baloo 2';font-weight:700;font-size:18px;}
  .brand-sub{font-size:11px;color:var(--ink-soft);margin-top:-2px;}

  .nav-item{
    display:flex;align-items:center;gap:12px;
    padding:11px 12px;border-radius:12px;
    font-weight:700;font-size:14.5px;color:var(--ink-soft);
    cursor:pointer;
  }
  .nav-item.active{background:#fff1e6;color:var(--orange-deep);}
  .nav-item:hover:not(.active){background:#faf7fb;}
  .nav-dot{width:8px;height:8px;border-radius:50%;background:currentColor;opacity:.55;}

  .sidebar-foot{margin-top:auto;padding-top:18px;border-top:1px solid #ece5ef;}
  .streak-card{
    background:#fff1e6;border-radius:14px;padding:14px;
  }
  .streak-num{font-family:'Baloo 2';font-size:22px;font-weight:700;color:var(--orange-deep);}
  .streak-label{font-size:12px;color:var(--ink-soft);font-weight:700;}

  /* main */
  .main{padding:28px 40px 60px;}
  .topbar{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px;}
  .topbar h1{font-size:24px;font-weight:700;}
  .topbar p{color:var(--ink-soft);font-weight:700;font-size:13.5px;margin-top:2px;}
  .scan-btn{
    display:flex;align-items:center;gap:10px;
    background:linear-gradient(160deg,var(--orange),var(--orange-deep));
    color:#fff;border:none;border-radius:14px;
    padding:13px 22px;font-family:'Baloo 2';font-weight:700;font-size:15px;
    cursor:pointer;box-shadow:0 10px 22px -10px rgba(232,113,42,.65);
  }

  .stat-card{
    background:var(--card);border-radius:20px;
    padding:22px 26px;
    box-shadow:0 18px 40px -28px rgba(60,30,60,.35);
    display:grid;grid-template-columns:auto 1.3fr 1fr;gap:28px;align-items:center;
  }
  .level-badge{
    background:linear-gradient(160deg,var(--orange),var(--orange-deep));
    color:#fff;font-family:'Baloo 2';font-weight:700;font-size:15px;
    padding:9px 18px;border-radius:12px;text-align:center;
  }
  .status-chip{
    margin-top:10px;font-size:11.5px;font-weight:800;color:#7b5fe0;
    background:#efe9fe;padding:5px 10px;border-radius:999px;text-align:center;
  }

  .bar-row{display:flex;align-items:center;gap:10px;margin-bottom:14px;}
  .bar-row:last-child{margin-bottom:0;}
  .bar-icon{width:26px;height:26px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;}
  .bar-track{flex:1;height:16px;background:var(--track);border-radius:999px;overflow:hidden;position:relative;}
  .bar-fill{height:100%;border-radius:999px;}
  .bar-fill.hp{background:linear-gradient(90deg,#ff8a8f,var(--hp));}
  .bar-fill.xp{background:linear-gradient(90deg,#ffe08a,var(--xp));}
  .bar-text{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:10.5px;font-weight:800;color:var(--ink);}

  .cal-ring-wrap{display:flex;align-items:center;gap:16px;justify-self:end;}
  .cal-num{font-family:'Baloo 2';font-size:22px;font-weight:700;}
  .cal-label{font-size:11.5px;color:var(--ink-soft);font-weight:700;}

  .macro-grid{
    display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin:22px 0;
  }
  .macro-card{
    background:var(--card);border-radius:16px;padding:14px 16px;
    box-shadow:0 14px 30px -26px rgba(60,30,60,.3);
  }
  .macro-top{display:flex;align-items:center;gap:8px;margin-bottom:8px;}
  .macro-icon{width:24px;height:24px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;}
  .macro-name{font-size:12px;font-weight:800;color:var(--ink-soft);}
  .macro-val{font-family:'Baloo 2';font-size:18px;font-weight:700;}
  .macro-val small{font-family:'Nunito';font-size:11px;color:var(--ink-soft);font-weight:700;}
  .macro-track{height:6px;border-radius:99px;background:var(--track);margin-top:8px;overflow:hidden;}
  .macro-track i{display:block;height:100%;border-radius:99px;}
  .over{color:var(--sodium);}

  .content-grid{display:grid;grid-template-columns:1fr 1.15fr;gap:22px;margin-top:8px;}

  .character-panel{
    background:linear-gradient(180deg,#d9c7e5 0%, #cf9cb3 75%, #c98fa8 100%);
    border-radius:24px;position:relative;overflow:hidden;
    min-height:420px;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;
    box-shadow:0 18px 40px -28px rgba(60,30,60,.4);
  }
  .character-panel .cloud{position:absolute;background:rgba(255,255,255,.55);border-radius:50%;}
  .c1{width:70px;height:70px;top:30px;left:24px;}
  .c2{width:44px;height:44px;top:66px;left:64px;}
  .c3{width:56px;height:56px;top:110px;right:34px;}

  .bunny{width:210px;position:relative;margin-bottom:36px;}
  .bunny-ground{
    position:absolute;bottom:-18px;left:50%;transform:translateX(-50%);
    width:150px;height:14px;background:rgba(0,0,0,.12);border-radius:50%;
    filter:blur(2px);
  }

  .char-name-tag{
    position:absolute;top:20px;left:50%;transform:translateX(-50%);
    background:rgba(255,255,255,.9);padding:8px 18px;border-radius:999px;
    font-family:'Baloo 2';font-weight:700;font-size:14px;color:var(--orange-deep);
  }

  .side-panel{display:flex;flex-direction:column;gap:20px;}

  .panel-card{
    background:var(--card);border-radius:20px;padding:20px 22px;
    box-shadow:0 14px 30px -26px rgba(60,30,60,.3);
  }
  .panel-title{font-family:'Baloo 2';font-weight:700;font-size:15px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;}
  .panel-title a{font-size:12px;font-weight:800;color:var(--blue);text-decoration:none;font-family:'Nunito';}

  .drop-zone{
    border:2px dashed #e0d3e6;border-radius:16px;
    padding:28px 20px;text-align:center;background:#fbf9fc;
  }
  .drop-zone .camera{
    width:56px;height:56px;border-radius:50%;margin:0 auto 12px;
    background:linear-gradient(160deg,var(--orange),var(--orange-deep));
    display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;
  }
  .drop-zone h3{font-size:14.5px;margin-bottom:4px;}
  .drop-zone p{font-size:12.5px;color:var(--ink-soft);font-weight:700;margin-bottom:14px;}
  .drop-zone button{
    background:var(--blue);color:#fff;border:none;border-radius:10px;
    padding:9px 18px;font-weight:800;font-size:13px;font-family:'Nunito';cursor:pointer;
  }

  .log-item{
    display:flex;align-items:center;gap:12px;padding:10px 0;
    border-bottom:1px solid #f0ebf2;
  }
  .log-item:last-child{border-bottom:none;}
  .log-thumb{width:42px;height:42px;border-radius:12px;background:#f3ecf7;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
  .log-name{font-size:13.5px;font-weight:800;}
  .log-meta{font-size:11.5px;color:var(--ink-soft);font-weight:700;}
  .log-xp{margin-left:auto;font-size:12px;font-weight:800;color:var(--orange-deep);background:#fff1e6;padding:4px 10px;border-radius:999px;}
</style>
</head>
<body>
<div class="shell">

  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">NQ</div>
      <div>
        <div class="brand-name">NutriQuest</div>
        <div class="brand-sub">Scan. Level up. Repeat.</div>
      </div>
    </div>

    <div class="nav-item active"><span class="nav-dot"></span> Home</div>
    <div class="nav-item"><span class="nav-dot"></span> Food logs</div>
    <div class="nav-item"><span class="nav-dot"></span> Character</div>
    <div class="nav-item"><span class="nav-dot"></span> Stats</div>
    <div class="nav-item"><span class="nav-dot"></span> Awards</div>
    <div class="nav-item"><span class="nav-dot"></span> Theme</div>
    <!-- Integrasi dengan sistem logout Breeze -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <div class="nav-item" onclick="event.preventDefault(); this.closest('form').submit();">
            <span class="nav-dot"></span> Logout
        </div>
    </form>

    <div class="sidebar-foot">
      <div class="streak-card">
        <div class="streak-num">7 hari</div>
        <div class="streak-label">Streak scan makanan</div>
      </div>
    </div>
  </aside>

  <main class="main">
    <!-- BLOK PERHITUNGAN DINAMIS BLADE -->
    @php
        // 1. Filter hanya log makanan yang AI-nya sudah selesai menganalisis
        $completedLogs = $todayLogs->where('status', 'completed');

        // 2. Kalkulasi total nutrisi hari ini secara otomatis
        $todayCalories = $completedLogs->sum('calories');
        $todayProtein = $completedLogs->sum('protein');
        $todayCarbs = $completedLogs->sum('carbs');
        $todayFat = $completedLogs->sum('fat');
        $todayFiber = $completedLogs->sum('fiber');
        $todaySugar = $completedLogs->sum('sugar');
        $todaySodium = $completedLogs->sum('sodium');

        // 3. Ambil target nutrisi dari database (atau berikan nilai default jika user belum isi form Body Profile)
        $targetCalories = $target->calories ?? 2000;
        $targetProtein = $target->protein ?? 60;
        $targetCarbs = $target->carbs ?? 250;
        $targetFat = $target->fat ?? 60;
        $targetFiber = $target->fiber ?? 30;
        $targetSugar = $target->sugar ?? 50;
        $targetSodium = $target->sodium ?? 2000;
        
        // 4. Perhitungan persentase Bar HP & XP Karakter
        $currentHp = $character->hp ?? 100;
        $currentXp = $character->xp ?? 0;
        // Asumsi Max XP per level adalah 120 (Bisa disesuaikan dengan logika gamification-mu)
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
      <div class="character-panel">
        <div class="cloud c1"></div><div class="cloud c2"></div><div class="cloud c3"></div>
        <div class="char-name-tag">🐰 Mochi</div>
        <svg class="bunny" viewBox="0 0 210 230" xmlns="http://www.w3.org/2000/svg">
          <!-- Karakter SVG Mochi -->
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
          
          <!-- Menampilkan Riwayat Makanan dari DB secara dinamis -->
          @forelse($todayLogs as $log)
          <div class="log-item">
            <div class="log-thumb">
               <!-- Jika masih proses, tampilkan ikon loading, jika selesai tampilkan piring -->
               @if($log->status === 'processing') ⏳ @else 🍽️ @endif
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

        </div>
      </div>
    </section>
  </main>
</div>

<!-- Input tersembunyi untuk fitur upload -->
<input type="file" id="foodPhotoInput" accept="image/*" capture="environment" hidden>

<script>
// Memicu klik pada input file ketika tombol ditekan
document.getElementById('uploadBtn').addEventListener('click', () => {
  document.getElementById('foodPhotoInput').click();
});

// Mengeksekusi API ketika file telah dipilih
document.getElementById('foodPhotoInput').addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;

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
      }
  } catch (error) {
      console.error('Terjadi kesalahan saat mengunggah:', error);
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
          // Halaman dimuat ulang agar nilai HP, XP, dan macro gizi yang baru muncul secara otomatis
          window.location.reload();
        } else if (data.food_log.status === 'failed') {
          clearInterval(interval);
          alert('Gagal menganalisis foto, coba foto yang lebih jelas.');
        }
    } catch (error) {
        console.error('Gagal mengecek status:', error);
    }
  }, 2000);
}
</script>
</body>
</html>