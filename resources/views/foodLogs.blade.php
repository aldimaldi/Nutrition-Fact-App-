@extends('layouts.app')

@section('title', 'Food Logs')

@section('content')
    <div class="topbar">
      <div>
        <h1>Semua Riwayat Makanan</h1>
        <p>Jejak petualangan gizi karaktermu tersimpan di sini</p>
      </div>
    </div>

    <section class="history-grid">
      @forelse($logs as $log)
      <div class="history-card">
        <div class="history-photo">
            @if($log->status === 'processing') 
                ⏳ 
            @elseif($log->photo_path)
                <img src="{{ asset('storage/' . $log->photo_path) }}" alt="Foto Makanan" loading="lazy">
            @else 
                🍽️ 
            @endif
        </div>
        
        <div class="history-info">
            <div class="history-head">
                <div>
                    <div class="history-title">{{ $log->food_name ?? 'Menganalisis foto...' }}</div>
                    <div class="history-time">
                        📅 {{ \Carbon\Carbon::parse($log->eaten_at)->format('d M Y') }} &nbsp;•&nbsp; 
                        🕒 {{ \Carbon\Carbon::parse($log->eaten_at)->format('H:i') }}
                    </div>
                </div>
                
                @if($log->status === 'completed')
                    <div class="history-xp">+{{ $log->xp_earned ?? 0 }} XP</div>
                @else
                    <div class="history-status-processing">Processing</div>
                @endif
            </div>

            @if($log->status === 'completed')
            <div class="history-macros">
                <div class="macro-badge"><i style="color:var(--orange-deep)">🔥</i> {{ $log->calories }} <span>kcal</span></div>
                <div class="macro-badge"><i style="color:var(--protein)">🐟</i> {{ $log->protein_g }}g <span>Protein</span></div>
                <div class="macro-badge"><i style="color:var(--carbs)">🌿</i> {{ $log->carbs_g }}g <span>Carbs</span></div>
                <div class="macro-badge"><i style="color:var(--fat)">🥑</i> {{ $log->fat_g }}g <span>Fat</span></div>
                <div class="macro-badge"><i style="color:var(--fiber)">🫐</i> {{ $log->fiber_g }}g <span>Fiber</span></div>
                <div class="macro-badge"><i style="color:var(--sugar)">🍬</i> {{ $log->sugar_g }}g <span>Sugar</span></div>
                <div class="macro-badge"><i style="color:var(--sodium)">🧂</i> {{ $log->sodium_mg }}mg <span>Sodium</span></div>
            </div>
            @endif
        </div>
      </div>
      @empty
      <div class="empty-state">
          <h3>Belum ada riwayat makanan</h3>
          <p style="margin-top: 8px;">Karaktermu masih menunggu makanan pertamanya!</p>
      </div>
      @endforelse
    </section>

    <!-- Menampilkan tombol navigasi pagination bawaan Laravel -->
    <div class="pagination-container">
        {{ $logs->links() }}
    </div>

@endsection