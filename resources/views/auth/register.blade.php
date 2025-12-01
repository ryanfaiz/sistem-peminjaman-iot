<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Sistem Peminjaman IoT') }} — Daftar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <!-- Fonts + Theme -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
        @vite('resources/css/app.css')
        <style>
            :root{--brand:#b30000}
            body{font-family: 'Noto Sans JP', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;}
            .auth-card{max-width:820px;margin:2.5rem auto}
        </style>
    </head>
    <body class="bg-light text-dark d-flex flex-column min-vh-100">
        <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">{{ config('app.name', 'Sistem Peminjaman IoT') }}</a>
                <div class="d-none d-md-block">
                    <a class="btn btn-outline-secondary btn-sm me-2" href="{{ url('/') }}">Beranda</a>
                    <a class="btn btn-outline-secondary btn-sm" href="#">Bantuan</a>
                </div>
            </div>
        </header>

        <main class="flex-grow-1">
            <div class="container auth-card">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card shadow-lg">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Pendaftaran Pengguna Lab IoT IPB</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="row g-3">
                                    @csrf

                                    @if ($errors->any())
                                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                                    @endif

                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="identifier" class="form-label">NIM/NIP</label>
                                        <input type="text" class="form-control @error('identifier') is-invalid @enderror" id="identifier" name="identifier" value="{{ old('identifier') }}" required>
                                        @error('identifier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="role" class="form-label">Peran</label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                            <option value="">Pilih Peran</option>
                                            <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                        </select>
                                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="whatsapp_number" class="form-label">No. WhatsApp</label>
                                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required>
                                        @error('whatsapp_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="id_card_photo" class="form-label">Foto KTM/Pegawai</label>
                                        <input type="file" class="form-control @error('id_card_photo') is-invalid @enderror" id="id_card_photo" name="id_card_photo" required>
                                        <div class="form-text">Maks 2MB, format JPG/PNG.</div>
                                        @error('id_card_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Daftar Akun</button>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <a href="{{ route('login') }}">Sudah punya akun? Login di sini.</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-4 bg-white border-top mt-5">
            <div class="container text-center small text-muted">© {{ date('Y') }} {{ config('app.name') }}</div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>