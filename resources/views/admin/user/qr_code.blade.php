@extends('admin.layouts.layout')

@section('admin_page_title')
QR Code - {{ $user->name }}
@endsection

@section('admin_layout')
<div class="container my-5">
    <h1 class="text-center mb-4">QR Code untuk {{ $user->name }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            @if($user->role == 2) <!-- Cek apakah user adalah mahasiswa -->
                <div class="mb-4">
                    <img src="{{ asset($user->qr_code) }}" alt="QR Code" class="img-fluid rounded shadow" style="max-width: 300px; height: auto;">
                </div>
                <p class="lead">Scan QR code ini untuk akses lebih lanjut.</p>
            @else
                <p class="lead text-danger">QR Code tidak tersedia untuk dosen.</p>
            @endif

            <a href="{{ route('user.manage') }}" class="btn btn-primary btn-lg">Kembali ke Manage Users</a>
        </div>
    </div>
</div>
@endsection
