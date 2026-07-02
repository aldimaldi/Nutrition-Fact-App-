@extends('layouts.app')

@section('title', 'Karakter')

@push('styles')
<style>
  /* Tambahan CSS khusus untuk halaman Karakter */
  .avatar-grid { 
      display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); 
      gap: 16px; margin-top: 16px; 
  }
  .avatar-option {
      border: 2px solid var(--track); border-radius: 16px; padding: 16px; text-align: center;
      cursor: pointer; transition: all 0.2s; background: var(--card);
  }
  .avatar-option:hover { border-color: var(--sky-top); }
  
  /* Efek ketika radio button disorot/dipilih */
  .avatar-input:checked + .avatar-option {
      border-color: var(--orange-deep); background: #fff1e6;
  }
  
  .avatar-preview { 
      width: 60px; height: 60px; margin: 0 auto 12px; background: var(--track); 
      border-radius: 50%; display: flex; align-items: center; justify-content: center; 
      font-size: 28px; 
  }
  
  .form-group { margin-bottom: 24px; }
  .form-label { display: block; font-weight: 700; color: var(--ink-soft); margin-bottom: 8px; }
  .form-input {
      width: 100%; padding: 12px 16px; border: 2px solid var(--track); border-radius: 12px;
      font-family: 'Nunito', sans-serif; font-size: 15px; color: var(--ink); outline: none; 
      transition: 0.2s;
  }
  .form-input:focus { border-color: var(--orange); }
  
  .alert-success { 
      background: #eaf7ee; color: var(--carbs); padding: 12px 16px; 
      border-radius: 12px; font-weight: 700; margin-bottom: 24px; 
      display: flex; align-items: center; gap: 8px; 
  }
</style>
@endpush

@section('content')
<div class="topbar">
  <div>
    <h1>Pengaturan Karakter</h1>
    <p>Pilih avatar dan berikan nama untuk teman dietmu</p>
  </div>
</div>

<div class="panel-card" style="max-width: 600px;">
  
  <!-- Notifikasi Sukses -->
  @if(session('success'))
    <div class="alert-success">
      <span>✔️</span> {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('character.update') }}" method="POST">
    @csrf
    
    <div class="form-group">
      <label class="form-label">Nama Karakter</label>
      <input type="text" name="name" class="form-input" 
             value="{{ old('name', $character->name ?? '') }}" 
             required placeholder="Contoh: Mochi">
    </div>

    <div class="form-group">
      <label class="form-label">Pilih Avatar</label>
      <div class="avatar-grid">
        
        <!-- Pilihan Bunny -->
        <label>
          <input type="radio" name="avatar" value="bunny" class="avatar-input" hidden 
                 {{ ($character->avatar ?? 'bunny') === 'bunny' ? 'checked' : '' }}>
          <div class="avatar-option">
            <div class="avatar-preview">🐰</div>
            <strong>Bunny</strong>
          </div>
        </label>

        <!-- Pilihan Cat -->
        <label>
          <input type="radio" name="avatar" value="cat" class="avatar-input" hidden 
                 {{ ($character->avatar ?? '') === 'cat' ? 'checked' : '' }}>
          <div class="avatar-option">
            <div class="avatar-preview">🐱</div>
            <strong>Cat</strong>
          </div>
        </label>

        <!-- Pilihan Bear -->
        <label>
          <input type="radio" name="avatar" value="bear" class="avatar-input" hidden 
                 {{ ($character->avatar ?? '') === 'bear' ? 'checked' : '' }}>
          <div class="avatar-option">
            <div class="avatar-preview">🐻</div>
            <strong>Bear</strong>
          </div>
        </label>

      </div>
    </div>

    <button type="submit" class="scan-btn" style="width: 100%; justify-content: center;">
        Simpan Karakter
    </button>
  </form>
</div>
@endsection