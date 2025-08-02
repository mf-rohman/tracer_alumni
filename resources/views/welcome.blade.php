<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracer Study Alumni</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles (Using Bootstrap CDN for quick styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .checker-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .checker-card {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checker-container">
            <div class="card checker-card">
                <div class="card-body text-center">
                    <h1 class="card-title mb-4">Tracer Study Alumni</h1>
                    <p class="text-muted mb-4">Silakan masukkan Nomor Pokok Mahasiswa (NPM) Anda untuk melanjutkan.</p>

                    {{-- Form untuk Cek NPM --}}
                    <form action="{{ route('cek.npm') }}" method="POST">
                        @csrf  {{-- Token keamanan wajib di Laravel --}}

                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg text-center" name="npm" placeholder="Masukkan NPM Anda" required value="{{ old('npm') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Cek NPM</button>
                    </form>

                    {{-- Menampilkan pesan sukses atau error dari controller --}}
                    @if (session('success'))
                        <div class="alert alert-success mt-4">
                            {{ session('success') }}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="btn btn-success">Lanjut ke Halaman Login</a>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mt-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('login') }}">Login sebagai Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
