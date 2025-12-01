<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
      /* Small custom styles to complement Bootstrap */
      :root{--brand:#0d6efd;}
      body{font-family: 'Noto Sans JP', Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;}
      .hero {
        background: linear-gradient(180deg, rgba(13,110,253,0.06), rgba(255,255,255,0.02));
      }
      .feature-icon{width:44px;height:44px;border-radius:.5rem;display:inline-grid;place-items:center;background:#fff;border:1px solid #eee}
      @media (prefers-color-scheme: dark){
        .hero{background: linear-gradient(180deg, rgba(179,0,0,0.06), rgba(0,0,0,0.2));}
      }
    </style>
  </head>
  <body class="bg-light text-dark d-flex flex-column min-vh-100">
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">{{ config('app.name', 'Sistem Peminjaman IoT') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="nav">
          <ul class="navbar-nav mb-2 mb-lg-0">
            @if (Route::has('login'))
              @auth
                <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
              @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                @if (Route::has('register'))
                  <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endif
              @endauth
            @endif
          </ul>
        </div>
      </div>
    </header>

    <main class="flex-grow-1">
      <section class="hero py-5">
        <div class="container">
          <div class="row align-items-center gy-4">
            <div class="col-lg-6">
              <h1 class="display-6 fw-bold">Sistem Peminjaman & Inventaris IoT Lab</h1>
              <p class="lead text-muted">Kelola inventaris, pinjaman alat, dan laporan untuk IoT Lab — cepat, aman, dan terorganisir. Cocok untuk IPB University.</p>

              <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk</a>
                <a href="#features" class="btn btn-outline-secondary btn-lg">Fitur</a>
              </div>

              <ul class="list-unstyled mt-4">
                <li class="mb-2"><strong>Inventory:</strong> Data alat, status, dan kategori.</li>
                <li class="mb-2"><strong>Peminjaman:</strong> Proses pinjam, kembalikan, dan approval.</li>
                <li class="mb-2"><strong>Laporan:</strong> Riwayat, stok, dan analitik sederhana.</li>
              </ul>
            </div>

            <div class="col-lg-6 text-center">
              <img src="/storage/landing-illustration.png" alt="IoT lab illustration" class="img-fluid shadow-sm" style="max-height:340px; object-fit:contain;">
              <small class="d-block text-muted mt-2">Ilustrasi (ganti dengan gambar lab Anda di `public/`)</small>
            </div>
          </div>
        </div>
      </section>

      <section id="features" class="py-5">
        <div class="container">
          <div class="text-center mb-5">
            <h2 class="h3 fw-bold">Fitur Utama</h2>
            <p class="text-muted">Semua yang diperlukan untuk mengelola inventaris dan peminjaman alat di lab Anda.</p>
          </div>

          <div class="row g-4">
            <div class="col-md-6 col-lg-3">
              <div class="card h-100 border-0 shadow-sm p-3">
                <div class="d-flex align-items-start gap-3">
                  <div class="feature-icon bg-white text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16"><path d="M9.828.122a.5.5 0 0 0-.656 0L1.5 6.793V14.5A1.5 1.5 0 0 0 3 16h10a1.5 1.5 0 0 0 1.5-1.5V6.793L9.828.122zM8 1.99L13.5 6 8 10.01 2.5 6 8 1.99z"/></svg>
                  </div>
                  <div>
                    <h5 class="mb-1">Manajemen Inventaris</h5>
                    <p class="mb-0 text-muted small">Tambah, edit, dan lacak alat beserta lokasi dan kondisi.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="card h-100 border-0 shadow-sm p-3">
                <div class="d-flex align-items-start gap-3">
                  <div class="feature-icon bg-white text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar2-check" viewBox="0 0 16 16"><path d="M10.854 6.146a.5.5 0 0 1 .11.638l-.057.07L7.5 10.27 6.093 8.863a.5.5 0 0 1 .638-.765l.07.057L7.5 9.586l3.354-3.44a.5.5 0 0 1 .707 0z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h.5A1.5 1.5 0 0 1 15 2.5v11A1.5 1.5 0 0 1 13.5 15H2.5A1.5 1.5 0 0 1 1 13.5v-11A1.5 1.5 0 0 1 2.5 1H3V.5a.5.5 0 0 1 .5-.5zM2.5 2a.5.5 0 0 0-.5.5V3h12v-.5a.5.5 0 0 0-.5-.5H13v.5a.5.5 0 0 1-1 0V2H4v.5a.5.5 0 0 1-1 0V2H2.5z"/></svg>
                  </div>
                  <div>
                    <h5 class="mb-1">Peminjaman & Pengembalian</h5>
                    <p class="mb-0 text-muted small">Proses peminjaman dengan approval, durasi, dan notifikasi status.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="card h-100 border-0 shadow-sm p-3">
                <div class="d-flex align-items-start gap-3">
                  <div class="feature-icon bg-white text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16"><path d="M0 0h1v15h15v1H0V0z"/><path d="M10 6l-3 3-2-2-4 4V12h14V6h-5z"/></svg>
                  </div>
                  <div>
                    <h5 class="mb-1">Laporan & Statistik</h5>
                    <p class="mb-0 text-muted small">Laporan riwayat peminjaman, stok, dan aktivitas lab.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="card h-100 border-0 shadow-sm p-3">
                <div class="d-flex align-items-start gap-3">
                  <div class="feature-icon bg-white text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M5.5 3a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z"/><path d="M2 8c0 1.657 1.343 3 3 3h6c1.657 0 3-1.343 3-3 0-1-1.5-1.5-4-1.5H6C3.5 6.5 2 7 2 8z"/></svg>
                  </div>
                  <div>
                    <h5 class="mb-1">Manajemen Pengguna</h5>
                    <p class="mb-0 text-muted small">Role-based access: admin, staf lab, dan mahasiswa.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white py-5 border-top">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h3 class="fw-bold">Tentang IoT Lab</h3>
              <p class="text-muted">IoT Lab IPB menyediakan fasilitas pengembangan dan penelitian perangkat IoT. Sistem ini membantu tim lab dan mahasiswa mengatur peminjaman alat dengan lebih efisien dan bertanggung jawab.</p>
            </div>
            <div class="col-md-4 text-md-end">
              <a href="#" class="btn btn-outline-danger">Hubungi Kami</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="py-4 bg-light border-top mt-5">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="small text-muted">© {{ date('Y') }} IPB University — IoT Lab</div>
        <div class="small">
          <a href="#" class="text-muted me-3">Privasi</a>
          <a href="#" class="text-muted">Bantuan</a>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>