@extends('layouts.app')

@section('title', 'Penghargaan')

@push('styles')
<style>
  .awards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 24px;
  }
  .award-card {
      background: var(--card);
      border-radius: 20px;
      padding: 24px 16px;
      text-align: center;
      box-shadow: 0 14px 30px -26px rgba(60,30,60,.3);
      position: relative;
      transition: transform 0.2s;
  }
  .award-card:hover { transform: translateY(-5px); }
  
  .award-icon-wrapper {
      width: 70px;
      height: 70px;
      margin: 0 auto 16px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      background: linear-gradient(135deg, #fff1e6, #ffe4e6);
      border: 3px solid #fff;
      box-shadow: 0 8px 16px -8px rgba(232,113,42,.4);
  }
  
  .award-title {
      font-family: 'Baloo 2', cursive;
      font-size: 18px;
      font-weight: 700;
      color: var(--ink);
      line-height: 1.2;
      margin-bottom: 6px;
  }
  
  .award-desc {
      font-size: 12px;
      color: var(--ink-soft);
      font-weight: 700;
  }
  
  /* Desain khusus untuk lencana yang belum terbuka (Locked) */
  .award-card.locked {
      background: #f8f6f9;
      box-shadow: none;
      border: 1px solid var(--track);
  }
  .award-card.locked .award-icon-wrapper {
      background: #e4dfe7;
      box-shadow: none;
      filter: grayscale(100%);
  }
  .award-card.locked .award-title, 
  .award-card.locked .award-desc {
      color: #9a8e9e;
  }
  
  .lock-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      font-size: 14px;
      opacity: 0.7;
  }
  
  .progress-bar-wrap {
      background: var(--card);
      border-radius: 16px;
      padding: 20px;
      margin-top: 24px;
      display: flex;
      align-items: center;
      gap: 16px;
  }
  .progress-info { flex: 1; }
</style>
@endpush

@section('content')
<div class="topbar">
  <div>
    <h1>Koleksi Penghargaan</h1>
    <p>Selesaikan tantangan gizi dan penuhi lemarimu dengan lencana!</p>
  </div>
</div>

<div class="progress-bar-wrap">
    <div style="font-size: 32px;">🏆</div>
    <div class="progress-info">
        <h3 style="font-family:'Baloo 2'; font-size:18px;">Pencapaian Saat Ini</h3>
        <p style="font-size:13px; color:var(--ink-soft); font-weight:700;">
            Karakter Level {{ $level }} • {{ $totalScans }} Makanan Discan
        </p>
    </div>
</div>

<div class="awards-grid">
    @foreach($awards as $award)
        <div class="award-card {{ $award['unlocked'] ? '' : 'locked' }}">
            @if(!$award['unlocked'])
                <div class="lock-badge">🔒</div>
            @endif
            
            <div class="award-icon-wrapper">
                {{ $award['icon'] }}
            </div>
            <div class="award-title">{{ $award['name'] }}</div>
            <div class="award-desc">{{ $award['desc'] }}</div>
        </div>
    @endforeach
</div>
@endsection