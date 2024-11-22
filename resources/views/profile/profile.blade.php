@extends('mahasiswa.layouts.layout')

@section('mahasiswa_page_title')
Settings - Mahasiswa Panel
@endsection

@section('mahasiswa_layout')
<div class="container my-5">
    <h1 class="text-center mb-4">Profil Pengguna</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bagian Foto Profil -->
    <div class="mb-4 text-center">
        @if($user->profile_image)
            <img src="{{ asset('profile_images/' . $user->profile_image) }}" alt="Profile Image" class="rounded-circle" width="150" height="150">
        @else
            <p class="text-muted">Foto profil belum diupload.</p>
        @endif
    </div>

    <!-- Form Upload Foto Profil -->
    <div class="text-center mb-4">
        <form action="{{ route('profile.upload_image') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="profile_image" class="form-label">Upload Foto Profil</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Bagian Detail Profil -->
    <div class="mb-4">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        
        <!-- Menampilkan informasi kelas dan tahun angkatan hanya untuk dosen dan mahasiswa -->
        @if($user->role == 1 || $user->role == 2) 
            <p><strong>Kelas:</strong> {{ $user->classroom ? $user->classroom->name : '-' }}</p>
            <p><strong>Tahun Angkatan:</strong> {{ $user->batch_year }}</p>
        @endif
        
        <p><strong>Role:</strong> 
            @switch($user->role)
                @case(0)
                    Admin
                    @break
                @case(1)
                    Dosen
                    @break
                @case(2)
                    Mahasiswa
                    @break
                @default
                    Tidak Dikenal
            @endswitch
        </p>
    </div>

    <!-- QR Code dan Tombol Download -->
    @if($user->role == 2) <!-- Cek apakah user adalah mahasiswa -->
        <div class="text-center">
            <h4>QR Code:</h4>
            <img src="{{ asset($user->qr_code) }}" alt="QR Code" class="mb-3" width="150" height="150">
            <br>

            <!-- Tombol Download QR Code -->
            <a href="{{ asset($user->qr_code) }}" download="qr_code_{{ $user->name }}" class="btn btn-outline-primary">
                Download QR Code
            </a>
        </div>
    @else
        <p class="text-muted text-center">QR Code tidak tersedia untuk dosen dan admin.</p>
    @endif

    <!-- Form Ganti Password -->
    <div class="mt-5">
        <h3 class="text-center mb-4">Ganti Password</h3>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="current_password" class="form-label">Password Lama</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Ganti Password</button>
        </form>
    </div>

</div>
@endsection
