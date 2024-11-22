@extends('mahasiswa.layouts.layout')

@section('mahasiswa_page_title', 'FAQ - Mahasiswa Panel')

@section('mahasiswa_layout')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center font-weight-bold mb-4">Frequently Asked Questions</h3>

            <div class="list-group">

                <!-- Pertanyaan 1 -->
                <div class="list-group-item p-3 mb-3 shadow-sm rounded">
                    <h5 class="text-dark font-weight-bold">Bagaimana cara mengakses dashboard mahasiswa?</h5>
                    <p class="text-muted">
                        Anda dapat mengakses dashboard mahasiswa melalui halaman utama dengan login menggunakan akun yang sudah terdaftar.
                    </p>
                </div>

                <!-- Pertanyaan 2 -->
                <div class="list-group-item p-3 mb-3 shadow-sm rounded">
                    <h5 class="text-dark font-weight-bold">Bagaimana cara mengganti foto profil?</h5>
                    <p class="text-muted">
                        Untuk mengganti foto profil, klik pada bagian profil di dashboard, lalu pilih opsi "Edit Profil". Upload foto baru dan simpan perubahan.
                    </p>
                </div>

                <!-- Pertanyaan 3 -->
                <div class="list-group-item p-3 mb-3 shadow-sm rounded">
                    <h5 class="text-dark font-weight-bold">Apa yang harus saya lakukan jika lupa password?</h5>
                    <p class="text-muted">
                        Jika Anda lupa password, klik "Lupa Password" di halaman login, lalu ikuti langkah-langkah untuk mereset password Anda.
                    </p>
                </div>

                <!-- Pertanyaan 4 -->
                <div class="list-group-item p-3 mb-3 shadow-sm rounded">
                    <h5 class="text-dark font-weight-bold">Apakah ada aplikasi mobile untuk akses dashboard?</h5>
                    <p class="text-muted">
                        Saat ini, dashboard mahasiswa hanya dapat diakses melalui browser desktop atau mobile.
                    </p>
                </div>

                

            </div>
        </div>
    </div>
</div>
@endsection
