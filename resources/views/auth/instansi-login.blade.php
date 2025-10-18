<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penilai Alumni - Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 480px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .login-card .card-body {
            padding: 2.5rem;
        }

        .ts-control {
            padding: .5rem 1rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card mx-auto">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="card-title fw-bold">Portal Penilaian Alumni</h3>
                    <p class="text-muted">Login untuk memberikan penilaian kinerja alumni.</p>
                </div>

                <form action="{{ route('instansi.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="instansi_id" class="form-label">Nama Instansi / Perusahaan</label>
                        <select name="instansi_id" id="instansi_id" required placeholder="Ketik untuk mencari..."></select>
                         @error('instansi_id')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                         @error('password')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect('#instansi_id', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                create: false,
                // Mengambil data dari API saat pengguna mengetik
                load: function(query, callback) {
                    // Hanya lakukan pencarian jika ada input
                    if (!query.length) return callback();
                    
                    fetch(`/api/instansi/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(json => {
                            callback(json.results); // Kirim hasil ke Tom Select
                        }).catch(()=>{
                            callback();
                        });
                },
            });
        });
    </script>
</body>
</html>
