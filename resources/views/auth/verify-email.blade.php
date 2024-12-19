<x-guest-layout>
    <head>
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

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
                            <h2>Verifikasi Email</h2>
                            <p class="text-secondary">Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan ulang.</p>
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success text-sm">
                                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                            </div>
                        @endif

                        <div class="input-group mb-3">
                            <form method="POST" action="{{ route('verification.send') }}" class="w-100">
                                @csrf
                                <button class="btn btn-lg w-100 fs-6" style="background-color:#00AFEF; color:white" type="submit">Kirim Ulang Email Verifikasi</button>
                            </form>
                        </div>

                        <div class="input-group">
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button class="btn btn-outline-secondary btn-lg w-100 fs-6" type="submit">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
