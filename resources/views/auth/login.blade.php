<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Sistem Peminjaman IoT') }} — Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
        @vite('resources/css/app.css')
        <style>
            :root{--brand:#b30000}
            body{font-family: 'Noto Sans JP', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;}
            .feature-icon{width:44px;height:44px;border-radius:.5rem;display:inline-grid;place-items:center;background:#fff;border:1px solid #eee}
            .auth-card{max-width:520px;margin:3.5rem auto}
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
                        <div class="card shadow">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0">Login Peminjaman IoT</h4>
                                    </div>
                            <div class="card-body">
                                <form method="POST" action="/login">
                                    @csrf

                                    @if ($errors->any())
                                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="d-grid mt-3">
                                        <button type="submit" class="btn btn-primary">Masuk</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>.</small>
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