<x-guest-layout>
    <head>
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <div style="background-color:#00AFEF;">
        <div class="container d-flex justify-content-center align-items-center min-vh-100" style="max-width: 700px">
            <div class="row border rounded-5 p-3 bg-white shadow w-100">
                <div class="col-md-12">
                    <div class="header-text mb-4">
                        <h2>Lupa Kata Sandi</h2>
                        <p class="text-secondary">Lupa kata sandi Anda? Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
                    </div>

                    <!-- Status Sesi -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Alamat Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <button class="btn btn-lg w-100 fs-6" style="background-color:#00AFEF; color:white" type="submit">Kirim Tautan Reset Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
