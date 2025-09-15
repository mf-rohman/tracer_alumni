<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Identitas - Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .verify-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-left: 350px;
            
        }
        .verify-card .card-header {
            background-color: #4B49AC;
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        .verify-card .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card verify-card">
            <div class="card-header text-center">
                <h4 class="mb-0">Verifikasi Identitas</h4>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-4">
                    Untuk keamanan akun Anda, silakan masukkan Nomor Induk Kependudukan (NIK) yang terdaftar.
                </p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('alumni.login.verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="npm" value="{{ $npm }}">

                    <div class="mb-4">
                        <label for="nik" class="form-label fw-bold">NIK</label>
                        <input type="password" name="nik" id="nik" class="form-control form-control-lg" placeholder="Masukkan 16 digit NIK Anda..." required autofocus>
                         @error('nik')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        <a href="{{ route('landing') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

