<x-guest-layout>
    <head>
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>

    <div style="background-color:#00AFEF;">
        <div class="container d-flex justify-content-center align-items-center min-vh-100" style="max-width: 700px">
            <div class="row border rounded-5 p-3 bg-white shadow w-100">
                <div class="col-md-12">
                    <div class="header-text mb-4">
                        <h2>Reset Password</h2>
                        <p class="text-secondary">Masukkan informasi di bawah ini untuk mengatur ulang kata sandi Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Token Reset Password -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Alamat Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                            @error('email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kata Sandi Baru -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi Baru</label>
                            <input id="password" class="form-control" type="password" name="password" required>
                            @error('password')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Konfirmasi Kata Sandi -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                            @error('password_confirmation')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}" @required(true)></div>
                            @if($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback" style="display: block">
                                <strong>{{$errors->first('g-recaptcha-response')}}</strong>
                                </span>
                            @endif
                        <div>
                            <button class="btn btn-lg w-100 fs-6" style="background-color:#00AFEF; color:white" type="submit">Atur Ulang Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>