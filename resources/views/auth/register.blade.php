<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>NutriQuest — Daftar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#2b1f33; --ink-soft:#6b5c74;
    --orange:#ff8a3d; --orange-deep:#e8712a;
    --blue:#3f6fe0;
    --card:#ffffff; --field:#f6f2f8; --border:#e6ddec;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  body{
    font-family:'Nunito',sans-serif;color:var(--ink);
    min-height:100vh;
  }
  h1,h2,.display{font-family:'Baloo 2',cursive;}

  .shell{display:grid;grid-template-columns:1fr 1fr;min-height:100vh;}

  /* ---- panel kiri: ilustrasi & value prop ---- */
  .illustration{
    background:linear-gradient(165deg,#d9c7e5 0%, #cf9cb3 60%, #c98fa8 100%);
    position:relative;overflow:hidden;
    padding:44px 48px;display:flex;flex-direction:column;
  }
  .illustration .cloud{position:absolute;background:rgba(255,255,255,.5);border-radius:50%;}
  .c1{width:90px;height:90px;top:60px;left:40px;}
  .c2{width:56px;height:56px;top:110px;left:110px;}
  .c3{width:70px;height:70px;top:200px;right:60px;}

  .brand{display:flex;align-items:center;gap:10px;position:relative;z-index:2;}
  .brand-mark{
    width:40px;height:40px;border-radius:12px;
    background:linear-gradient(160deg,var(--orange),var(--orange-deep));
    display:flex;align-items:center;justify-content:center;
    font-family:'Baloo 2';color:#fff;font-weight:700;font-size:18px;
  }
  .brand-name{font-family:'Baloo 2';font-weight:700;font-size:19px;color:#3c2a45;}
  .brand-sub{font-size:11.5px;color:#5b4664;font-weight:700;margin-top:-2px;}

  .hero-text{margin-top:52px;position:relative;z-index:2;max-width:380px;}
  .hero-text h1{font-size:30px;line-height:1.25;color:#3c2a45;}
  .hero-text p{margin-top:10px;font-size:14.5px;font-weight:700;color:#5b4664;line-height:1.5;}

  .bunny-wrap{position:relative;flex:1;display:flex;align-items:flex-end;justify-content:center;}
  .bunny{width:220px;position:relative;z-index:2;filter:drop-shadow(0 18px 20px rgba(60,20,50,.25));}
  .new-tag{
    position:absolute;top:8px;left:calc(50% - 130px);
    background:#7b5fe0;
    color:#fff;font-family:'Baloo 2';font-weight:700;font-size:13px;
    padding:7px 14px;border-radius:10px;z-index:3;
  }

  .feature-list{position:relative;z-index:2;display:flex;flex-direction:column;gap:14px;margin-top:26px;}
  .feature-item{display:flex;align-items:center;gap:12px;}
  .feature-icon{
    width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,.75);
    display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;
  }
  .feature-text{font-size:13px;font-weight:800;color:#3c2a45;}

  /* ---- panel kanan: form ---- */
  .form-side{display:flex;align-items:center;justify-content:center;padding:40px;background:#fbf9fc;}
  .form-card{width:100%;max-width:400px;}
  .form-card h2{font-size:24px;margin-bottom:6px;}
  .form-card p.sub{color:var(--ink-soft);font-weight:700;font-size:13.5px;margin-bottom:26px;}

  .field{margin-bottom:16px;}
  .field label{display:block;font-weight:800;font-size:13px;margin-bottom:6px;}
  .field input{
    width:100%;padding:11px 14px;border-radius:12px;border:1.5px solid var(--border);
    background:var(--field);font-family:'Nunito';font-size:14px;color:var(--ink);
  }
  .field input:focus{outline:none;border-color:var(--orange);}
  .error{color:#c0392b;font-size:12px;font-weight:700;margin-top:5px;}

  button[type=submit]{
    width:100%;margin-top:6px;
    background:linear-gradient(160deg,var(--orange),var(--orange-deep));
    color:#fff;border:none;border-radius:14px;padding:14px;
    font-family:'Baloo 2';font-weight:700;font-size:15px;cursor:pointer;
    box-shadow:0 10px 22px -10px rgba(232,113,42,.65);
  }

  .switch-row{text-align:center;margin-top:22px;font-size:13.5px;font-weight:700;color:var(--ink-soft);}
  .switch-row a{color:var(--orange-deep);font-weight:800;text-decoration:none;}
  .switch-row a:hover{text-decoration:underline;}

  @media (max-width: 880px){
    .shell{grid-template-columns:1fr;}
    .illustration{min-height:280px;padding:32px;}
    .hero-text{margin-top:20px;}
    .bunny-wrap{display:none;}
    .feature-list{margin-top:18px;}
  }
</style>
</head>
<body>
<div class="shell">

  <section class="illustration">
    <div class="cloud c1"></div><div class="cloud c2"></div><div class="cloud c3"></div>

    <div class="brand">
      <div class="brand-mark">NQ</div>
      <div>
        <div class="brand-name">NutriQuest</div>
        <div class="brand-sub">Scan. Level up. Repeat.</div>
      </div>
    </div>

    <div class="hero-text">
      <h1>Karakter barumu menunggu untuk dibesarkan.</h1>
      <p>Buat akun, isi profil tubuhmu, dan biarkan sistem menghitung kebutuhan nutrisi harianmu secara otomatis.</p>
    </div>

    <div class="feature-list">
      <div class="feature-item">
        <div class="feature-icon">🎯</div>
        <div class="feature-text">Target kalori & makro dihitung otomatis dari data tubuhmu</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">🤖</div>
        <div class="feature-text">Estimasi nutrisi otomatis lewat AI, cukup dari foto</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">🏆</div>
        <div class="feature-text">Kumpulkan streak dan pencapaian setiap hari</div>
      </div>
    </div>

    <div class="bunny-wrap">
      <div class="new-tag">Karakter baru!</div>
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
    </div>
  </section>

  <section class="form-side">
    <div class="form-card">
      <h2>Buat akun baru</h2>
      <p class="sub">Mulai perjalanan sehatmu bersama karaktermu.</p>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
          <label for="name">Nama lengkap</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
          @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
          @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" required autocomplete="new-password">
          @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="password_confirmation">Konfirmasi password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
          @error('password_confirmation') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Buat akun & mulai</button>
      </form>

      <div class="switch-row">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </div>
  </section>

</div>
</body>
</html>