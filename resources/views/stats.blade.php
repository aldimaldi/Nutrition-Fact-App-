@extends('layouts.app')

@section('title', 'Statistik')

@push('styles')
<style>
  .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-top: 24px;
  }
  .stat-box {
      background: var(--card);
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 14px 30px -26px rgba(60,30,60,.3);
      display: flex;
      flex-direction: column;
      gap: 12px;
  }
  .stat-box-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
  }
  .stat-box-title {
      color: var(--ink-soft);
      font-weight: 700;
      font-size: 14px;
  }
  .stat-box-value {
      font-family: 'Baloo 2', cursive;
      font-size: 32px;
      font-weight: 700;
      line-height: 1;
      color: var(--ink);
  }
  .stat-box-subtitle {
      font-size: 12px;
      font-weight: 700;
      color: var(--ink-soft);
  }
  .insight-card {
      background: linear-gradient(135deg, #fff1e6, #ffe4e6);
      border-radius: 20px;
      padding: 24px;
      margin-top: 24px;
      display: flex;
      align-items: center;
      gap: 20px;
  }
  .insight-icon { font-size: 40px; }
  .insight-text h3 { font-size: 18px; color: var(--orange-deep); margin-bottom: 4px; }
  .insight-text p { font-size: 14px; font-weight: 600; color: var(--ink-soft); }
</style>
@endpush

@section('content')
<div class="topbar">
  <div>
    <h1>Statistik Perjalanan</h1>
    <p>Rekapitulasi pencapaian gizi karaktermu dalam 7 hari terakhir</p>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-box">
    <div class="stat-box-icon" style="background: #fff6d9;">⚡</div>
    <div>
      <div class="stat-box-title">Total XP Diperoleh</div>
      <div class="stat-box-value">+{{ $totalXpEarned }}</div>
      <div class="stat-box-subtitle">Dalam 7 Hari Terakhir</div>
    </div>
  </div>

  <div class="stat-box">
    <div class="stat-box-icon" style="background: #e6f1fb;">📷</div>
    <div>
      <div class="stat-box-title">Makanan Discan</div>
      <div class="stat-box-value">{{ $totalScans }} <span style="font-size:18px;">kali</span></div>
      <div class="stat-box-subtitle">Level Saat Ini: {{ $character->level ?? 1 }}</div>
    </div>
  </div>

  <div class="stat-box">
    <div class="stat-box-icon" style="background: #ffe4e6;">🔥</div>
    <div>
      <div class="stat-box-title">Rata-rata Kalori</div>
      <div class="stat-box-value">{{ $avgCalories }} <span style="font-size:18px;">kcal</span></div>
      <div class="stat-box-subtitle">Target Harian: {{ $target->calories ?? 2000 }} kcal</div>
    </div>
  </div>
</div>

<div class="insight-card">
  <div class="insight-icon">💡</div>
  <div class="insight-text">
    <h3>Konsistensi adalah Kunci!</h3>
    <p>Terus scan makananmu setiap hari agar <strong>{{ $character->name ?? 'Karaktermu' }}</strong> (Level {{ $character->level ?? 1 }}) cepat naik level dan HP-nya selalu terjaga. Pola makan yang seimbang akan memberikan bonus XP tambahan!</p>
  </div>
</div>
@endsection