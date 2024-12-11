<x-guest-layout>
    <head>
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div style="background-color:#00AFEF;">
        <div class="container d-flex justify-content-center align-items-center min-vh-100" style="max-width: 700px">
            <div class="row border rounded-5 p-3 bg-white shadow w-100">
                <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #ffffff;">
                    <div class="featured-image mb-3">
                        <img src="{{ asset('admin_asset/img/login/Absen-In.png') }}" class="img-fluid" style="width: 100%; max-width: 400px;">
                    </div>
                </div>
                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text mb-4">
                            <h2>Selamat Datang!</h2>
                            <p>Silahkan Login Menggunakan Akun Anda.</p>
                        </div>
                        <div class="input-group mb-3">
                            <input id="email" type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-group mb-1">
                            <input id="password" type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" required autocomplete="current-password">
                            @error('password')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label for="remember_me" class="form-check-label text-secondary"><small>Ingat saya</small></label>
                            </div>
                            <div class="forgot">
                                <small><a href="{{ route('password.request') }}">Lupa Password?</a></small>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button class="btn btn-lg w-100 fs-6" style="background-color:#00AFEF; color:white" type="submit">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
</x-guest-layout>
