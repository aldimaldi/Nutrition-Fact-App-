<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NutriQuest — Ubah Pola Makan Jadi Petualangan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Menggunakan ulang variabel warna dari dashboard agar transisi halaman terasa mulus */
        :root {
            --sky-top: #cdb8dc; --sky-bottom: #c98fa8; --ground: #b97e9a; --card: #ffffff;
            --ink: #2b1f33; --ink-soft: #6b5c74; --orange: #ff8a3d; --orange-deep: #e8712a;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Nunito', sans-serif;
            color: var(--ink);
            background: linear-gradient(180deg, #efe9f4 0%, #efe9f4 55%, var(--ground) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        h1, h2, h3 { font-family: 'Baloo 2', cursive; }
        a { text-decoration: none; }

        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            padding: 0 24px; 
            width: 100%;
        }

        /* --- NAVBAR --- */
        header { 
            padding: 24px 0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-mark {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(160deg, var(--orange), var(--orange-deep));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Baloo 2'; color: #fff; font-weight: 800; font-size: 22px;
            box-shadow: 0 8px 16px -8px rgba(232,113,42,.6);
        }
        .brand-name { font-family: 'Baloo 2'; font-weight: 800; font-size: 26px; color: var(--ink); line-height: 1; }
        .brand-sub { font-size: 13px; color: var(--ink-soft); font-weight: 700; }

        .nav-links { display: flex; gap: 16px; align-items: center; }
        .btn-outline {
            padding: 10px 22px; border-radius: 12px; font-weight: 800; font-size: 15px;
            color: var(--orange-deep); background: #fff1e6; transition: 0.2s;
        }
        .btn-outline:hover { background: #ffe4cc; }
        .btn-solid {
            padding: 10px 22px; border-radius: 12px; font-weight: 800; font-size: 15px;
            background: linear-gradient(160deg, var(--orange), var(--orange-deep));
            color: #fff; box-shadow: 0 8px 16px -8px rgba(232,113,42,.6); transition: 0.2s;
        }
        .btn-solid:hover { transform: translateY(-2px); box-shadow: 0 12px 20px -8px rgba(232,113,42,.8); }

        /* --- HERO SECTION --- */
        .hero { text-align: center; padding: 70px 0 60px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff1e6; color: var(--orange-deep);
            padding: 8px 20px; border-radius: 99px; font-size: 14.5px; font-weight: 800;
            margin-bottom: 24px; font-family: 'Nunito', sans-serif;
            border: 1px solid #ffe4cc;
        }
        .hero h1 { font-size: 64px; font-weight: 800; line-height: 1.1; margin-bottom: 20px; color: var(--ink); }
        .hero p { font-size: 18px; color: var(--ink-soft); max-width: 650px; margin: 0 auto 40px; line-height: 1.6; font-weight: 600; }
        .hero-buttons { display: flex; gap: 16px; justify-content: center; }

        /* --- FEATURES GRID --- */
        .features { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 24px; 
            padding-bottom: 80px; 
        }
        .feature-card {
            background: var(--card); padding: 36px 28px; border-radius: 24px;
            text-align: center; box-shadow: 0 20px 40px -20px rgba(60,30,60,.15);
            transition: transform 0.3s ease;
            border: 1px solid rgba(255,255,255,0.5);
        }
        .feature-card:hover { transform: translateY(-8px); }
        .feature-icon {
            width: 72px; height: 72px; border-radius: 20px; margin: 0 auto 24px;
            display: flex; align-items: center; justify-content: center; font-size: 36px;
        }
        .feature-card h3 { font-size: 22px; margin-bottom: 12px; color: var(--ink); }
        .feature-card p { font-size: 14.5px; color: var(--ink-soft); font-weight: 600; line-height: 1.6; }

        /* --- FOOTER --- */
        footer { 
            text-align: center; padding: 40px 0; color: #fff; 
            font-weight: 700; font-size: 14px; margin-top: auto; 
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Responsivitas untuk layar HP */
        @media (max-width: 768px) {
            .hero h1 { font-size: 42px; }
            .hero p { font-size: 16px; padding: 0 16px; }
            .hero-buttons { flex-direction: column; padding: 0 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="brand">
                <div class="brand-mark">NQ</div>
                <div>
                    <div class="brand-name">NutriQuest</div>
                    <div class="brand-sub">Scan. Level up. Repeat.</div>
                </div>
            </div>

            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-solid">Masuk Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline hidden-mobile">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-solid">Daftar Sekarang</a>
                        @endif
                    @endauth
                </div>
            @endif
        </header>

        <section class="hero">
            <div class="hero-badge">⚔️ Membawa RPG ke Dunia Nyata</div>
            <h1>Ubah Pola Makan<br>Jadi Petualangan Seru!</h1>
            <p>Berhenti menghitung kalori dengan cara membosankan. Pindai makananmu menggunakan kecerdasan buatan, dapatkan poin XP, dan jadilah pahlawan gizi untuk karakter peliharaanmu.</p>
            
            @if (Route::has('login'))
                <div class="hero-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-solid" style="padding: 16px 36px; font-size: 18px;">Lanjutkan Petualangan</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-solid" style="padding: 16px 36px; font-size: 18px;">Mulai Bermain — Gratis</a>
                    @endauth
                </div>
            @endif
        </section>

        <section class="features">
            <div class="feature-card">
                <div class="feature-icon" style="background: #eaf7ee;">📷</div>
                <h3>AI Food Scanner</h3>
                <p>Cukup potret piringmu. Kecerdasan buatan kami akan langsung membedah kalori, protein, lemak, dan karbohidrat dalam hitungan detik.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: #fff6d9;">⚡</div>
                <h3>Naikkan Level Karakter</h3>
                <p>Makan sehat bukan lagi beban. Dapatkan XP setiap kali kamu menyeimbangkan nutrisi dan hindari makanan over-kalori agar HP karaktermu terjaga.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: #ffe4e6;">🏆</div>
                <h3>Koleksi Lencana</h3>
                <p>Selesaikan berbagai misi rahasia, pertahankan rekor beruntun (streak), dan penuhi lemari trofimu dengan penghargaan eksklusif.</p>
            </div>
        </section>
    </div>

    <footer>
        &copy; {{ date('Y') }} NutriQuest. Dirancang untuk masa depan kesehatan interaktif.
    </footer>
</body>
</html>